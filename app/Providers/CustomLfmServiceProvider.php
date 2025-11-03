<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CustomLfmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Désactiver les routes par défaut de LFM
        $this->loadRoutesFrom();
    }
    
    protected function loadRoutesFrom()
    {
        Route::group([
            'prefix' => 'laravel-filemanager',
            'middleware' => ['web', 'auth'],
            'namespace' => 'App\Http\Controllers'
        ], function () {
            require base_path('routes/lfm.php');
        });
    }
}