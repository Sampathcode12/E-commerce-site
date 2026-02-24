namespace ECommerceApi.Models;

public class Seller
{
    public int Id { get; set; }
    public int SellerId { get; set; }
    public string SellerName { get; set; } = string.Empty;
    public string PasswordHash { get; set; } = string.Empty;
    public string SellerEmail { get; set; } = string.Empty;
    public string BusinessName { get; set; } = string.Empty;
    public string BusinessEmail { get; set; } = string.Empty;

    public User User { get; set; } = null!;
}
