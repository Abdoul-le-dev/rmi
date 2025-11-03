<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CustomLfmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Charger vos routes personnalisées pour LFM
        $this->registerLfmRoutes();
    }
    
    protected function registerLfmRoutes()
    {
        Route::group([
            'prefix' => 'laravel-filemanager',
            'middleware' => ['web', 'auth'],
            'namespace' => 'App\Http\Controllers'
        ], function () {
            require base_path('routes/lfm.php');
        });
    }
    
    public function register()
    {
        //
    }
}