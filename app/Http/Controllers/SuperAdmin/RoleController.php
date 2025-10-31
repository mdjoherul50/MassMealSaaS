<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('superadmin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            // "members.view" থেকে "members" অংশটি আলাদা করার জন্য
            return explode('.', $permission->slug)[0];
        });
        return view('superadmin.roles.create', compact('permissions'));
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $role = Role::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['slug']),
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($validated['permissions']);
            }
        });

        return redirect()->route('superadmin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->slug)[0];
        });
        
        // রোলের সাথে বর্তমানে কোন পারমিশনগুলো আছে তা লোড করুন
        $role->load('permissions'); 
        
        // একটি সহজ অ্যারে তৈরি করুন (id => true) যেন ভিউ ফাইলে চেক করা সহজ হয়
        $rolePermissions = $role->permissions->pluck('id')->flip()->all();

        return view('superadmin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Super Admin রোলটি যেন এডিট করা না যায় (নিরাপত্তার জন্য)
        if ($role->slug == 'super-admin') {
            return back()->with('error', 'Cannot edit the Super Admin role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        DB::transaction(function () use ($validated, $request, $role) {
            $role->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['slug']),
            ]);

            $role->permissions()->sync($request->input('permissions', []));
        });

        return redirect()->route('superadmin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Super Admin বা Mess Admin রোল দুটি যেন ডিলিট করা না যায়
        if (in_array($role->slug, ['super-admin', 'mess-admin'])) {
            return back()->with('error', 'Cannot delete default system roles.');
        }

        $role->delete();
        return redirect()->route('superadmin.roles.index')->with('success', 'Role deleted successfully.');
    }
}