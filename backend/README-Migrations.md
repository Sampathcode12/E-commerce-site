# Migrations: C# model → tables and migration history

- **Add migration:** Generates a migration `.cs` file from your C# model (DbContext/entities). Run when you change the model.
- **Update database:** Applies pending migrations to the DB and updates the **`__EFMigrationsHistory`** table so history is maintained.
- **On backend run:** The app applies any pending migrations on startup, so the DB and migration history stay in sync.

## Option 1: Use the script (easiest)

From the project root **E-commerce-site**:

- **When you changed the model** (added/edited Models or DbContext):  
  Double-click **AddMigrationAndRun.ps1** or run:
  ```powershell
  powershell -ExecutionPolicy Bypass -File AddMigrationAndRun.ps1
  ```
  This adds a migration named `AutoUpdate` and then starts the backend (migrations apply on startup).

- **When you didn’t change the model**:  
  Double-click **RunBackend.ps1** or run it. It only starts the backend and applies existing migrations.

## Option 2: Use the dotnet ef CLI

Use a normal terminal (Command Prompt or PowerShell), **not** the Package Manager Console.

## 1. Open a terminal

- In Visual Studio: **View → Terminal**, or
- Open **Command Prompt** or **PowerShell** from Windows.

## 2. Go to the solution folder

```bash
cd C:\xampp\htdocs\E-commerce-site
```

## 3. Add a new migration (creates .cs from C# model)

From the **solution folder** (E-commerce-site), run:

```bash
dotnet ef migrations add InitialCreate --project backend
```

(Use any name instead of `InitialCreate`, e.g. `AddUserTable`.) Or from the backend folder:

```bash
cd backend
dotnet ef migrations add InitialCreate
```

This creates/updates files under `backend/Migrations/` and the model snapshot. No database change yet.

## 4. If "dotnet ef" is not found

Install the EF Core global tool once:

```bash
dotnet tool install --global dotnet-ef
```

Close and reopen the terminal, then run the migration command again.

## 5. Update the database (apply migrations and migration history)

After adding a migration, apply it so the C# model becomes tables and **`__EFMigrationsHistory`** is updated:

- **Option A (recommended):** Run the backend — migrations apply on startup.  
  From `backend`: `dotnet run`, or press F5 in Visual Studio.
- **Option B:** Apply from terminal without starting the API:  
  ```bash
  dotnet ef database update --project backend
  ```

Both options run the pending migration(s), create/alter tables, and insert a row into `__EFMigrationsHistory` for each applied migration so history is maintained.
