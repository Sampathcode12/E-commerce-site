namespace ECommerceApi.Models;

public class Product
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
