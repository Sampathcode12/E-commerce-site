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
    private readonly IUserRegistrationService _userRegistrationService;

    public AuthController(AppDbContext db, ITokenService tokenService, IUserRegistrationService userRegistrationService)
    {
        _db = db;
        _tokenService = tokenService;
        _userRegistrationService = userRegistrationService;
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

    /// <summary>
    /// User registration (signup). Uses C# DTOs and UserRegistrationService; saves to users table.
    /// </summary>
    [HttpPost("register")]
    public async Task<ActionResult<UserRegistrationResponse>> Register([FromBody] UserRegistrationRequest req)
    {
        if (req == null)
            return BadRequest(new { message = "Request body is required." });
        if (string.IsNullOrWhiteSpace(req.FirstName))
            return BadRequest(new { message = "First name is required." });
        if (string.IsNullOrWhiteSpace(req.LastName))
            return BadRequest(new { message = "Last name is required." });
        if (string.IsNullOrWhiteSpace(req.Email))
            return BadRequest(new { message = "Email is required." });
        if (string.IsNullOrWhiteSpace(req.Password))
            return BadRequest(new { message = "Password is required." });
        if (req.Password.Length < 6)
            return BadRequest(new { message = "Password must be at least 6 characters." });

        try
        {
            var user = await _userRegistrationService.RegisterAsync(req);
            var token = _tokenService.GenerateToken(user);
            return Ok(new UserRegistrationResponse
            {
                Token = token,
                UserId = user.Id,
                Email = user.Email,
                UserType = user.UserType
            });
        }
        catch (InvalidOperationException ex) when (ex.Message == "Email already exists.")
        {
            return BadRequest(new { message = ex.Message });
        }
    }
}
