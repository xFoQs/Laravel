<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id != 1) {
            return redirect()->route('users'); // Przekieruj użytkowników z rolą różną od 1 na stronę "users"
        }

        return $next($request); // Pozostaw użytkowników z rolą 1 na obecnej stronie
    }
}
