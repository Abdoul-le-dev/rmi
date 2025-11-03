<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Paginator::defaultView('pagination::default');
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        try {
        // Récupérer les paramètres généraux depuis la base
        $generalSettings = DB::table('settings')->where('name', 'general')->first();
        $generalSettings = $generalSettings ? json_decode($generalSettings->value, true) : [];

        // Partager avec toutes les vues
        view()->share('generalSettings', $generalSettings);
        } catch (\Exception $e) {
            // En cas d'erreur (ex: pendant migration)
            view()->share('generalSettings', []);
        }
        }
}
