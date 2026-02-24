using Microsoft.EntityFrameworkCore;
using ECommerceApi.Models;

namespace ECommerceApi.Data;

public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

    public DbSet<User> Users => Set<User>();
    public DbSet<Product> Products => Set<Product>();
    public DbSet<Seller> Sellers => Set<Seller>();
    public DbSet<Sell> Sells => Set<Sell>();

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        modelBuilder.Entity<User>(e =>
        {
            e.ToTable("users");
            e.Property(u => u.FirstName).HasColumnName("first_name");
            e.Property(u => u.LastName).HasColumnName("last_name");
            e.Property(u => u.PasswordHash).HasColumnName("password");
            e.Property(u => u.UserType).HasColumnName("user_type");
            e.Property(u => u.BankAccountNumber).HasColumnName("bank_account_number");
            e.Property(u => u.BankName).HasColumnName("bank_name");
        });

        modelBuilder.Entity<Product>(e =>
        {
            e.ToTable("products");
            e.Property(p => p.ProductId).HasColumnName("product_ID");
            e.Property(p => p.ImagePath).HasColumnName("image_path");
            e.Property(p => p.SubCategory).HasColumnName("sub_category");
            e.Property(p => p.Price).HasPrecision(18, 2);
        });

        modelBuilder.Entity<Seller>(e =>
        {
            e.ToTable("seller");
            e.Property(s => s.SellerId).HasColumnName("seller_id");
            e.Property(s => s.SellerName).HasColumnName("seller_name");
            e.Property(s => s.PasswordHash).HasColumnName("password");
            e.Property(s => s.SellerEmail).HasColumnName("seller_email");
            e.Property(s => s.BusinessName).HasColumnName("business_name");
            e.Property(s => s.BusinessEmail).HasColumnName("business_email");
        });

        // One-to-one: Seller is dependent (has FK SellerId -> User.Id)
        modelBuilder.Entity<Seller>()
            .HasOne(s => s.User)
            .WithOne(u => u.SellerProfile)
            .HasForeignKey<Seller>(s => s.SellerId);

        modelBuilder.Entity<Sell>(e =>
        {
            e.ToTable("sells");
            e.Property(s => s.OrderId).HasColumnName("order_id");
            e.Property(s => s.UserId).HasColumnName("user_id");
            e.Property(s => s.ProductId).HasColumnName("product_id");
            e.Property(s => s.TotalPrice).HasColumnName("total_price").HasPrecision(18, 2);
            e.Property(s => s.PaymentMethod).HasColumnName("payment_method");
            e.Property(s => s.SellDate).HasColumnName("sell_date");
        });
    }
}
