namespace ECommerceApi.Models;

public class Order
{
    public int Id { get; set; }
    public int UserId { get; set; }
    public DateTime OrderDate { get; set; }
    public string PaymentMethod { get; set; } = string.Empty;

    public User User { get; set; } = null!;
    public ICollection<Sell> Sells { get; set; } = new List<Sell>();
}
