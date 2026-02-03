<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyJibriToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $bearerToken = $request->bearerToken();
        $validApiKey = config('services.jibri.webhook_token');

        // Debug temporaire
        Log::info('API Key check', [
            'received' => $bearerToken,
            'received_length' => strlen($bearerToken ?? ''),
            'expected' => $validApiKey,
            'expected_length' => strlen($validApiKey),
            'match' => $bearerToken === $validApiKey,
            'received_trim' => trim($bearerToken ?? ''),
            'expected_trim' => trim($validApiKey),
        ]);

        if ($bearerToken !== $validApiKey) {
            return response()->json([
                'success' => false,
                'status' => 'client_identity_error',
                'message' => 'client identification failed. check the api key'
            ], 401);
        }
        return $next($request);
    }
}
