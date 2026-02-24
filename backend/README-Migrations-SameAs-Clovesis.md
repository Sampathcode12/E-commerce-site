# DB update & add migration (same pattern as Clovesis/ApexflowERP)

This project uses the **same approach** as your Clovesis solution (`C:\Users\Lahiru\Documents\clovesis\New`).

---

## 1. DB update (apply migrations)

**When:** Every time the app runs (F5 or `dotnet run`).

**What:** Pending migrations are applied; the database and `__EFMigrationsHistory` are updated.

**Clovesis (ApexflowERP.Api):**
```csharp
using var scope = app.Services.CreateScope();
var context = services.GetRequiredService<AppDbContext>();
await context.Database.MigrateAsync();
```

**This project (E-commerce backend):**
```csharp
using (var scope = app.Services.CreateScope())
{
    var context = scope.ServiceProvider.GetRequiredService<AppDbContext>();
    await context.Database.MigrateAsync();
}
```

So: **DB update = automatic on startup**, in both solutions.

---

## 2. Add migration (manual)

**When:** After you change the model (entities or `AppDbContext`).

**Clovesis (multi-project – DbContext in Infrastructure, startup is Api):**
```bash
cd C:\Users\Lahiru\Documents\clovesis\New
dotnet ef migrations add YourMigrationName --project ApexflowERP.Infrastructure --startup-project ApexflowERP.Api
```
Apply to DB: run the Api (F5) or:
```bash
dotnet ef database update --project ApexflowERP.Infrastructure --startup-project ApexflowERP.Api
```

**This project (single project – DbContext and migrations in backend):**
```bash
cd C:\xampp\htdocs\E-commerce-site
dotnet ef migrations add YourMigrationName --project backend
```
Apply to DB: run the backend (F5) or it auto-applies on startup.

---

## Summary

| Action        | Clovesis/New                    | E-commerce backend          |
|---------------|----------------------------------|-----------------------------|
| DB update     | On startup (`MigrateAsync`)      | On startup (`MigrateAsync`) |
| Add migration | Manual: `dotnet ef ... --project Infrastructure --startup-project Api` | Manual: `dotnet ef migrations add Name --project backend` |

Same idea in both: **add migration manually**, **DB update on run**.
