<?php
// app/Http/Middleware/LfmS3PathMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LfmS3PathMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($request->isMethod('post') && $response->isSuccessful()) {
            $content = $response->getContent();
            $data = json_decode($content, true);
            
            // Remplacer les URLs absolues par des chemins relatifs
            if (isset($data['url'])) {
                $data['url'] = $this->getRelativePath($data['url']);
                $response->setContent(json_encode($data));
            }
        }
        
        return $response;
    }
    
    private function getRelativePath($url)
    {
        // Extraire le chemin relatif depuis l'URL S3
        $pattern = '/amazonaws\.com\/(.+)$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return $url;
    }
}