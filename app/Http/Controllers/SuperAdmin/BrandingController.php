<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class BrandingController extends Controller
{
    public function edit()
    {
        $siteLogoPath = AppSetting::getValue('site_logo_path');

        return view('superadmin.branding.logo', compact('siteLogoPath'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $path = $validated['logo']->store('branding', 'public');
        AppSetting::setValue('site_logo_path', $path);

        return back()->with('success', __('common.update'));
    }
}
