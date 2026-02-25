<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetStoreContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isCashier()) {
                // Cashiers are strictly locked to their assigned store.
                session(['active_store_id' => $user->store_id]);
            } elseif (!session()->has('active_store_id')) {
                // Administrators get their assigned store as default if nothing is set in session.
                // If they have no assigned store, they get the first available store.
                $storeId = $user->store_id ?? \App\Models\Store::first()->id ?? null;
                if ($storeId) {
                    session(['active_store_id' => $storeId]);
                }
            }
            // If it's an Administrator and session already has active_store_id, 
            // we do nothing and let them browse their selected store.
        }

        return $next($request);
    }
}
