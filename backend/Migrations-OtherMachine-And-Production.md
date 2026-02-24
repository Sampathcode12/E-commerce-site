# Migrations: Another Machine & Production (Live)

## On another machine (new PC / teammate)

You **do not** run "Add Migration" on the other machine. Migrations are already in the repo.

### 1. Get the code
- Clone the repo or copy the project to the new machine.

### 2. Restore and run
```bash
cd E-commerce-site
dotnet restore
cd backend
dotnet run
```
When the app starts, it applies all pending migrations (Program.cs runs `Migrate()`). The database and `__EFMigrationsHistory` are updated automatically.

### 3. Connection string on the new machine
- Set the correct database in **backend/appsettings.json** (or use environment variables / User Secrets) for that machine:
```json
"ConnectionStrings": {
  "DefaultConnection": "Data Source=THAT-MACHINE\\SQLEXPRESS;Initial Catalog=ecommerce;..."
}
```

### Summary (another machine)
| Action | Do you do it? |
|--------|----------------|
| Add migration (`dotnet ef migrations add`) | **No** – migrations are in the repo. |
| Apply migrations | **Yes** – automatically when you run the app (`dotnet run` or F5). |
| Set connection string | **Yes** – for that machine’s database. |

---

## In production / live

You **never** run "Add Migration" in production. You only **apply** existing migrations.

### Option 1: Let the app apply on startup (simplest)

- Deploy the app as usual.
- On first start (or after deploying new code with new migrations), the app runs `Migrate()` and updates the database and `__EFMigrationsHistory`.
- No extra step; just deploy and run.

Make sure the **production** connection string in appsettings (or env/secrets) points to the live database.

### Option 2: Apply migrations before starting the app (deployment script)

Some teams prefer to update the database in a separate step:

```bash
cd backend
dotnet ef database update --connection "YourProductionConnectionString"
# Then start the app (e.g. dotnet run or your host)
```

Use this in your deployment pipeline (e.g. Azure DevOps, GitHub Actions, or a deploy script).

### Option 3: Generate SQL and run it manually (strict environments)

If your live DB is updated only by running scripts:

```bash
cd backend
dotnet ef migrations script -o migration.sql
```

- Run `migration.sql` (or the generated script) on the production database with your DB tool.
- Then deploy and start the app.

---

## Quick reference

| Where | Add migration? | Apply migrations? |
|-------|----------------|-------------------|
| **Your dev machine** (after changing model) | Yes – `dotnet ef migrations add` or AddMigration.bat | Yes – when you run the app (F5) |
| **Another dev machine** | No | Yes – when they run the app |
| **Production / live** | **No** | Yes – app applies on startup, or run `database update` / script |

---

## Production connection string

Do not put the real production password in appsettings.json in the repo. Use one of:

- **Environment variable:**  
  `ConnectionStrings__DefaultConnection=Data Source=...;...`
- **User Secrets** (dev): `dotnet user-secrets set "ConnectionStrings:DefaultConnection" "..."`
- **Azure Key Vault / AWS Secrets Manager** (production)
- **appsettings.Production.json** on the server (and keep that file out of the repo)
