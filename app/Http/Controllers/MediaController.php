<?php

// app/Http/Controllers/MediaController.php
namespace App\Http\Controllers;

use App\Helpers\S3Helper;

class MediaController extends Controller
{
    public function preview($path)
    {
        $temporaryUrl = \App\Helpers\S3Helper::getTemporaryUrl($path, 60);

        if (!$temporaryUrl) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json(['url' => $temporaryUrl]);
    }
}
