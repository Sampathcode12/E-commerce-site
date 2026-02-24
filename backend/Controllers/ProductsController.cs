using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using ECommerceApi.Data;
using ECommerceApi.DTOs;
using ECommerceApi.Models;

namespace ECommerceApi.Controllers;

[ApiController]
[Route("api/[controller]")]
public class ProductsController : ControllerBase
{
    private readonly AppDbContext _db;

    public ProductsController(AppDbContext db) => _db = db;

    [HttpGet]
    public async Task<ActionResult<List<ProductDto>>> GetAll()
    {
        var list = await _db.Products
            .Select(p => new ProductDto
            {
                Id = p.Id,
                Name = p.Name,
                ProductId = p.ProductId,
                Quantity = p.Quantity,
                Category = p.Category,
                SubCategory = p.SubCategory,
                Price = p.Price,
                ImagePath = p.ImagePath,
                Description = p.Description
            })
            .ToListAsync();
        return Ok(list);
    }

    [HttpGet("{id:int}")]
    public async Task<ActionResult<ProductDto>> GetById(int id)
    {
        var p = await _db.Products.FindAsync(id);
        if (p == null) return NotFound();
        return Ok(new ProductDto
        {
            Id = p.Id,
            Name = p.Name,
            ProductId = p.ProductId,
            Quantity = p.Quantity,
            Category = p.Category,
            SubCategory = p.SubCategory,
            Price = p.Price,
            ImagePath = p.ImagePath,
            Description = p.Description
        });
    }

    [HttpPost]
    [Microsoft.AspNetCore.Authorization.Authorize(Roles = "seller,admin")]
    public async Task<ActionResult<ProductDto>> Create([FromBody] CreateProductRequest req)
    {
        if (await _db.Products.AnyAsync(x => x.ProductId == req.ProductId))
            return BadRequest("Product ID already exists.");

        var product = new Product
        {
            Name = req.Name,
            ProductId = req.ProductId,
            Quantity = req.Quantity,
            Category = req.Category,
            SubCategory = req.SubCategory,
            Price = req.Price,
            Description = req.Description,
            ImagePath = "/uploads/placeholder.png"
        };
        _db.Products.Add(product);
        await _db.SaveChangesAsync();

        return CreatedAtAction(nameof(GetById), new { id = product.Id }, new ProductDto
        {
            Id = product.Id,
            Name = product.Name,
            ProductId = product.ProductId,
            Quantity = product.Quantity,
            Category = product.Category,
            SubCategory = product.SubCategory,
            Price = product.Price,
            ImagePath = product.ImagePath,
            Description = product.Description
        });
    }
}
