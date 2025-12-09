# Quick Start Guide - MassMeal SaaS New Features

## 🚀 Get Started in 5 Minutes

### Step 1: Run Setup Script

```bash
setup-new-features.bat
```

OR manually:

```bash
php artisan migrate
php artisan db:seed --class=PlanSeeder
php artisan config:clear
php artisan cache:clear
```

### Step 2: Test Language Switching

Add to any layout file (e.g., `navigation.blade.php`):

```blade
<x-language-switcher />
```

### Step 3: Access New Features

**Plan Management:**

-   Visit: `/superadmin/plans`
-   View all subscription plans
-   Create, edit, or delete plans

**Enhanced Tenant Management:**

-   Visit: `/superadmin/tenants`
-   See plan information, subscription status
-   Use search and filters
-   Create tenants with automatic owner user creation

---

## 📝 Quick Usage Examples

### Using Translations in Views

```blade
<!-- Common translations -->
<h1>{{ __('common.dashboard') }}</h1>
<button>{{ __('common.save') }}</button>
<span>{{ __('common.status') }}</span>

<!-- Tenant translations -->
<h2>{{ __('tenant.tenant_management') }}</h2>
<p>{{ __('tenant.add_tenant') }}</p>

<!-- House rent translations -->
<h3>{{ __('house_rent.house_rent_management') }}</h3>
<label>{{ __('house_rent.wifi_bill') }}</label>

<!-- Plan translations -->
<h4>{{ __('plan.plan_management') }}</h4>
<span>{{ __('plan.current_plan') }}</span>
```

### Checking Tenant Subscription

```blade
@if($tenant->isSubscriptionActive())
    <span class="badge badge-success">Active</span>
@else
    <span class="badge badge-danger">Inactive</span>
@endif

@if($tenant->isOnTrial())
    <div class="alert alert-info">
        Trial expires in {{ $tenant->remainingTrialDays() }} days
    </div>
@endif
```

### Displaying Plan Information

```blade
<div class="plan-info">
    <h3>{{ $tenant->planDetails->name }}</h3>
    <p>৳{{ number_format($tenant->planDetails->price_monthly, 2) }}/month</p>
    <p>Max Members: {{ $tenant->planDetails->max_members }}</p>
    <p>Storage: {{ $tenant->planDetails->max_storage_mb }} MB</p>
</div>
```

---

## 🎯 Key Routes

### Super Admin Routes

```
GET  /superadmin/plans              - List all plans
GET  /superadmin/plans/create       - Create plan form
POST /superadmin/plans              - Store new plan
GET  /superadmin/plans/{id}         - View plan
GET  /superadmin/plans/{id}/edit    - Edit plan form
PUT  /superadmin/plans/{id}         - Update plan
DELETE /superadmin/plans/{id}       - Delete plan

GET  /superadmin/tenants            - List tenants (with filters)
GET  /superadmin/tenants/create     - Create tenant form
POST /superadmin/tenants            - Store new tenant
GET  /superadmin/tenants/{id}       - View tenant
GET  /superadmin/tenants/{id}/edit  - Edit tenant form
PUT  /superadmin/tenants/{id}       - Update tenant
POST /superadmin/tenants/{id}/change-plan - Change plan

POST /language/switch               - Switch language
```

---

## 🗂️ Available Plans

| Plan       | Monthly | Yearly  | Members | Storage | Trial   |
| ---------- | ------- | ------- | ------- | ------- | ------- |
| Free       | ৳0      | ৳0      | 5       | 100 MB  | 30 days |
| Basic      | ৳500    | ৳5,000  | 15      | 500 MB  | 14 days |
| Premium    | ৳1,000  | ৳10,000 | 50      | 2 GB    | 14 days |
| Enterprise | ৳2,500  | ৳25,000 | 200     | 10 GB   | 30 days |

---

## 🌐 Supported Languages

-   **English (en)** - Default
-   **Bangla (bn)** - বাংলা

Switch using the language switcher component or:

```php
Session::put('locale', 'bn'); // Switch to Bangla
Session::put('locale', 'en'); // Switch to English
```

---

## 📊 New Database Fields

### Tenants Table

-   `plan_id` - Plan reference
-   `plan_started_at` - Plan start date
-   `plan_expires_at` - Plan expiry date
-   `phone` - Contact phone
-   `address` - Physical address
-   `subscription_status` - trial/active/expired/cancelled

### House Rents Table

-   `paid_amount` - Amount paid
-   `due_amount` - Amount due
-   `payment_method` - Payment method
-   `payment_date` - Payment date

### House Rent Mains Table

-   `payment_method` - Payment method to landlord
-   `payment_date` - Date paid to landlord
-   `receipt_number` - Receipt number
-   `notes` - Additional notes

---

## ✅ Verification Checklist

After setup, verify:

-   [ ] Migrations ran successfully
-   [ ] 4 plans are seeded in database
-   [ ] Language switcher appears in UI
-   [ ] Can switch between English and Bangla
-   [ ] Can access `/superadmin/plans`
-   [ ] Can access `/superadmin/tenants`
-   [ ] Tenant list shows plan information
-   [ ] Can create new tenant with plan
-   [ ] Subscription status displays correctly
-   [ ] Trial days calculate correctly

---

## 🔧 Troubleshooting

### Language not switching?

```bash
php artisan config:clear
php artisan cache:clear
```

### Plans not showing?

```bash
php artisan db:seed --class=PlanSeeder
```

### Routes not working?

```bash
php artisan route:clear
php artisan route:cache
```

### Views not updating?

```bash
php artisan view:clear
```

---

## 📚 Documentation

-   **Quick Start:** `QUICK_START.md` (this file)
-   **Feature Summary:** `FEATURE_SUMMARY.md`
-   **Full Guide:** `IMPLEMENTATION_GUIDE.md`

---

## 🎉 You're Ready!

All core features are implemented and ready to use. Start by:

1. Running the setup script
2. Accessing `/superadmin/plans` to see plans
3. Accessing `/superadmin/tenants` to manage tenants
4. Testing language switching
5. Creating additional views as needed

**Happy coding! 🚀**
