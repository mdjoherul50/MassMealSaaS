<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bazar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BazarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bazars = Bazar::with('buyer')->latest('date')->paginate(20);
        return view('tenant.bazars.index', compact('bazars'));
    }

    /**
     * Show the form for creating a new resource. (সংশোধিত)
     */
    public function create()
    {
        // 'member' রোলটি এখানে যোগ করা হয়েছে
        $roleSlugs = ['mess-admin', 'bazarman', 'member'];

        $buyers = User::where('tenant_id', Auth::user()->tenant_id)
                      ->whereHas('role', function ($query) use ($roleSlugs) {
                          $query->whereIn('slug', $roleSlugs);
                      })
                      ->orderBy('name')
                      ->get();
                      
        return view('tenant.bazars.create', compact('buyers'));
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'buyer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validatedData['tenant_id'] = Auth::user()->tenant_id;
        Bazar::create($validatedData);

        return redirect()->route('bazars.index')->with('success', 'Bazar entry added successfully.');
    }

    /**
     * Display the specified resource. (নতুন)
     */
    public function show(Bazar $bazar)
    {
        // TenantScope স্বয়ংক্রিয়ভাবে চেক করবে
        return view('tenant.bazars.show', compact('bazar'));
    }

    /**
     * Show the form for editing the specified resource. (নতুন)
     */
    public function edit(Bazar $bazar)
    {
        // 'member' রোলটি এখানে যোগ করা হয়েছে
        $roleSlugs = ['mess-admin', 'bazarman', 'member'];
        
        $buyers = User::where('tenant_id', Auth::user()->tenant_id)
                      ->whereHas('role', function ($query) use ($roleSlugs) {
                          $query->whereIn('slug', $roleSlugs);
                      })
                      ->orderBy('name')
                      ->get();
                      
        return view('tenant.bazars.edit', compact('bazar', 'buyers'));
    }

    /**
     * Update the specified resource in storage. (নতুন)
     */
    public function update(Request $request, Bazar $bazar)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'buyer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $bazar->update($validatedData);

        return redirect()->route('bazars.index')->with('success', 'Bazar entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage. (নতুন)
     */
    public function destroy(Bazar $bazar)
    {
        $bazar->delete();
        return redirect()->route('bazars.index')->with('success', 'Bazar entry deleted successfully.');
    }
}