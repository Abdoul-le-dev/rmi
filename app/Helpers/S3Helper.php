<?php
// app/Helpers/S3Helper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Helper
{
    /**
     * Génère une URL temporaire pour un fichier S3
     * 
     * @param string $path Peut être un chemin relatif, absolu S3, ou local
     * @param int $expiration Durée de validité en minutes
     * @return string|null
     */
    public static function getTemporaryUrl($path, $expiration = 60)
    {
        // Si c'est un chemin local (commence par /store ou ne contient pas amazonaws.com)
        if (self::isLocalPath($path)) {
            return $path; // Retourner tel quel
        }

        // Normaliser le chemin S3
        $relativePath = self::normalizeS3Path($path);
        
        if (empty($relativePath)) {
            return null;
        }

        // Vérifier l'existence et générer l'URL temporaire
        try {
            if (Storage::disk('s3')->exists($relativePath)) {
                return Storage::disk('s3')->temporaryUrl(
                    $relativePath,
                    now()->addMinutes($expiration)
                );
            }
        } catch (\Exception $e) {
            \Log::error('S3Helper::getTemporaryUrl error', [
                'path' => $path,
                'relativePath' => $relativePath,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Vérifie si le chemin est un fichier local (pas sur S3)
     * 
     * @param string $path
     * @return bool
     */
    public static function isLocalPath($path): bool
    {
        if (empty($path)) {
            return false;
        }

        // C'est un chemin local si :
        // 1. Commence par /store, /storage, /public, etc.
        // 2. Ne contient pas amazonaws.com
        // 3. Commence par / mais n'est pas une URL S3
        
        $localPrefixes = ['/store', '/storage', '/public', '/uploads'];
        
        foreach ($localPrefixes as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                return true;
            }
        }

        // Si ça ne contient pas amazonaws.com et commence par /, c'est probablement local
        if (Str::startsWith($path, '/') && !Str::contains($path, 'amazonaws.com')) {
            return true;
        }

        return false;
    }

    /**
     * Normalise un chemin S3 en enlevant l'URL de base et le bucket root
     * 
     * @param string $path
     * @return string
     */
    public static function normalizeS3Path(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // Si c'est déjà un chemin relatif (pas d'URL), le retourner nettoyé
        if (!Str::contains($path, 'http') && !Str::contains($path, 'amazonaws.com')) {
            return ltrim($path, '/');
        }

        $bucket = config('filesystems.disks.s3.bucket');
        $bucketRoot = trim(env('AWS_BUCKET_ROOT', ''), '/');

        // Patterns possibles pour les URLs S3
        $patterns = [
            // Format 1: https://bucket.s3.region.amazonaws.com/bucket-root/path
            "#https?://{$bucket}\.s3\.[^/]+\.amazonaws\.com/(.+)$#",
            
            // Format 2: https://s3.region.amazonaws.com/bucket/bucket-root/path
            "#https?://s3\.[^/]+\.amazonaws\.com/{$bucket}/(.+)$#",
            
            // Format 3: https://bucket.s3.amazonaws.com/bucket-root/path
            "#https?://{$bucket}\.s3\.amazonaws\.com/(.+)$#",
            
            // Format générique: tout après amazonaws.com/
            "#amazonaws\.com/(.+)$#",
        ];

        $relativePath = '';

        // Essayer chaque pattern
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $path, $matches)) {
                $relativePath = urldecode($matches[1]);
                break;
            }
        }

        // Si aucun pattern ne correspond, essayer de parser l'URL
        if (empty($relativePath)) {
            $parsedUrl = parse_url($path);
            if (isset($parsedUrl['path'])) {
                $relativePath = ltrim($parsedUrl['path'], '/');
                
                // Enlever le nom du bucket s'il est présent au début du path
                if (Str::startsWith($relativePath, $bucket . '/')) {
                    $relativePath = Str::after($relativePath, $bucket . '/');
                }
            }
        }

        // Enlever le bucket root s'il est présent au début
        if (!empty($bucketRoot) && !empty($relativePath)) {
            if (Str::startsWith($relativePath, $bucketRoot . '/')) {
                $relativePath = Str::after($relativePath, $bucketRoot . '/');
            }
        }

        return ltrim($relativePath, '/');
    }

    /**
     * Détermine si un chemin pointe vers S3 ou vers le stockage local
     * 
     * @param string $path
     * @return bool
     */
    public static function isS3Path($path): bool
    {
        if (empty($path)) {
            return false;
        }

        return Str::contains($path, 'amazonaws.com') || 
               Str::contains($path, 's3.');
    }

    /**
     * Obtient le chemin relatif d'un fichier, qu'il soit sur S3 ou local
     * Utile pour stocker dans la base de données
     * 
     * @param string $path
     * @return string
     */
    public static function getRelativePath($path): string
    {
        // Si c'est local, retourner tel quel
        if (self::isLocalPath($path)) {
            return $path;
        }

        // Si c'est S3, normaliser
        if (self::isS3Path($path)) {
            return self::normalizeS3Path($path);
        }

        // Sinon, retourner nettoyé
        return ltrim($path, '/');
    }

    /**
     * Génère l'URL appropriée selon le type de stockage
     * 
     * @param string $path
     * @param int $expiration
     * @return string|null
     */
    public static function getUrl($path, $expiration = 60)
    {
        if (empty($path)) {
            return null;
        }

        // Si c'est déjà une URL complète, la retourner
        if (Str::startsWith($path, 'http')) {
            // Si c'est une URL S3, générer une URL temporaire
            if (self::isS3Path($path)) {
                return self::getTemporaryUrl($path, $expiration);
            }
            return $path;
        }

        // Si c'est local
        if (self::isLocalPath($path)) {
            return asset($path);
        }

        // Sinon, considérer comme S3
        return self::getTemporaryUrl($path, $expiration);
    }
}