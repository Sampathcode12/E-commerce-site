namespace ECommerceApi.DTOs;

public class OrderDto
{
    public int Id { get; set; }
    public int UserId { get; set; }
    public int ProductId { get; set; }
    public string? ProductName { get; set; }
    public int Quantity { get; set; }
    public decimal TotalPrice { get; set; }
    public string PaymentMethod { get; set; } = string.Empty;
    public DateTime OrderDate { get; set; }
}

public class PlaceOrderRequest
{
    public int ProductId { get; set; }
    public int Quantity { get; set; }
    public string PaymentMethod { get; set; } = "credit_card";
}
