<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika request menerima format gzip
        if (in_array('gzip', $request->getEncodings()) && function_exists('gzencode')) {
            $content = $response->getContent();
            
            // Minimal 1024 bytes (1KB) agar kompresi efisien
            if (strlen($content) > 1024) {
                $response->setContent(gzencode($content, 9));
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Vary', 'Accept-Encoding');
                // Mengupdate Content-Length
                $response->headers->set('Content-Length', strlen($response->getContent()));
            }
        }

        return $response;
    }
}
