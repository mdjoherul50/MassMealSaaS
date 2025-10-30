<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // firstOrCreate ব্যবহার করলে ডুপ্লিকেট ইউজার তৈরি হবে না
        User::firstOrCreate(
            [
                'email' => 'superadmin@admin.com' // এই ইমেইলটি দিয়ে খুঁজবে
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin'), // 'password' হবে পাসওয়ার্ড
                'role' => 'super_admin',
                'tenant_id' => null // সুপার অ্যাডমিনের কোনো টেন্যান্ট নেই
            ]
        );
    }
}