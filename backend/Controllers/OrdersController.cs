using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using ECommerceApi.Data;
using ECommerceApi.DTOs;
using ECommerceApi.Models;

namespace ECommerceApi.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class OrdersController : ControllerBase
{
    private readonly AppDbContext _db;

    public OrdersController(AppDbContext db) => _db = db;

    private int GetUserId()
    {
        var id = User.FindFirstValue(ClaimTypes.NameIdentifier);
        return int.Parse(id ?? "0");
    }

    [HttpGet]
    public async Task<ActionResult<List<OrderDto>>> GetMyOrders()
    {
        var userId = GetUserId();
        var list = await _db.Orders
            .Where(o => o.UserId == userId)
            .Include(o => o.Product)
            .OrderByDescending(o => o.OrderDate)
            .Select(o => new OrderDto
            {
                Id = o.Id,
                UserId = o.UserId,
                ProductId = o.ProductId,
                ProductName = o.Product.Name,
                Quantity = o.Quantity,
                TotalPrice = o.TotalPrice,
                PaymentMethod = o.PaymentMethod,
                OrderDate = o.OrderDate
            })
            .ToListAsync();
        return Ok(list);
    }

    [HttpPost]
    public async Task<ActionResult<OrderDto>> PlaceOrder([FromBody] PlaceOrderRequest req)
    {
        var userId = GetUserId();
        var product = await _db.Products.FindAsync(req.ProductId);
        if (product == null) return NotFound("Product not found.");
        if (req.Quantity < 1 || req.Quantity > product.Quantity)
            return BadRequest("Invalid quantity or insufficient stock.");

        var totalPrice = product.Price * req.Quantity;
        var order = new Order
        {
            UserId = userId,
            ProductId = product.Id,
            Quantity = req.Quantity,
            TotalPrice = totalPrice,
            PaymentMethod = req.PaymentMethod
        };
        _db.Orders.Add(order);
        product.Quantity -= req.Quantity;
        await _db.SaveChangesAsync();

        return Ok(new OrderDto
        {
            Id = order.Id,
            UserId = order.UserId,
            ProductId = order.ProductId,
            ProductName = product.Name,
            Quantity = order.Quantity,
            TotalPrice = order.TotalPrice,
            PaymentMethod = order.PaymentMethod,
            OrderDate = order.OrderDate
        });
    }
}
