using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using ECommerceApi.Data;
using ECommerceApi.DTOs;
using ECommerceApi.Models;
using ECommerceApi.Services;

namespace ECommerceApi.Controllers;

[ApiController]
[Route("api/[controller]")]
public class AuthController : ControllerBase
{
    private readonly AppDbContext _db;
    private readonly ITokenService _tokenService;

    public AuthController(AppDbContext db, ITokenService tokenService)
    {
        _db = db;
        _tokenService = tokenService;
    }

    [HttpPost("login")]
    public async Task<ActionResult<LoginResponse>> Login([FromBody] LoginRequest req)
    {
        if (string.IsNullOrWhiteSpace(req.Email) || string.IsNullOrWhiteSpace(req.Password))
            return BadRequest("Email and password are required.");

        // Hardcoded admin for testing
        if (req.Email == "admin@example.com" && req.Password == "adminpassword")
        {
            var adminToken = _tokenService.GenerateToken(new User
            {
                Id = 1,
                Email = req.Email,
                UserType = "admin"
            });
            return Ok(new LoginResponse
            {
                Token = adminToken,
                UserId = 1,
                Email = req.Email,
                UserType = "admin"
            });
        }

        var user = await _db.Users.FirstOrDefaultAsync(u => u.Email == req.Email);
        if (user == null || !BCrypt.Net.BCrypt.Verify(req.Password, user.PasswordHash))
            return Unauthorized("Invalid email or password.");

        var token = _tokenService.GenerateToken(user);
        return Ok(new LoginResponse
        {
            Token = token,
            UserId = user.Id,
            Email = user.Email,
            UserType = user.UserType
        });
    }

    [HttpPost("register")]
    public async Task<ActionResult<LoginResponse>> Register([FromBody] RegisterRequest req)
    {
        if (string.IsNullOrWhiteSpace(req.Email) || string.IsNullOrWhiteSpace(req.Password) ||
            string.IsNullOrWhiteSpace(req.FirstName) || string.IsNullOrWhiteSpace(req.LastName))
            return BadRequest("Required fields: FirstName, LastName, Email, Password.");

        if (await _db.Users.AnyAsync(u => u.Email == req.Email))
            return BadRequest("Email already exists.");

        var user = new User
        {
            FirstName = req.FirstName,
            LastName = req.LastName,
            Email = req.Email,
            PasswordHash = BCrypt.Net.BCrypt.HashPassword(req.Password),
            Phone = req.Phone,
            Address = req.Address,
            Age = req.Age,
            Sex = req.Sex,
            Interests = req.Interests,
            BankAccountNumber = req.BankAccountNumber,
            BankName = req.BankName,
            UserType = "customer"
        };

        _db.Users.Add(user);
        await _db.SaveChangesAsync();

        var token = _tokenService.GenerateToken(user);
        return Ok(new LoginResponse
        {
            Token = token,
            UserId = user.Id,
            Email = user.Email,
            UserType = user.UserType
        });
    }
}
