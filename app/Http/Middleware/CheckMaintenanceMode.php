<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Schema::hasTable('settings')) {
                $maintenanceMode = Setting::where('key', 'maintenance_mode')->value('value');
                
                if ($request->query('admin') === '1') {
                    session(['admin_bypass' => true]);
                }

                if ($maintenanceMode == '1') {
                    
                    // Allow access if logged in as Admin or Super Admin
                    if (Auth::check()) {
                        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')) {
                            return $next($request);
                        } else {
                            // If regular user, force logout and show 503
                            Auth::logout();
                            return response()->view('errors.503', [], 503);
                        }
                    }
                    
                    // Allow access to login/logout only if bypass session exists
                    if ($request->is('login') || $request->is('logout')) {
                        if (session('admin_bypass')) {
                            return $next($request);
                        }
                    }
                    
                    // Otherwise, show maintenance error
                    return response()->view('errors.503', [], 503);
                }
            }
        } catch (\Exception $e) {
            // Ignore if settings table doesn't exist yet
        }
        
        return $next($request);
    }
}
