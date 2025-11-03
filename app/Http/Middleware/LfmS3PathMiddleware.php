<?php
// app/Http/Middleware/LfmS3PathMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LfmS3PathMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Vérifier si c'est une réponse JSON
        $contentType = $response->headers->get('content-type');
        
        if ($contentType && Str::contains($contentType, 'application/json')) {
            $content = $response->getContent();
            $data = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE && $data) {
                Log::info('LFM Middleware - Data avant transformation:', $data);
                
                $data = $this->processData($data);
                
                Log::info('LFM Middleware - Data après transformation:', $data);
                
                $response->setContent(json_encode($data));
            }
        }
        
        return $response;
    }
    
    private function processData($data)
    {
        // Traiter un seul élément
        if (isset($data['url'])) {
            $data['url'] = $this->stripS3BaseUrl($data['url']);
        }
        
        if (isset($data['thumb_url'])) {
            $data['thumb_url'] = $this->stripS3BaseUrl($data['thumb_url']);
        }
        
        if (isset($data['path'])) {
            $data['path'] = $this->stripS3BaseUrl($data['path']);
        }
        
        // Traiter un tableau d'éléments
        if (isset($data['items']) && is_array($data['items'])) {
            $data['items'] = array_map(function($item) {
                return $this->processData($item);
            }, $data['items']);
        }
        
        // Traiter récursivement tous les arrays
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->processData($value);
            } elseif (is_string($value) && $this->isS3Url($value)) {
                $data[$key] = $this->stripS3BaseUrl($value);
            }
        }
        
        return $data;
    }
    
    private function stripS3BaseUrl($url)
    {
        if (!is_string($url)) {
            return $url;
        }
        
        $awsUrl = rtrim(env('AWS_URL'), '/');
        $bucketRoot = trim(env('AWS_BUCKET_ROOT', ''), '/');
        
        Log::info('Traitement URL:', [
            'original' => $url,
            'AWS_URL' => $awsUrl,
            'AWS_BUCKET_ROOT' => $bucketRoot
        ]);
        
        // Si AWS_BUCKET_ROOT est défini
        if (!empty($bucketRoot)) {
            // Chercher et supprimer: AWS_URL/AWS_BUCKET_ROOT/
            $fullBase = $awsUrl . '/' . $bucketRoot . '/';
            if (Str::startsWith($url, $fullBase)) {
                $relativePath = Str::after($url, $fullBase);
                Log::info('URL transformée avec BUCKET_ROOT:', $relativePath);
                return $relativePath;
            }
        }
        
        // Sinon, chercher et supprimer uniquement AWS_URL
        if (Str::startsWith($url, $awsUrl . '/')) {
            $relativePath = Str::after($url, $awsUrl . '/');
            Log::info('URL transformée sans BUCKET_ROOT:', $relativePath);
            return $relativePath;
        }
        
        // Si l'URL contient amazonaws.com (fallback)
        if (Str::contains($url, 'amazonaws.com')) {
            $bucket = config('filesystems.disks.s3.bucket');
            
            // Essayer différents patterns S3
            $patterns = [
                "/{$bucket}\/(.+)$/",
                "/amazonaws\.com\/(.+)$/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $url, $matches)) {
                    $relativePath = urldecode($matches[1]);
                    Log::info('URL transformée avec pattern:', $relativePath);
                    return $relativePath;
                }
            }
        }
        
        return $url;
    }
    
    private function isS3Url($url)
    {
        if (!is_string($url)) {
            return false;
        }
        
        $awsUrl = env('AWS_URL');
        
        return Str::startsWith($url, $awsUrl) || 
               Str::contains($url, 'amazonaws.com') ||
               Str::contains($url, 's3.');
    }
}