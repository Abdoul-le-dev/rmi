<?php
namespace App\Listeners;

use UniSharp\LaravelFilemanager\Events\FileWasUploaded;

class LfmUploadListener
{
    public function handle(FileWasUploaded $event)
    {
        // Chemin complet du fichier uploadé
        $path = $event->path(); // exemple : "https://bucket.s3.amazonaws.com/rmiclass-prod-uploads/avatar.jpg"

        // Extraire le chemin S3 relatif (ex: "rmiclass-prod-uploads/avatar.jpg")
        $relativePath = ltrim(parse_url($path, PHP_URL_PATH), '/');

        // Si ton LFM a un dossier base (ex: "rmiclass-prod-uploads/"), tu peux le retirer :
        $relativePath = preg_replace('/^rmiclass-prod-uploads\//', '', $relativePath);

        // Remplacer le lien absolu par le chemin relatif
        $event->setPath($relativePath);
    }
}
