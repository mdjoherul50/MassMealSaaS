<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bazar;
use App\Models\User; // <-- User মডেল ইম্পোর্ট করা আছে
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
        // --- এটি হলো সমাধান ---
        // আমরা এখন 'role'-এর বদলে 'role.slug' দিয়ে খুঁজব
        
        // সিডার থেকে রোলের স্ল্যাগগুলো নিন
        $roleSlugs = ['bazarman', 'mess-admin'];

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

    // ... (বাকি মেথডগুলো)
}