<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    // app/Http/Middleware/RoleMiddleware.php

// ...

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        
        $userRole = strtolower($user->role); 
        
        
        $allowedRoles = array_map('strtolower', $roles); 

        
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        
        return abort(403, 'Anda tidak memiliki akses ke halaman ini.'); 
        
    }
}