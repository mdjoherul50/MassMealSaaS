<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'description' => 'Perfect for small groups starting out',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'max_members' => 5,
                'max_storage_mb' => 100,
                'features' => [
                    'Up to 5 members',
                    'Basic meal tracking',
                    'Basic reports',
                    '100 MB storage',
                ],
                'trial_days' => 30,
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'description' => 'Great for small to medium groups',
                'price_monthly' => 500,
                'price_yearly' => 5000,
                'max_members' => 15,
                'max_storage_mb' => 500,
                'features' => [
                    'Up to 15 members',
                    'Advanced meal tracking',
                    'House rent management',
                    'Detailed reports',
                    '500 MB storage',
                    'Email support',
                ],
                'trial_days' => 14,
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium Plan',
                'slug' => 'premium',
                'description' => 'Best for large groups and hostels',
                'price_monthly' => 1000,
                'price_yearly' => 10000,
                'max_members' => 50,
                'max_storage_mb' => 2000,
                'features' => [
                    'Up to 50 members',
                    'All Basic features',
                    'Advanced analytics',
                    'Custom reports',
                    'Priority support',
                    '2 GB storage',
                    'Data export',
                ],
                'trial_days' => 14,
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise Plan',
                'slug' => 'enterprise',
                'description' => 'For organizations with multiple locations',
                'price_monthly' => 2500,
                'price_yearly' => 25000,
                'max_members' => 200,
                'max_storage_mb' => 10000,
                'features' => [
                    'Unlimited members',
                    'All Premium features',
                    'Multi-location support',
                    'API access',
                    'Dedicated support',
                    '10 GB storage',
                    'Custom integrations',
                    'Training sessions',
                ],
                'trial_days' => 30,
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
