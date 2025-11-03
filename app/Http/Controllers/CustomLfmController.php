<?php

namespace App\Http\Controllers;

use UniSharp\LaravelFilemanager\Controllers\LfmController as BaseLfmController;
use Illuminate\Support\Facades\Storage;

class CustomLfmController extends BaseLfmController
{
    /**
     * Override la méthode qui retourne les fichiers
     */
    public function getItems()
    {
        $items = parent::getItems();
        
        // Transformer les URLs S3 en chemins relatifs
        if (isset($items['items'])) {
            $items['items'] = array_map(function($item) {
                if (isset($item['url'])) {
                    $item['url'] = $this->extractRelativePath($item['url']);
                }
                if (isset($item['thumb_url'])) {
                    $item['thumb_url'] = $this->extractRelativePath($item['thumb_url']);
                }
                return $item;
            }, $items['items']);
        }
        
        return $items;
    }
    
    /**
     * Extraire le chemin relatif depuis l'URL S3
     */
    private function extractRelativePath($url)
    {
        // Si c'est déjà un chemin relatif, le retourner tel quel
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        // Extraire le chemin depuis l'URL S3
        $bucket = config('filesystems.disks.s3.bucket');
        $pattern = '/amazonaws\.com\/' . preg_quote($bucket, '/') . '\/(.+?)(\?|$)/';
        
        if (preg_match($pattern, $url, $matches)) {
            return urldecode($matches[1]);
        }
        
        // Fallback : extraire tout après le bucket
        $parts = parse_url($url);
        if (isset($parts['path'])) {
            return ltrim($parts['path'], '/');
        }
        
        return $url;
    }
}