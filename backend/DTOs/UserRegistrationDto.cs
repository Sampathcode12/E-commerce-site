using System.ComponentModel.DataAnnotations;

namespace ECommerceApi.DTOs;

/// <summary>
/// DTO for the user registration (signup) form.
/// Maps to the users table created by the InitialCreate migration.
/// </summary>
public class UserRegistrationRequest
{
    [Required(ErrorMessage = "First name is required")]
    [StringLength(255)]
    public string FirstName { get; set; } = string.Empty;

    [Required(ErrorMessage = "Last name is required")]
    [StringLength(255)]
    public string LastName { get; set; } = string.Empty;

    [Required(ErrorMessage = "Email is required")]
    [EmailAddress(ErrorMessage = "Invalid email format")]
    [StringLength(255)]
    public string Email { get; set; } = string.Empty;

    [Required(ErrorMessage = "Password is required")]
    [StringLength(255, MinimumLength = 6, ErrorMessage = "Password must be at least 6 characters")]
    public string Password { get; set; } = string.Empty;

    [StringLength(255)]
    public string? Phone { get; set; }

    public string? Address { get; set; }

    [Range(1, 150, ErrorMessage = "Age must be between 1 and 150")]
    public int? Age { get; set; }

    [StringLength(255)]
    public string? Sex { get; set; }

    public string? Interests { get; set; }

    [StringLength(255)]
    public string? BankAccountNumber { get; set; }

    [StringLength(255)]
    public string? BankName { get; set; }
}

/// <summary>
/// Response after successful user registration.
/// </summary>
public class UserRegistrationResponse
{
    public string Token { get; set; } = string.Empty;
    public int UserId { get; set; }
    public string Email { get; set; } = string.Empty;
    public string UserType { get; set; } = "customer";
}
