@echo off
REM Run from Visual Studio: Tools > External Tools > Add this batch file.
REM Or double-click from solution folder.
cd /d "%~dp0"
cd ..
echo Adding EF Core migration...
dotnet ef migrations add AutoUpdate --project backend
if errorlevel 1 (
    echo.
    echo If 'dotnet ef' not found, run once in terminal: dotnet tool install --global dotnet-ef
    pause
    exit /b 1
)
echo.
echo Migration added. Run the backend (F5) to apply it.
pause
