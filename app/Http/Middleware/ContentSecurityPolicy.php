<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', $this->getPolicy());

        return $response;
    }

    private function getPolicy(): string
    {
        $isLocal = app()->environment('local');
        
        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.quilljs.com";
        $styleSrc = "'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.quilljs.com";
        $fontSrc = "'self' data: https://fonts.gstatic.com";
        $imgSrc = "'self' data: blob:";
        $connectSrc = "'self'";

        // Allow Vite dev server in local environment
        if ($isLocal) {
            $scriptSrc .= " http://localhost:* http://127.0.0.1:*";
            $styleSrc .= " http://localhost:* http://127.0.0.1:*";
            $connectSrc .= " http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*";
            $imgSrc .= " http://localhost:* http://127.0.0.1:*";
        }

        $policy = [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src {$styleSrc}",
            "img-src {$imgSrc}",
            "font-src {$fontSrc}",
            "connect-src {$connectSrc}",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];

        if (! $isLocal) {
            $policy[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $policy);
    }
}
