-- Run this script ONCE on an EXISTING database so that EF Core treats it
-- as already having the initial migration applied. Creates the migration
-- history table and marks "InitialCreate" as applied.
-- After this, when you run the backend, Migrate() will only apply NEW migrations.

-- SQL Server: create migration history table if it does not exist
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = '__EFMigrationsHistory')
BEGIN
    CREATE TABLE [dbo].[__EFMigrationsHistory] (
        [MigrationId] nvarchar(150) NOT NULL,
        [ProductVersion] nvarchar(32) NOT NULL,
        CONSTRAINT [PK___EFMigrationsHistory] PRIMARY KEY ([MigrationId])
    );
END
GO

-- Mark the initial migration as already applied
IF NOT EXISTS (SELECT 1 FROM [dbo].[__EFMigrationsHistory] WHERE [MigrationId] = N'20250224000000_InitialCreate')
    INSERT INTO [dbo].[__EFMigrationsHistory] ([MigrationId], [ProductVersion])
    VALUES (N'20250224000000_InitialCreate', N'8.0.0');
GO
