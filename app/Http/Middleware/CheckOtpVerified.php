<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
            if ($request->expectsJson() || $request->isXmlHttpRequest() || $request->is('chat')) {
                return response()->json([
                    'error' => true,
                    'redirect' => route('otp.verify'),
                    'message' => 'Silakan verifikasi OTP terlebih dahulu.'
                ], 403);
            }
            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
