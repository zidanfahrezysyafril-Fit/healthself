<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard sesuai role
            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard')->with('error', 'Akses tidak diizinkan.'),
                'konselor' => redirect()->route('konselor.dashboard')->with('error', 'Akses tidak diizinkan.'),
                default    => redirect()->route('home')->with('error', 'Akses tidak diizinkan.'),
            };
        }

        return $next($request);
    }
}
