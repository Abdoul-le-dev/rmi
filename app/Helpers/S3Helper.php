<?php
// app/Helpers/S3Helper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Helper
{
    public static function getTemporaryUrl($path, $expiration = 60)
    {
        // Nettoyer le chemin si nécessaire
        $path = ltrim(self::normalizeS3Path($path), '/');

        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->temporaryUrl(
                $path,
                now()->addMinutes($expiration)
            );
        }

        return null;
    }

    public static function normalizeS3Path(string $path): string
    {
        // On récupère les variables d'environnement
        $awsUrl = rtrim(config('filesystems.disks.s3.url') ?? env('AWS_URL'), '/');
        $bucketRoot = trim(env('AWS_BUCKET_ROOT', ''), '/');

        // Si le path commence par l’URL complète S3
        if (Str::startsWith($path, $awsUrl)) {
            // Supprime l’URL et tout ce qui précède le bucket root
            $relative = Str::after($path, $awsUrl . '/');

            // Si le bucket root est présent au début du chemin, on l’enlève aussi
            if (Str::startsWith($relative, $bucketRoot . '/')) {
                $relative = Str::after($relative, $bucketRoot . '/');
            }

            return $relative;
        }

        // Si ce n’est pas un lien S3, on retourne le path tel quel
        return $path;
    }
}
