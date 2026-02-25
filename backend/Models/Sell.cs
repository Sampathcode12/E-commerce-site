namespace ECommerceApi.Models;

public class Sell
{
    public int Id { get; set; }
    public int OrderId { get; set; }
    public int UserId { get; set; }
    public int ProductId { get; set; }
    public int Quantity { get; set; }
    public decimal TotalPrice { get; set; }
    public string PaymentMethod { get; set; } = string.Empty;
    public DateTime SellDate { get; set; }
}
