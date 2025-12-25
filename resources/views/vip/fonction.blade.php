<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .notification-item {
            animation: slideIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-5">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-5">Notifications</h1>
        
        <!-- Notification mise en avant -->
        <div class="notification-item flex items-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg p-4 mb-4 cursor-pointer hover:from-indigo-600 hover:to-purple-700 transition-all relative group">
            <div class="w-10 h-10 rounded-full bg-white bg-opacity-30 flex items-center justify-center text-2xl mr-3 flex-shrink-0">
                🎉
            </div>
            <div class="flex-1">
                <p class="text-sm leading-relaxed">
                    <span class="font-semibold">Cher utilisateur,</span> 
                    <span>Nous avons mis en place de nouvelles fonctionnalités</span>
                </p>
                <p class="text-xs text-white text-opacity-80 mt-1">Allez les consulter !</p>
            </div>
            <span class="absolute right-4 text-2xl font-light opacity-70 group-hover:opacity-100 transition-opacity">›</span>
        </div>

        <!-- Notification standard -->
        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.05s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                JA
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Jordis AMEGAN</span> 
                    <span class="text-gray-600">Souhaite collaborer avec vous</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.1s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                JK
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Judith KOSSOU</span> 
                    <span class="text-gray-600">vous invite à rejoindre une session</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.15s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                PH
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Patrice HOUNGUE</span> 
                    <span class="text-gray-600">vous invite à rejoindre une session</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.2s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                JZ
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Josué ZINSALO</span> 
                    <span class="text-gray-600">vous souhaite la bienvenue</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.25s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                JT
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Jack TITOUAN</span> 
                    <span class="text-gray-600">vous invite à rejoindre une session</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.3s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                JK
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Judith KOSSOU</span> 
                    <span class="text-gray-600">vous invite à rejoindre une session</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.35s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-base mr-3 flex-shrink-0">
                EA
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">Edies ANASSOU</span> 
                    <span class="text-gray-600">vous invite à rejoindre une session</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 5 jours</p>
            </div>
        </div>

        <div class="notification-item flex items-center rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50 transition-colors" style="animation-delay: 0.4s;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-2xl mr-3 flex-shrink-0">
                📝
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-600 leading-relaxed">
                    Hey Peter, we've got a c.unt team is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500's...
                </p>
                <p class="text-xs text-gray-400 mt-1">Il y a 1 mois</p>
            </div>
        </div>
    </div>

    <script>
        // Gestion des clics sur les notifications
        document.querySelectorAll('.notification-item').forEach(notification => {
            notification.addEventListener('click', function() {
                console.log('Notification cliquée');
                
                // Animation de clic
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 100);
            });
        });
    </script>
</body>
</html>