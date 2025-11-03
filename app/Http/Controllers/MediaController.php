<?php

// app/Http/Controllers/MediaController.php
namespace App\Http\Controllers;

use App\Helpers\S3Helper;

class MediaController extends Controller
{
    public function preview($path)
    {
        $temporaryUrl = S3Helper::getUrl($path, 60);
        
        if (!$temporaryUrl) {
            abort(404);
        }
        
        return $temporaryUrl;
    }
}