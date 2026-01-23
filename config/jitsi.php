<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Jitsi Meet Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration Jitsi Meet avec authentification JWT
    |
    */

    // App ID utilisé pour générer les tokens JWT
    'app_id' => env('JITSI_APP_ID', 'rmiclass_jitsi'),

    // Clé secrète pour signer les tokens JWT (TRÈS IMPORTANT - À GARDER SECRET!)
    'app_secret' => env('JITSI_APP_SECRET'),

    // Domaine de votre serveur Jitsi
    'domain' => env('JITSI_DOMAIN', 'live.rmiclass.net'),

    // Durée par défaut des tokens en heures
    'default_token_duration' => env('JITSI_TOKEN_DURATION', 24),

    // Configuration des fonctionnalités
    'features' => [
        'recording' => env('JITSI_FEATURE_RECORDING', true),
        'livestreaming' => env('JITSI_FEATURE_LIVESTREAMING', true),
        'transcription' => env('JITSI_FEATURE_TRANSCRIPTION', false),
        'screen_sharing' => env('JITSI_FEATURE_SCREEN_SHARING', true),
    ],

    // Paramètres des live classes
    'live_class' => [
        // Minutes avant l'heure prévue pour permettre à l'instructeur de démarrer
        'start_early_minutes' => env('JITSI_START_EARLY_MINUTES', 15),
        
        // Minutes après l'heure de fin prévue avant de fermer automatiquement
        'auto_end_delay_minutes' => env('JITSI_AUTO_END_DELAY_MINUTES', 30),
        
        // Nombre maximum de participants par défaut
        'default_max_participants' => env('JITSI_DEFAULT_MAX_PARTICIPANTS', 100),
    ],
];