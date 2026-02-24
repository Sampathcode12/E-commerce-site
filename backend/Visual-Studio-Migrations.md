# Add Migration from Visual Studio

You can do everything from Visual Studio without opening a separate PowerShell window.

---

## Option 1: Terminal inside Visual Studio (recommended)

1. In Visual Studio, go to **View → Terminal** (or press **Ctrl + `**).
2. In the terminal, the folder is usually the solution folder. Run:
   ```bash
   dotnet ef migrations add AutoUpdate --project backend
   ```
3. If you get **"dotnet ef" is not recognized**, install the tool once in that same terminal:
   ```bash
   dotnet tool install --global dotnet-ef
   ```
   Then run the migration command again.
4. Press **F5** (or **Run**) to start the backend. Migrations apply automatically on startup.

---

## Option 2: Add a menu item (External Tool)

1. In Visual Studio: **Tools → External Tools**.
2. Click **Add**.
3. Set:
   - **Title:** `Add EF Migration`
   - **Command:** `C:\xampp\htdocs\E-commerce-site\backend\AddMigration.bat`
   - **Initial directory:** `C:\xampp\htdocs\E-commerce-site`
4. Click **OK**.
5. From now on, use **Tools → Add EF Migration** to add a migration. Then press **F5** to run the backend.

---

## Option 3: Double-click the batch file

1. In **Solution Explorer**, go to the **backend** folder.
2. Open the folder in File Explorer (right‑click **backend → Open Folder in File Explorer**).
3. Double‑click **AddMigration.bat**.
4. Then run the backend from Visual Studio (**F5**).

---

## Applying migrations (no extra step)

When you run the backend from Visual Studio (**F5**), the app applies any pending migrations on startup. You only need to **add** a migration when you change your model (e.g. **Models/*.cs** or **AppDbContext**).
