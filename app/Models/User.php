<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',   // 'role' এর পরিবর্তে
        'tenant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the tenant that the user belongs to.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the role that the user has.
     * (নতুন মেথড)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has a specific permission.
     * (নতুন মেথড)
     */
    public function hasPermission($permissionSlug): bool
    {
        // যদি ইউজার সুপার অ্যাডমিন হয়, সব পারমিশন দিন
        // (আমরা সিডারে 'super-admin' slug সহ রোল তৈরি করবো)
        if ($this->role && $this->role->slug == 'super-admin') {
            return true;
        }

        // ইউজারের রোল আছে কিনা এবং রোলের পারমিশনের মধ্যে $permissionSlug আছে কিনা দেখুন
        return $this->role && $this->role->permissions->contains('slug', $permissionSlug);
    }
}