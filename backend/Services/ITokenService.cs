using ECommerceApi.Models;

namespace ECommerceApi.Services;

public interface ITokenService
{
    string GenerateToken(User user);
}
