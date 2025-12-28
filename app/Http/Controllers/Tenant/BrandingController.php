<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandingController extends Controller
{
    public function edit()
    {
        $tenant = Auth::user()->tenant;
        $tenantLogoPath = $tenant?->logo_path;

        return view('tenant.branding.logo', compact('tenant', 'tenantLogoPath'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role?->slug !== 'mess-admin') {
            abort(403);
        }

        $tenant = $user->tenant;
        if (!$tenant) {
            abort(404);
        }

        $validated = $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $path = $validated['logo']->store('tenant-logos/' . $tenant->id, 'public');
        $tenant->update(['logo_path' => $path]);

        return back()->with('success', __('common.update'));
    }
}
