@echo off
echo ========================================
echo MassMeal SaaS - Setup New Features
echo ========================================
echo.

echo Step 1: Running migrations...
php artisan migrate

echo.
echo Step 2: Seeding plans...
php artisan db:seed --class=PlanSeeder

echo.
echo Step 3: Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo New Features Added:
echo - Multi-language support (English and Bangla)
echo - Plan management system
echo - Enhanced tenant management
echo - Improved house rent module
echo.
echo Next Steps:
echo 1. Create view files for plans CRUD
echo 2. Create view files for enhanced tenant management
echo 3. Add language switcher to your layouts
echo 4. Test the new features
echo.
echo For detailed guide, see IMPLEMENTATION_GUIDE.md
echo.
pause
