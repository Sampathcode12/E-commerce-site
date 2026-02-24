# E-Commerce — React + C# Backend

This document describes the **new** stack: **React** frontend and **C# ASP.NET Core** backend, rebuilt from the original PHP project.

## Project structure

- **`backend/`** — ASP.NET Core 8 Web API (C#)
  - JWT auth, MySQL (same DB as before: `ecommerce`)
  - Endpoints: auth (login/register), products, orders
- **`client/`** — React 18 + Vite
  - React Router, auth context, pages: Home, Login, Signup, Product detail, Checkout, Account, Orders

## Prerequisites

- **.NET 8 SDK** — [Download](https://dotnet.microsoft.com/download)
- **Node.js 18+** and npm
- **MySQL** — existing `ecommerce` database (from the PHP version)

## Database

Set the **connection string** in `backend/appsettings.json`:

```json
"ConnectionStrings": {
  "DefaultConnection": "Server=localhost;Database=ecommerce;User=root;Password=YOUR_PASSWORD;"
}
```

### Migrations: create DB and update tables

When you run the backend, it **applies EF Core migrations on startup**:

- **If the database does not exist:** the database is created, then all tables are created and the migration is recorded in `__EFMigrationsHistory`.
- **If the database already exists and is empty:** same as above.
- **If the database already has tables** (e.g. from the PHP app): you must **baseline** it once so EF does not try to create tables again (see below). After that, only **new** migrations run, so tables are updated and history is maintained.

Migration history is stored in the `__EFMigrationsHistory` table. All schema changes should be done via migrations so history stays correct.

### Baseline an existing database (PHP-created or other)

If you already have the `ecommerce` database and tables (e.g. from the old PHP site), run this **once** before starting the API:

1. Open `backend/Scripts/BaselineExistingDatabase.sql`.
2. Run it against your `ecommerce` database (e.g. in MySQL Workbench or `mysql -u root -p ecommerce < Scripts/BaselineExistingDatabase.sql`).

This creates `__EFMigrationsHistory` and marks the initial migration as applied. When you start the backend, `Migrate()` will skip the initial migration and only apply any **new** migrations you add later.

### Adding new migrations later

After changing entities or `AppDbContext`, create a new migration and apply it:

```bash
cd backend
dotnet ef migrations add YourMigrationName
dotnet run
```

`dotnet run` applies pending migrations on startup. To apply migrations without running the API: `dotnet ef database update`.

## Run the backend (C#)

```bash
cd backend
dotnet restore
dotnet run
```

API runs at **http://localhost:5000**.  
Swagger/OpenAPI is not included; use Postman or the React app to call endpoints.

## Run the frontend (React)

```bash
cd client
npm install
npm run dev
```

App runs at **http://localhost:5173**.

Optional: create `client/.env` and set:

```env
VITE_API_URL=http://localhost:5000/api
```

If not set, the client uses `http://localhost:5000/api` by default (see `client/src/api/client.js`).

## API overview

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST   | `/api/auth/login` | No | Login (email, password). Returns JWT. |
| POST   | `/api/auth/register` | No | Register customer. Returns JWT. |
| GET    | `/api/products` | No | List all products. |
| GET    | `/api/products/{id}` | No | Get product by id. |
| POST   | `/api/products` | Yes (seller/admin) | Create product. |
| GET    | `/api/orders` | Yes | Current user's orders. |
| POST   | `/api/orders` | Yes | Place order (productId, quantity, paymentMethod). |

**Auth:** Send header `Authorization: Bearer <token>` for protected routes.  
Token is stored in `localStorage` by the React app after login/register.

## Test login

- **Admin (hardcoded):** `admin@example.com` / `adminpassword`
- **Customer:** Register via Sign up, then sign in.

## Build for production

**Backend:**

```bash
cd backend
dotnet publish -c Release -o ./publish
```

**Frontend:**

```bash
cd client
npm run build
```

Serve the `client/dist` folder with any static host; point it to your deployed API URL via `VITE_API_URL` when you built.

## Original PHP app

The original PHP site remains under the `page/` folder and is unchanged. This React + C# setup is a separate, full rebuild that uses the same MySQL database.
