<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * API key is OPTIONAL - if provided, it must be valid.
     * If not provided, request continues (for web with CSRF protection).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        
        // If API key is provided, validate it
        if ($apiKey) {
            $validApiKey = config('chatbot.api_key');
            
            if (!$validApiKey || $apiKey !== $validApiKey) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid API key'
                ], 401);
            }
        }
        
        // If no API key provided, continue (for web/CSRF protected requests)
        return $next($request);
    }
}
