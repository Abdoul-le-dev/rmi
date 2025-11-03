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
        try {
            $response = $next($request);
            
            // Vérifier si c'est une réponse JSON
            $contentType = $response->headers->get('content-type');
            
            if ($contentType && Str::contains($contentType, 'application/json')) {
                $content = $response->getContent();
                
                if (!empty($content)) {
                    $data = json_decode($content, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                        $data = $this->processData($data);
                        $response->setContent(json_encode($data));
                    }
                }
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('LFM Middleware Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Retourner la réponse originale en cas d'erreur
            return $next($request);
        }
    }
    
    private function processData($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        
        // Traiter les champs URL courants
        $urlFields = ['url', 'thumb_url', 'path', 'source'];
        
        foreach ($urlFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = $this->stripS3BaseUrl($data[$field]);
            }
        }
        
        // Traiter un tableau d'éléments
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $key => $item) {
                if (is_array($item)) {
                    $data['items'][$key] = $this->processData($item);
                }
            }
        }
        
        // Traiter récursivement les autres arrays
        foreach ($data as $key => $value) {
            if (is_array($value) && $key !== 'items') {
                $data[$key] = $this->processData($value);
            }
        }
        
        return $data;
    }
    
    private function stripS3BaseUrl($url)
    {
        if (!is_string($url) || empty($url)) {
            return $url;
        }
        
        // Ne traiter que les URLs complètes
        if (!Str::startsWith($url, 'http')) {
            return $url;
        }
        
        try {
            $awsUrl = env('AWS_URL');
            $bucketRoot = env('AWS_BUCKET_ROOT');
            
            if (empty($awsUrl)) {
                return $url;
            }
            
            $awsUrl = rtrim($awsUrl, '/');
            
            // Cas 1: AWS_URL + AWS_BUCKET_ROOT
            if (!empty($bucketRoot)) {
                $bucketRoot = trim($bucketRoot, '/');
                $fullBase = $awsUrl . '/' . $bucketRoot . '/';
                
                if (Str::startsWith($url, $fullBase)) {
                    return Str::after($url, $fullBase);
                }
            }
            
            // Cas 2: Seulement AWS_URL
            if (Str::startsWith($url, $awsUrl . '/')) {
                return Str::after($url, $awsUrl . '/');
            }
            
            // Cas 3: Fallback pour les URLs S3 standard
            if (Str::contains($url, 'amazonaws.com')) {
                $bucket = config('filesystems.disks.s3.bucket');
                
                if (!empty($bucket)) {
                    // Pattern: https://bucket.s3.region.amazonaws.com/path
                    if (preg_match("#/{$bucket}/(.+)$#", $url, $matches)) {
                        return urldecode($matches[1]);
                    }
                    
                    // Pattern: https://s3.region.amazonaws.com/bucket/path
                    if (preg_match("#amazonaws\.com/{$bucket}/(.+)$#", $url, $matches)) {
                        return urldecode($matches[1]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error stripping S3 URL: ' . $e->getMessage(), ['url' => $url]);
        }
        
        return $url;
    }
}