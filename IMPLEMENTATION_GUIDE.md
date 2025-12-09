# MassMeal SaaS - Implementation Guide

## Overview

This guide covers the comprehensive implementation of multi-language support, enhanced tenant management, standardized house rent module, and plan management system.

## Features Implemented

### 1. Multi-Language System (Bangla & English)

#### Files Created:

-   `lang/en/common.php` - Common English translations
-   `lang/bn/common.php` - Common Bangla translations
-   `lang/en/tenant.php` - Tenant module English translations
-   `lang/bn/tenant.php` - Tenant module Bangla translations
-   `lang/en/house_rent.php` - House rent English translations
-   `lang/bn/house_rent.php` - House rent Bangla translations
-   `lang/en/plan.php` - Plan management English translations
-   `lang/bn/plan.php` - Plan management Bangla translations
-   `app/Http/Middleware/SetLocale.php` - Middleware for language switching
-   `app/Http/Controllers/LanguageController.php` - Language controller
-   `resources/views/components/language-switcher.blade.php` - Language switcher component

#### Usage:

```php
// In Blade templates
{{ __('common.dashboard') }}
{{ __('tenant.tenant_management') }}
{{ __('house_rent.house_rent_management') }}
{{ __('plan.plan_management') }}
```

#### Language Switcher:

```blade
<x-language-switcher />
```

### 2. Plan Management System

#### Files Created:

-   `app/Models/Plan.php` - Plan model with features
-   `app/Http/Controllers/SuperAdmin/PlanController.php` - Full CRUD controller
-   `database/migrations/2025_12_09_000001_create_plans_table.php` - Plans table
-   `database/migrations/2025_12_09_000002_update_tenants_table_for_plans.php` - Enhanced tenants table
-   `database/seeders/PlanSeeder.php` - Default plans seeder

#### Plan Features:

-   **Free Plan**: 5 members, 100 MB storage, 30-day trial
-   **Basic Plan**: 15 members, 500 MB storage, ৳500/month
-   **Premium Plan**: 50 members, 2 GB storage, ৳1000/month
-   **Enterprise Plan**: 200 members, 10 GB storage, ৳2500/month

#### Routes:

```php
Route::resource('superadmin.plans', PlanController::class);
```

### 3. Enhanced Tenant Management

#### Files Updated:

-   `app/Models/Tenant.php` - Added plan relationship, subscription methods
-   `app/Http/Controllers/SuperAdmin/TenantController.php` - Full CRUD with plan management

#### New Features:

-   Plan assignment and tracking
-   Subscription status (trial, active, expired, cancelled)
-   Trial period management
-   Phone and address fields
-   Plan expiration tracking
-   Search and filter functionality

#### New Methods in Tenant Model:

```php
$tenant->isSubscriptionActive(); // Check if subscription is active
$tenant->isOnTrial(); // Check if on trial
$tenant->remainingTrialDays(); // Get remaining trial days
$tenant->planDetails; // Get plan relationship
```

#### Routes:

```php
Route::resource('superadmin.tenants', TenantController::class);
Route::post('superadmin.tenants/{tenant}/change-plan', 'changePlan');
```

### 4. Standardized House Rent Module

#### Files Updated:

-   `app/Models/HouseRent.php` - Enhanced with payment tracking
-   `app/Models/HouseRentMain.php` - Enhanced with payment details
-   `database/migrations/2025_12_09_000003_enhance_house_rent_tables.php` - New fields

#### New Fields:

**HouseRent:**

-   `paid_amount` - Amount paid by member
-   `due_amount` - Amount due
-   `payment_method` - Payment method (cash, bank, mobile)
-   `payment_date` - Date of payment

**HouseRentMain:**

-   `payment_method` - Payment method for landlord
-   `payment_date` - Date paid to landlord
-   `receipt_number` - Receipt number
-   `notes` - Additional notes

#### New Methods:

```php
$houseRent->getPaymentStatusAttribute(); // Returns: paid, partial, unpaid
```

## Installation Steps

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Plans

```bash
php artisan db:seed --class=PlanSeeder
```

### 3. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Configuration

### Language Configuration

The default language is set in `config/app.php`:

```php
'locale' => env('APP_LOCALE', 'en'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
```

Add to `.env`:

