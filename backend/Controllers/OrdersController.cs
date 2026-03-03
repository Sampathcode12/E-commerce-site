using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using ECommerceApi.Data;
using ECommerceApi.DTOs;
using ECommerceApi.Models;

namespace ECommerceApi.Controllers;

[ApiController]
[Route("api/orders")]
[Authorize]
public class OrdersController : ControllerBase
{
    private readonly AppDbContext _db;

    public OrdersController(AppDbContext db) => _db = db;

    private int? GetCurrentUserId()
    {
        var id = User.FindFirstValue(ClaimTypes.NameIdentifier);
        return int.TryParse(id, out var uid) ? uid : null;
    }

    /// <summary>Place a single-item order (or one item).</summary>
    [HttpPost]
    public async Task<ActionResult<OrderDto>> PlaceOrder([FromBody] PlaceOrderRequest req)
    {
        var userId = GetCurrentUserId();
        if (userId == null) return Unauthorized();

        var product = await _db.Products.FindAsync(req.ProductId);
        if (product == null) return BadRequest(new { message = "Product not found." });
        var qty = Math.Max(1, Math.Min(req.Quantity, product.Quantity));
        var totalPrice = product.Price * qty;
        var paymentMethod = string.IsNullOrWhiteSpace(req.PaymentMethod) ? "credit_card" : req.PaymentMethod.Trim();

        var order = new Order
        {
            UserId = userId.Value,
            OrderDate = DateTime.UtcNow,
            PaymentMethod = paymentMethod
        };
        _db.Orders.Add(order);
        await _db.SaveChangesAsync();

        var sell = new Sell
        {
            OrderId = order.Id,
            UserId = userId.Value,
            ProductId = product.Id,
            Quantity = qty,
            TotalPrice = totalPrice,
            PaymentMethod = paymentMethod,
            SellDate = DateTime.UtcNow
        };
        _db.Sells.Add(sell);
        product.Quantity -= qty;
        await _db.SaveChangesAsync();

        return Ok(ToOrderDto(order, new List<Sell> { sell }));
    }

    /// <summary>Place an order with multiple items (cart checkout).</summary>
    [HttpPost("cart")]
    public async Task<ActionResult<OrderDto>> PlaceOrderCart([FromBody] PlaceOrderCartRequest req)
    {
        var userId = GetCurrentUserId();
        if (userId == null) return Unauthorized();
        if (req.Items == null || req.Items.Count == 0)
            return BadRequest(new { message = "No items in order." });

        var paymentMethod = string.IsNullOrWhiteSpace(req.PaymentMethod) ? "credit_card" : req.PaymentMethod.Trim();
        var order = new Order
        {
            UserId = userId.Value,
            OrderDate = DateTime.UtcNow,
            PaymentMethod = paymentMethod
        };
        _db.Orders.Add(order);
        await _db.SaveChangesAsync();

        var sells = new List<Sell>();
        foreach (var item in req.Items)
        {
            var product = await _db.Products.FindAsync(item.ProductId);
            if (product == null) continue;
            var qty = Math.Max(1, Math.Min(item.Quantity, product.Quantity));
            var totalPrice = product.Price * qty;
            var sell = new Sell
            {
                OrderId = order.Id,
                UserId = userId.Value,
                ProductId = product.Id,
                Quantity = qty,
                TotalPrice = totalPrice,
                PaymentMethod = paymentMethod,
                SellDate = DateTime.UtcNow
            };
            _db.Sells.Add(sell);
            product.Quantity -= qty;
            sells.Add(sell);
        }
        await _db.SaveChangesAsync();

        return Ok(ToOrderDto(order, sells));
    }

