<?php
// app/Helpers/S3Helper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class S3Helper
{
    public static function getTemporaryUrl($path, $expiration = 60)
    {
        // Nettoyer le chemin si nécessaire
        $path = ltrim($path, '/');
        
        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->temporaryUrl(
                $path,
                now()->addMinutes($expiration)
            );
        }
        
        return null;
    }
}