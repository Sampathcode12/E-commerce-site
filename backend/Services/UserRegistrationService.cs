using Microsoft.EntityFrameworkCore;
using ECommerceApi.Data;
using ECommerceApi.DTOs;
using ECommerceApi.Models;

namespace ECommerceApi.Services;

public class UserRegistrationService : IUserRegistrationService
{
    private readonly AppDbContext _db;

    public UserRegistrationService(AppDbContext db)
    {
        _db = db;
    }

    public async Task<User> RegisterAsync(UserRegistrationRequest request, CancellationToken cancellationToken = default)
    {
        if (await _db.Users.AnyAsync(u => u.Email == request.Email, cancellationToken))
            throw new InvalidOperationException("Email already exists.");

        var user = new User
        {
            FirstName = request.FirstName,
            LastName = request.LastName,
            Email = request.Email.Trim(),
            PasswordHash = BCrypt.Net.BCrypt.HashPassword(request.Password),
            Phone = string.IsNullOrWhiteSpace(request.Phone) ? null : request.Phone.Trim(),
            Address = string.IsNullOrWhiteSpace(request.Address) ? null : request.Address.Trim(),
            Age = request.Age,
            Sex = string.IsNullOrWhiteSpace(request.Sex) ? null : request.Sex.Trim(),
            Interests = string.IsNullOrWhiteSpace(request.Interests) ? null : request.Interests.Trim(),
            BankAccountNumber = string.IsNullOrWhiteSpace(request.BankAccountNumber) ? null : request.BankAccountNumber.Trim(),
            BankName = string.IsNullOrWhiteSpace(request.BankName) ? null : request.BankName.Trim(),
            UserType = "customer"
        };

        _db.Users.Add(user);
        await _db.SaveChangesAsync(cancellationToken);
        return user;
    }
}