    /// <summary>Get current user's orders.</summary>
    [HttpGet]
    public async Task<ActionResult<List<OrderDto>>> GetMyOrders()
    {
        var userId = GetCurrentUserId();
        if (userId == null) return Unauthorized();

        var orders = await _db.Orders
            .Where(o => o.UserId == userId)
            .Include(o => o.Sells)
            .OrderByDescending(o => o.OrderDate)
            .ToListAsync();

        var list = new List<OrderDto>();
        foreach (var order in orders)
        {
            var items = new List<OrderItemDto>();
            foreach (var s in order.Sells)
            {
                var p = await _db.Products.FindAsync(s.ProductId);
                items.Add(new OrderItemDto
                {
                    SellId = s.Id,
                    ProductId = s.ProductId,
                    ProductName = p?.Name ?? "Product",
                    Quantity = s.Quantity,
                    TotalPrice = s.TotalPrice
                });
            }
            list.Add(new OrderDto
            {
                Id = order.Id,
                OrderDate = order.OrderDate,
                PaymentMethod = order.PaymentMethod,
                Items = items,
                TotalAmount = items.Sum(i => i.TotalPrice)
            });
        }
        return Ok(list);
    }

    /// <summary>Admin: get all orders from all users (admin only).</summary>
    [HttpGet("admin/orders")]
    [Authorize(Policy = "AdminOrSeller")]
    public async Task<ActionResult<List<OrderWithCustomerDto>>> GetAllOrders()
    {
        var orders = await _db.Orders
            .Include(o => o.Sells)
            .OrderByDescending(o => o.OrderDate)
            .ToListAsync();

        var list = new List<OrderWithCustomerDto>();
        foreach (var order in orders)
        {
            var user = await _db.Users.FindAsync(order.UserId);
            var items = new List<OrderItemDto>();
            foreach (var s in order.Sells)
            {
                var p = await _db.Products.FindAsync(s.ProductId);
                items.Add(new OrderItemDto
                {
                    SellId = s.Id,
                    ProductId = s.ProductId,
                    ProductName = p?.Name ?? "Product",
                    Quantity = s.Quantity,
                    TotalPrice = s.TotalPrice
                });
            }
            list.Add(new OrderWithCustomerDto
            {
                Id = order.Id,
                UserId = order.UserId,
                CustomerEmail = user?.Email ?? "",
                OrderDate = order.OrderDate,
                PaymentMethod = order.PaymentMethod,
                Items = items,
                TotalAmount = items.Sum(i => i.TotalPrice)
            });
        }
        return Ok(list);
    }

    /// <summary>Admin: get all selling details (sales).</summary>
    [HttpGet("admin/sales")]
    [Authorize(Policy = "AdminOrSeller")]
    public async Task<ActionResult<List<SellDetailDto>>> GetAllSales()
    {
        var sells = await _db.Sells
            .OrderByDescending(s => s.SellDate)
            .ToListAsync();
        var list = new List<SellDetailDto>();
        foreach (var s in sells)
        {
            var user = await _db.Users.FindAsync(s.UserId);
            var product = await _db.Products.FindAsync(s.ProductId);
            var unitPrice = s.Quantity > 0 ? s.TotalPrice / s.Quantity : 0;
            list.Add(new SellDetailDto
            {
                Id = s.Id,
                OrderId = s.OrderId,
                SellDate = s.SellDate,
                CustomerEmail = user?.Email ?? "",
                ProductName = product?.Name ?? "Product",
                ProductId = s.ProductId,
                Quantity = s.Quantity,
                UnitPrice = unitPrice,
                TotalPrice = s.TotalPrice,
                PaymentMethod = s.PaymentMethod ?? ""
            });
        }
        return Ok(list);
    }

    private static OrderDto ToOrderDto(Order order, List<Sell> sells)
    {
        var items = sells.Select(s => new OrderItemDto
        {
            SellId = s.Id,
            ProductId = s.ProductId,
            ProductName = "", // caller can skip or fill
            Quantity = s.Quantity,
            TotalPrice = s.TotalPrice
        }).ToList();
        return new OrderDto
        {
            Id = order.Id,
            OrderDate = order.OrderDate,
            PaymentMethod = order.PaymentMethod,
            Items = items,
            TotalAmount = items.Sum(i => i.TotalPrice)
        };
    }
}
