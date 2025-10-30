<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SRS সেকশন ১২ অনুযায়ী
        // super_admin ছাড়া বাকিদের tenant_id থাকতে হবে
        if ($request->user() && 
            $request->user()->role !== 'super_admin' && 
            !$request->user()->tenant_id) 
        {
            // যদি কোনো mess_admin এর tenant_id সেট করা না থাকে
            // আপনি এখানে লগ-আউট করে দিতে পারেন বা একটি एरর পেজে পাঠাতে পারেন
            auth()->logout();
            return redirect('/')->with('error', 'You are not assigned to any mess.');
        }

        return $next($request);
    }
}