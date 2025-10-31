<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // পুরানো ডেটা থাকলে পরিষ্কার করুন
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- সব পারমিশন তৈরি করুন ---

        // Member Permissions
        Permission::create(['name' => 'View Members', 'slug' => 'members.view']);
        Permission::create(['name' => 'Create Members', 'slug' => 'members.create']);
        Permission::create(['name' => 'Edit Members', 'slug' => 'members.edit']);
        Permission::create(['name' => 'Delete Members', 'slug' => 'members.delete']);

        // Meal Permissions
        Permission::create(['name' => 'View Meals', 'slug' => 'meals.view']);
        Permission::create(['name' => 'Manage Meals', 'slug' => 'meals.manage']);

        // Bazar Permissions
        Permission::create(['name' => 'View Bazar', 'slug' => 'bazars.view']);
        Permission::create(['name' => 'Manage Bazar', 'slug' => 'bazars.manage']);

        // Deposit Permissions
        Permission::create(['name' => 'View Deposits', 'slug' => 'deposits.view']);
        Permission::create(['name' => 'Manage Deposits', 'slug' => 'deposits.manage']);

        // Report Permissions
        Permission::create(['name' => 'View Reports', 'slug' => 'reports.view']);

        // Super Admin Permissions
        Permission::create(['name' => 'Manage Tenants', 'slug' => 'tenants.manage']);
        Permission::create(['name' => 'Manage Roles', 'slug' => 'roles.manage']);


        // --- রোল তৈরি করুন ---

        // Super Admin Role (সব পারমিশন পাবে)
        $superAdminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);

        // Mess Admin Role
        $messAdminRole = Role::create(['name' => 'Mess Admin', 'slug' => 'mess-admin']);
        $messAdminRole->permissions()->sync(
            Permission::whereIn('slug', [
                'members.view', 'members.create', 'members.edit', 'members.delete',
                'meals.view', 'meals.manage',
                'bazars.view', 'bazars.manage',
                'deposits.view', 'deposits.manage',
                'reports.view'
            ])->pluck('id')
        );

        // Bazarman Role (SRS অনুযায়ী)
        $bazarmanRole = Role::create(['name' => 'Bazarman', 'slug' => 'bazarman']);
        $bazarmanRole->permissions()->sync(
            Permission::whereIn('slug', [
                'meals.view', 'meals.manage', // মিল এন্ট্রি করতে পারবে
                'bazars.view', 'bazars.manage' // বাজার এন্ট্রি করতে পারবে
            ])->pluck('id')
        );

        // Member Role (Optional - যদি সদস্যরা লগইন করে)
        $memberRole = Role::create(['name' => 'Member', 'slug' => 'member']);
        $memberRole->permissions()->sync(
             Permission::whereIn('slug', ['reports.view'])->pluck('id') // শুধু রিপোর্ট দেখতে পারবে
        );

        // --- ডিফল্ট Super Admin ইউজার তৈরি করুন ---
        User::firstOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'tenant_id' => null
            ]
        );
    }
}
