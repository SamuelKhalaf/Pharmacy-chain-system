<?php

namespace App\Http\Middleware;

use App\Enums\AdminRole;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($guard === 'admin' && $user) {
                    switch ($user->role_id) {
                        case AdminRole::SuperAdmin->value:
                            return redirect()->route('home');
                        case AdminRole::BranchAdmin->value:
                            return redirect()->route('pharmacy.index');
                        default:
                            return response()->view('errors.403', [], 403);
                    }
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }

}