```
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## Views to Create/Update

### Super Admin Views Needed:

#### Plans:

-   `resources/views/superadmin/plans/index.blade.php` - List all plans
-   `resources/views/superadmin/plans/create.blade.php` - Create new plan
-   `resources/views/superadmin/plans/edit.blade.php` - Edit plan
-   `resources/views/superadmin/plans/show.blade.php` - View plan details

#### Tenants:

-   `resources/views/superadmin/tenants/index.blade.php` - List all tenants (update with filters)
-   `resources/views/superadmin/tenants/create.blade.php` - Create new tenant
-   `resources/views/superadmin/tenants/edit.blade.php` - Edit tenant
-   `resources/views/superadmin/tenants/show.blade.php` - View tenant details (update with plan info)

### Layout Updates:

Add language switcher to navigation:

```blade
<!-- In navigation.blade.php or header -->
<x-language-switcher />
```

## API Endpoints

### Plans Management

-   `GET /superadmin/plans` - List all plans
-   `GET /superadmin/plans/create` - Show create form
-   `POST /superadmin/plans` - Store new plan
-   `GET /superadmin/plans/{plan}` - Show plan details
-   `GET /superadmin/plans/{plan}/edit` - Show edit form
-   `PUT /superadmin/plans/{plan}` - Update plan
-   `DELETE /superadmin/plans/{plan}` - Delete plan

### Tenant Management

-   `GET /superadmin/tenants` - List all tenants (with search & filters)
-   `GET /superadmin/tenants/create` - Show create form
-   `POST /superadmin/tenants` - Store new tenant
-   `GET /superadmin/tenants/{tenant}` - Show tenant details
-   `GET /superadmin/tenants/{tenant}/edit` - Show edit form
-   `PUT /superadmin/tenants/{tenant}` - Update tenant
-   `DELETE /superadmin/tenants/{tenant}` - Delete tenant
-   `POST /superadmin/tenants/{tenant}/change-plan` - Change tenant plan

### Language

-   `POST /language/switch` - Switch language

## Database Schema

### Plans Table

```sql
- id
- name
- slug (unique)
- description
- price_monthly
- price_yearly
- max_members
- max_storage_mb
- features (JSON)
- trial_days
- is_active
- is_popular
- sort_order
- timestamps
```

### Tenants Table (Updated)

```sql
- id
- name
- phone
- address
- owner_user_id
- plan (legacy)
- plan_id (FK to plans)
- plan_started_at
- plan_expires_at
- status (active, suspended)
- subscription_status (trial, active, expired, cancelled)
- timestamps
```

### House Rents Table (Enhanced)

```sql
- id
- tenant_id
- member_id
- month
- house_rent
- wifi_bill
- current_bill
- gas_bill
- extra_bill
- extra_note
- total
- paid_amount (NEW)
- due_amount (NEW)
- status
- payment_method (NEW)
- payment_date (NEW)
- created_by
- timestamps
```

### House Rent Mains Table (Enhanced)

```sql
- id
- tenant_id
- month
- house_rent
- wifi_bill
- current_bill
- gas_bill
- extra_bill
- extra_note
- total
- assigned_to_members
- remaining_balance
- carry_forward
- status
- payment_method (NEW)
- payment_date (NEW)
- receipt_number (NEW)
- notes (NEW)
- created_by
- timestamps
```

## Usage Examples

### Creating a New Tenant with Plan

```php
$tenant = Tenant::create([
    'name' => 'ABC Mess',
    'phone' => '01712345678',
    'address' => 'Dhaka, Bangladesh',
    'plan_id' => 2, // Basic Plan
    'plan_started_at' => now(),
    'plan_expires_at' => now()->addDays(14),
    'status' => 'active',
    'subscription_status' => 'trial',
]);
```

### Checking Subscription Status

```php
if ($tenant->isSubscriptionActive()) {
    // Allow access
}

if ($tenant->isOnTrial()) {
    $daysLeft = $tenant->remainingTrialDays();
    // Show trial notification
}
```

### Using Translations

```blade
<h1>{{ __('tenant.tenant_management') }}</h1>
<button>{{ __('common.create') }}</button>
<span>{{ __('plan.current_plan') }}: {{ $tenant->planDetails->name }}</span>
```

### Recording House Rent Payment

```php
$houseRent->update([
    'paid_amount' => 5000,
    'due_amount' => 0,
    'payment_method' => 'bank',
    'payment_date' => now(),
    'status' => 'paid',
]);
```

## Security Considerations

1. **Permissions**: Ensure only super admins can access plan and tenant management
2. **Validation**: All inputs are validated in controllers
3. **Transactions**: Tenant creation uses database transactions
4. **Soft Deletes**: Consider implementing soft deletes for tenants

## Next Steps

1. Create the view files for plans and enhanced tenant management
2. Add language switcher to all layouts
3. Update existing views to use translation keys
4. Add more language files for other modules (meals, bazars, deposits, etc.)
5. Implement plan upgrade/downgrade workflow
6. Add payment gateway integration for plan subscriptions
7. Create dashboard widgets showing plan statistics
8. Add email notifications for trial expiration
9. Implement usage tracking (members count, storage usage)
10. Add plan feature restrictions middleware

## Testing

### Test Plan Management

```bash
# Create plans
php artisan db:seed --class=PlanSeeder

# Test plan CRUD operations
# Visit /superadmin/plans
```

### Test Language Switching

```bash
# Switch to Bangla
# Use language switcher in UI
# Verify translations appear correctly
```

### Test Tenant Management

```bash
# Create tenant with plan
# Verify trial period
# Test plan change
# Check subscription status
```

## Troubleshooting

### Language files not loading

```bash
php artisan config:clear
php artisan cache:clear
```

### Migrations failing

```bash
# Check if tables exist
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback

# Re-run migrations
php artisan migrate
```

### Plans not showing

```bash
# Seed plans
php artisan db:seed --class=PlanSeeder
```

## Support

For issues or questions, refer to:

-   Laravel Documentation: https://laravel.com/docs
-   Laravel Localization: https://laravel.com/docs/localization
-   Project README: README.md
