namespace ECommerceApi.Models;

public class User
{
    public int Id { get; set; }
    public string FirstName { get; set; } = string.Empty;
    public string LastName { get; set; } = string.Empty;
    public string Email { get; set; } = string.Empty;
    public string PasswordHash { get; set; } = string.Empty;
    public string? Phone { get; set; }
    public string? Address { get; set; }
    public int? Age { get; set; }
    public string? Sex { get; set; }
    public string? Interests { get; set; }
    public string? BankAccountNumber { get; set; }
    public string? BankName { get; set; }
    public string UserType { get; set; } = "customer";

    public Seller? SellerProfile { get; set; }
}
