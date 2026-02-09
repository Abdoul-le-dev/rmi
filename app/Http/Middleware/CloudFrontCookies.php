<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CloudFrontCookies
{
    protected $cloudFrontSigner;

    public function __construct()
    {
        // Injecter ton service CloudFrontSigner
        $this->cloudFrontSigner = app()->make('App\Services\CloudFrontUrlSigner');
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
        

            // Générer les cookies signés
            $signedCookies = $this->cloudFrontSigner->getSignedCookie();

            // Forcer la valeur de Key-Pair-Id si présent
            // if (isset($signedCookies['CloudFront-Key-Pair-Id'])) {
            //     $signedCookies['CloudFront-Key-Pair-Id'] = config('services.cloudfront.key_pair_id');
            // }

            // Obtenir la réponse suivante (route ou contrôleur)
            $response = $next($request);

            // Ajouter les cookies à la réponse
            foreach ($signedCookies as $name => $value) {
                $cookie = cookie(
                    $name,
                    $value,
                    config('services.cloudfront.url_expiration') / 60, // en minutes
                    '/',
                    config('services.cloudfront.cookie_domain'),
                    true,   // secure HTTPS
                    false,  // httpOnly=false pour debug
                    false,  // raw
                    'none'  // SameSite=None
                );

                $response->withCookie($cookie);
            }

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
