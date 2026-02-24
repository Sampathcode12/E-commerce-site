# How to Add a Migration

**When you add a new migration to the code, the database is updated automatically the next time you run the backend** (F5 or `dotnet run`). The app applies any pending migrations on startup.

EF Core does **not** create migration *files* automatically when the app runs. You add those with the CLI or script below; then running the app applies them to the DB.

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

## 3. Add a new migration

From the **solution folder** (E-commerce-site), run:

```bash
dotnet ef migrations add UserAndAnotherTable --project backend
```

Or go into the backend folder first, then run:

```bash
cd backend
dotnet ef migrations add UserAndAnotherTable
```

## 4. If "dotnet ef" is not found

Install the EF Core global tool once:

```bash
dotnet tool install --global dotnet-ef
```

Close and reopen the terminal, then run the migration command again.

## 5. Update the database

After adding a migration, update the DB in either way:

- **Option A (recommended):** Run the backend — migrations apply automatically on startup.  
  From `backend`: `dotnet run`, or press F5 in Visual Studio.
- **Option B:** Apply from terminal without starting the API:  
  `dotnet ef database update --project backend`
