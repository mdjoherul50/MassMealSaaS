<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     * (Mess Admin-কে 'members.index' পেজ দেখতে দেবে)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('members.view');
    }

    /**
     * Determine whether the user can view the model.
     * (সদস্যের 'show' পেজ দেখার পারমিশন)
     */
    public function view(User $user, Member $member): bool
    {
        // রুল ১: যদি ইউজার Mess Admin হন (যেকোনো সদস্যকে দেখতে পারবেন)
        if ($user->hasPermission('members.view')) {
            return $user->tenant_id === $member->tenant_id;
        }

        // রুল ২: যদি ইউজার সাধারণ Member হন (শুধু নিজের প্রোফাইল দেখতে পারবেন)
        // আমরা email দিয়ে User এবং Member-কে লিঙ্ক করছি
        return $user->tenant_id === $member->tenant_id && $user->email === $member->email;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('members.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): bool
    {
        // অ্যাডমিন নিজের টেন্যান্টের সদস্যদের এডিট করতে পারবেন
        if ($user->hasPermission('members.edit')) {
             return $user->tenant_id === $member->tenant_id;
        }
        // অথবা সদস্য নিজের প্রোফাইল নিজে এডিট করতে পারবেন (email বাদে)
        return $user->tenant_id === $member->tenant_id && $user->email === $member->email;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->hasPermission('members.delete') && $user->tenant_id === $member->tenant_id;
    }
}
