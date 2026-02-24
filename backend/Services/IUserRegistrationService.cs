using ECommerceApi.DTOs;
using ECommerceApi.Models;

namespace ECommerceApi.Services;

public interface IUserRegistrationService
{
    /// <summary>
    /// Registers a new user from the registration form DTO.
    /// Saves to the users table (created by migration).
    /// </summary>
    Task<User> RegisterAsync(UserRegistrationRequest request, CancellationToken cancellationToken = default);
}
