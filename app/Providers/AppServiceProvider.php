<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Models\Member;
use App\Policies\MemberPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Member::class, MemberPolicy::class);
        // মাইগ্রেশনের আগে যেন एरর না দেয় সেজন্য try...catch ব্লক ব্যবহার করা
        try {
            // permissions টেবিলের সব পারমিশন লুপ করে গেট ডিফাইন করুন
            if (Schema::hasTable('permissions')) {
                Permission::all()->each(function ($permission) {
                    Gate::define($permission->slug, function (User $user) use ($permission) {
                        // User মডেলে আমাদের তৈরি করা hasPermission মেথডটি কল করুন
                        return $user->hasPermission($permission->slug);
                    });
                });
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}
