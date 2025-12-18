<?php

namespace Database\Seeders;

use App\Models\Bazar;
use App\Models\Deposit;
use App\Models\HouseRent;
use App\Models\HouseRentMain;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('DemoDataSeeder is disabled in production.');
            return;
        }

        DB::transaction(function () {
            $plan = Plan::where('slug', 'basic')->first() ?? Plan::orderBy('sort_order')->first();

            $tenant = Tenant::firstOrCreate(
                ['name' => 'Demo Mess'],
                [
                    'owner_user_id' => null,
                    'plan' => $plan?->slug ?? 'free',
                    'plan_id' => $plan?->id,
                    'plan_started_at' => now()->toDateString(),
                    'plan_expires_at' => now()->addDays(14)->toDateString(),
                    'phone' => '01700000000',
                    'address' => 'Demo Address',
                    'status' => 'active',
                    'subscription_status' => 'trial',
                ]
            );

            $messAdminRole = Role::where('slug', 'mess-admin')->first();
            $bazarmanRole = Role::where('slug', 'bazarman')->first();

            $messAdmin = User::firstOrCreate(
                ['email' => 'messadmin@demo.local'],
                [
                    'name' => 'Demo Mess Admin',
                    'password' => Hash::make('password'),
                    'role_id' => $messAdminRole?->id,
                    'tenant_id' => $tenant->id,
                ]
            );

            $bazarman = User::firstOrCreate(
                ['email' => 'bazarman@demo.local'],
                [
                    'name' => 'Demo Bazarman',
                    'password' => Hash::make('password'),
                    'role_id' => $bazarmanRole?->id,
                    'tenant_id' => $tenant->id,
                ]
            );

            if (!$tenant->owner_user_id) {
                $tenant->owner_user_id = $messAdmin->id;
                $tenant->save();
            }

            DB::table('meals')->where('tenant_id', $tenant->id)->delete();
            DB::table('bazars')->where('tenant_id', $tenant->id)->delete();
            DB::table('deposits')->where('tenant_id', $tenant->id)->delete();
            DB::table('house_rents')->where('tenant_id', $tenant->id)->delete();
            DB::table('house_rent_mains')->where('tenant_id', $tenant->id)->delete();
            DB::table('members')->where('tenant_id', $tenant->id)->delete();

            $members = collect();
            for ($i = 1; $i <= 20; $i++) {
                $members->push(Member::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Member ' . $i,
                    'reg_id' => 'REG-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                    'phone' => '017' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'email' => 'member' . $i . '@demo.local',
                    'join_date' => now()->subDays(60)->toDateString(),
                ]));
            }

            $startDate = now()->subDays(29)->startOfDay();
            for ($d = 0; $d < 30; $d++) {
                $date = $startDate->copy()->addDays($d)->toDateString();

                foreach ($members as $member) {
                    $breakfast = random_int(0, 1);
                    $lunch = random_int(0, 1);
                    $dinner = random_int(0, 1);

                    if (($breakfast + $lunch + $dinner) === 0) {
                        $lunch = 1;
                    }

                    DB::table('meals')->insert([
                        'tenant_id' => $tenant->id,
                        'member_id' => $member->id,
                        'date' => $date,
                        'breakfast' => $breakfast,
                        'lunch' => $lunch,
                        'dinner' => $dinner,
                        'created_by' => $messAdmin->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            for ($i = 0; $i < 15; $i++) {
                $date = now()->subDays(random_int(0, 29))->toDateString();
                $total = random_int(500, 2000);

                Bazar::create([
                    'tenant_id' => $tenant->id,
                    'date' => $date,
                    'buyer_id' => $bazarman->id,
                    'description' => 'Demo bazar #' . ($i + 1),
                    'total_amount' => $total,
                    'items' => [
                        ['name' => 'Rice', 'qty' => random_int(1, 5), 'price' => (int) round($total * 0.4)],
                        ['name' => 'Veg', 'qty' => random_int(2, 8), 'price' => (int) round($total * 0.3)],
                        ['name' => 'Fish/Meat', 'qty' => random_int(1, 4), 'price' => (int) round($total * 0.3)],
                    ],
                ]);
            }

            foreach ($members as $member) {
                for ($k = 0; $k < 2; $k++) {
                    Deposit::create([
                        'tenant_id' => $tenant->id,
                        'member_id' => $member->id,
                        'amount' => random_int(500, 2000),
                        'method' => 'cash',
                        'reference' => null,
                        'date' => now()->subDays(random_int(0, 29))->toDateString(),
                    ]);
                }
            }

            $month = now()->format('Y-m');

            $main = HouseRentMain::create([
                'tenant_id' => $tenant->id,
                'month' => $month,
                'house_rent' => 20000,
                'wifi_bill' => 1000,
                'current_bill' => 1500,
                'gas_bill' => 800,
                'extra_bill' => 700,
                'extra_note' => 'Demo bills',
                'assigned_to_members' => 0,
                'carry_forward' => 0,
                'status' => 'pending',
                'created_by' => $messAdmin->id,
            ]);

            $perMember = round(((float) $main->total) / max(1, $members->count()), 2);

            foreach ($members as $member) {
                $total = $perMember;

                HouseRent::create([
                    'tenant_id' => $tenant->id,
                    'member_id' => $member->id,
                    'month' => $month,
                    'house_rent' => $total,
                    'wifi_bill' => 0,
                    'current_bill' => 0,
                    'gas_bill' => 0,
                    'extra_bill' => 0,
                    'extra_note' => null,
                    'total' => $total,
                    'status' => 'pending',
                    'created_by' => $messAdmin->id,
                ]);
            }
        });
    }
}
