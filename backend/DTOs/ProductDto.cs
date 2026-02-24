namespace ECommerceApi.DTOs;

public class ProductDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string? ProductId { get; set; }
    public int Quantity { get; set; }
    public string? Category { get; set; }
    public string? SubCategory { get; set; }
    public decimal Price { get; set; }
    public string? ImagePath { get; set; }
    public string? Description { get; set; }
}

public class CreateProductRequest
{
    public string Name { get; set; } = string.Empty;
    public string ProductId { get; set; } = string.Empty;
    public int Quantity { get; set; }
    public string Category { get; set; } = string.Empty;
    public string SubCategory { get; set; } = string.Empty;
    public decimal Price { get; set; }
    public string? Description { get; set; }
}
