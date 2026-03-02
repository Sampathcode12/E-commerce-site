using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace ECommerceApi.Controllers;

[ApiController]
[Route("api/upload")] // explicit path so client can use /api/upload
public class UploadController : ControllerBase
{
    private readonly IWebHostEnvironment _env;
    private const string UploadSubDir = "uploads";
    private static readonly string[] AllowedExtensions = { ".jpg", ".jpeg", ".png", ".gif", ".webp" };
    private const int MaxFileSizeBytes = 5 * 1024 * 1024; // 5 MB

    public UploadController(IWebHostEnvironment env)
    {
        _env = env;
    }

    [HttpPost]
    [Authorize(Policy = "AdminOrSeller")]
    public async Task<ActionResult<UploadResponse>> Upload(IFormFileCollection files)
    {
        if (files == null || files.Count == 0)
            return BadRequest(new { message = "No files provided." });

        var webRoot = Path.Combine(_env.ContentRootPath, "wwwroot");
        var basePath = Path.Combine(webRoot, UploadSubDir);
        Directory.CreateDirectory(webRoot);
        Directory.CreateDirectory(basePath);

        var urls = new List<string>();
        foreach (var file in files)
        {
            var ext = Path.GetExtension(file.FileName)?.ToLowerInvariant();
            if (string.IsNullOrEmpty(ext) || !AllowedExtensions.Contains(ext))
                continue;
            if (file.Length > MaxFileSizeBytes)
                continue;

            var safeName = $"{Guid.NewGuid():N}{ext}";
            var fullPath = Path.Combine(basePath, safeName);
            await using (var stream = System.IO.File.Create(fullPath))
                await file.CopyToAsync(stream);

            urls.Add($"/{UploadSubDir}/{safeName}");
        }

        if (urls.Count == 0)
            return BadRequest(new { message = "No valid image files (allowed: jpg, png, gif, webp; max 5MB each)." });

        return Ok(new UploadResponse { Urls = urls });
    }
}

public class UploadResponse
{
    public List<string> Urls { get; set; } = new();
}
