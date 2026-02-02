<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $liveClass->title }} - Live Class</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block px-4 py-2 bg-white rounded-full shadow-sm mb-4">
                <i class="fas fa-video text-blue-600 mr-2"></i>
                <span class="text-gray-700 font-semibold">Live Class Public</span>
            </div>
        </div>

        <!-- Card principale -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Image de couverture -->
            <div class="relative h-80 bg-gradient-to-br from-blue-500 to-indigo-600">
                @if($liveClass->live_cover)
                    <img src="/store/{{ $liveClass->live_cover }}" alt="{{ $liveClass->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-video text-9xl text-white/20"></i>
                    </div>
                @endif

                <!-- Statut -->
                <div class="absolute top-6 left-6">
                    @php
                        $statusConfig = [
                            'scheduled' => ['class' => 'bg-blue-500', 'icon' => 'fa-calendar-check', 'label' => 'Planifié'],
                            'live' => ['class' => 'bg-red-500 animate-pulse', 'icon' => 'fa-circle', 'label' => 'EN DIRECT'],
                            'ended' => ['class' => 'bg-gray-500', 'icon' => 'fa-check-circle', 'label' => 'Terminé'],
                        ];
                        $status = $statusConfig[$liveClass->status] ?? $statusConfig['scheduled'];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-semibold text-white {{ $status['class'] }} shadow-lg">
                        <i class="fas {{ $status['icon'] }} mr-2"></i>
                        {{ $status['label'] }}
                    </span>
                </div>

                <!-- Titre et description -->
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <h1 class="text-4xl font-bold mb-2">{{ $liveClass->title }}</h1>
                    <p class="text-lg text-gray-200">{{ $liveClass->description ?? 'Aucune description' }}</p>
                </div>
            </div>

            <!-- Contenu -->
            <div class="p-8">
                <!-- Informations du live -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date et heure</p>
                            <p class="font-semibold text-gray-900">{{ $liveClass->scheduled_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Durée</p>
                            <p class="font-semibold text-gray-900">{{ $liveClass->duration_minutes }} minutes</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Formateur</p>
                            <p class="font-semibold text-gray-900">{{ $liveClass->instructor->full_name ?? 'Non défini' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Message de statut -->
                <div id="status-message" class="mb-6">
                    @if($liveClass->status === 'scheduled')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                            <p class="text-yellow-800 font-medium">Ce live n'a pas encore commencé</p>
                            <p class="text-yellow-700 text-sm mt-1">Revenez à l'heure prévue</p>
                        </div>
                    @elseif($liveClass->status === 'ended')
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                            <i class="fas fa-flag-checkered text-gray-600 text-2xl mb-2"></i>
                            <p class="text-gray-800 font-medium">Ce live est terminé</p>
                        </div>
                    @endif
                </div>

                <!-- Formulaire de connexion (pour invités non connectés) -->
                @guest
                    <div id="guest-section" class="{{ $liveClass->status !== 'live' ? 'hidden' : '' }}">
                        <!-- Formulaire pour nouveaux invités -->
                        <div id="guest-form" class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-user-circle mr-2"></i>
                                Rejoindre en tant qu'invité
                            </h3>
                            <form id="join-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Votre nom <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="guest-name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Ex: Jean Dupont">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Votre email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="guest-email" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Ex: jean@example.com">
                                </div>

                                <button type="submit" id="join-button"
                                    class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Rejoindre le live maintenant
                                </button>
                            </form>
                        </div>

                        <!-- Bouton pour invités ayant déjà rejoint -->
                        <div id="guest-rejoin" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">Déjà inscrit</h3>
                                            <p class="text-sm text-gray-600" id="guest-info"></p>
                                        </div>
                                    </div>
                                    <button onclick="clearGuestData()" 
                                        class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Changer
                                    </button>
                                </div>
                                
                                <button onclick="rejoinAsGuest()" id="rejoin-button"
                                    class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Rejoindre le live maintenant
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Bouton pour utilisateurs connectés -->
                    <div id="auth-join" class="{{ $liveClass->status !== 'live' ? 'hidden' : '' }}">
                        <button onclick="joinAsAuthenticatedUser()"
                            class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-play-circle mr-2"></i>
                            Rejoindre le live maintenant
                        </button>
                    </div>
                @endguest

                <!-- Message d'erreur -->
                <div id="error-message" class="hidden mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800 text-center" id="error-text"></p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                Connexion sécurisée via Jitsi Meet
            </p>
        </div>
    </div>

    <script>
        const liveClassToken = '{{ $liveClass->public_token }}';
        const liveClassStatus = '{{ $liveClass->status }}';
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const STORAGE_KEY = `live_class_${liveClassToken}`;

        // Fonctions de gestion du localStorage
        function getGuestData() {
            try {
                const data = localStorage.getItem(STORAGE_KEY);
                return data ? JSON.parse(data) : null;
            } catch (error) {
                console.error('Erreur lors de la lecture du localStorage:', error);
                return null;
            }
        }

        function saveGuestData(name, email, url) {
            try {
                const data = {
                    name: name,
                    email: email,
                    url: url,
                    timestamp: Date.now()
                };
                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            } catch (error) {
                console.error('Erreur lors de la sauvegarde dans le localStorage:', error);
            }
        }

        function clearGuestData() {
            try {
                localStorage.removeItem(STORAGE_KEY);
                // Recharger la page pour afficher le formulaire
                location.reload();
            } catch (error) {
                console.error('Erreur lors de la suppression du localStorage:', error);
            }
        }

        // Vérifier si l'invité a déjà rejoint
        function checkGuestStatus() {
            const guestData = getGuestData();
            
            if (guestData && guestData.url) {
                // L'invité a déjà rejoint, afficher le bouton de rejoin
                document.getElementById('guest-form').classList.add('hidden');
                document.getElementById('guest-rejoin').classList.remove('hidden');
                document.getElementById('guest-info').textContent = `${guestData.name} (${guestData.email})`;
            } else {
                // Nouvel invité, afficher le formulaire
                document.getElementById('guest-form').classList.remove('hidden');
                document.getElementById('guest-rejoin').classList.add('hidden');
            }
        }

        // Rejoindre avec le lien existant
        function rejoinAsGuest() {
            const guestData = getGuestData();
            
            if (guestData && guestData.url) {
                const button = document.getElementById('rejoin-button');
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Ouverture en cours...';
                
                // Ouvrir le lien Jitsi
                window.open(guestData.url, '_blank');
                
                // Réinitialiser le bouton après un court délai
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-play-circle mr-2"></i>Rejoindre le live maintenant';
                }, 1000);
            } else {
                showError('Lien de connexion non trouvé. Veuillez vous réinscrire.');
                clearGuestData();
            }
        }

        // Pour les invités non connectés
        @guest
        // Vérifier le statut au chargement de la page
        if (liveClassStatus === 'live') {
            checkGuestStatus();
        }

        document.getElementById('join-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('guest-name').value;
            const email = document.getElementById('guest-email').value;
            const submitButton = document.getElementById('join-button');

            // Désactiver le bouton
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Connexion en cours...';

            try {
                const response = await fetch(`/live-class/public/${liveClassToken}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name, email })
                });

                const data = await response.json();

                if (data.success && data.url) {
                    // Sauvegarder les données dans le localStorage
                    saveGuestData(name, email, data.url);
                    
                    // Ouvrir la salle Jitsi dans un nouvel onglet
                    window.open(data.url, '_blank');
                    
                    // Afficher le bouton de rejoin
                    submitButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Connexion réussie !';
                    submitButton.classList.remove('from-red-500', 'to-red-600');
                    submitButton.classList.add('from-green-500', 'to-green-600');
                    
                    // Mettre à jour l'interface après un court délai
                    setTimeout(() => {
                        checkGuestStatus();
                    }, 1500);
                } else {
                    showError(data.message || 'Erreur lors de la connexion');
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-play-circle mr-2"></i>Rejoindre le live maintenant';
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de la connexion au live');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-play-circle mr-2"></i>Rejoindre le live maintenant';
            }
        });
        @endguest

        // Pour les utilisateurs connectés
        @auth
        async function joinAsAuthenticatedUser() {
            const button = document.querySelector('#auth-join button');
            const originalHTML = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Connexion en cours...';

            try {
                const response = await fetch(`/live-class/public/${liveClassToken}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.url) {
                    // Ouvrir la salle Jitsi dans un nouvel onglet
                    window.open(data.url, '_blank');
                    
                    button.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Connexion réussie !';
                    button.classList.remove('from-red-500', 'to-red-600');
                    button.classList.add('from-green-500', 'to-green-600');
                } else {
                    showError(data.message || 'Erreur lors de la connexion');
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de la connexion au live');
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        }
        @endauth

        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
        }

        // Vérifier périodiquement le statut du live
        if (liveClassStatus === 'scheduled') {
            setInterval(async () => {
                try {
                    const response = await fetch(`/live-classes/public/${liveClassToken}/status`);
                    const data = await response.json();
                    
                    if (data.status === 'live') {
                        location.reload(); // Recharger la page si le live a démarré
                    }
                } catch (error) {
                    console.error('Erreur lors de la vérification du statut:', error);
                }
            }, 30000); // Vérifier toutes les 30 secondes
        }

        // Nettoyer le localStorage si le live est terminé
        if (liveClassStatus === 'ended') {
            clearGuestData();
        }
    </script>
</body>
</html>