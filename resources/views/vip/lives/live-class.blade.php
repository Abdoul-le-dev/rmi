<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VIP RMI - Live Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @include('vip/composants/css')
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- ========================================
         HEADER PRINCIPAL
    ======================================== -->
    @include('vip/composants/header')

    <!-- ========================================
         LAYOUT PRINCIPAL (3 COLONNES)
    ======================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- ========================================
                 SIDEBAR GAUCHE - NAVIGATION
            ======================================== -->
            @include('vip/composants/sidebar_gauche')

            <!-- ========================================
                 COLONNE CENTRALE - FEED
            ======================================== -->
            <main class="lg:col-span-9 main" id="menu_menu">

                <!-- Vue Liste des Lives -->
                <div id="live-classes-list-view">
                    <!-- Header avec filtres et bouton créer -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">Lives Classes</h1>
                                @if ($user->role->name == 'teacher')
                                    <p class="text-sm text-gray-600 mt-0.5">Gérez vos sessions de cours en direct</p>
                                @else
                                    <p class="text-sm text-gray-600 mt-0.5">Les sessions de cours en direct</p>
                                @endif
                            </div>
                            @if ($user->role->name == 'teacher')
                                <button onclick="showCreateLiveModal()"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-br from-[#7256B2] to-[#5E44A6] text-white text-sm font-semibold rounded-lg hover:from-[#5E44A6] hover:to-[#4D3795] transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Créer un Live
                                </button>
                            @endif
                        </div>

                        <!-- Filtres -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            <!-- Filtre Status -->
                            <div class="relative">
                                <select id="filter-status"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7256B2] focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending">En attente</option>
                                    <option value="scheduled" selected>À venir</option>
                                    <option value="live">En direct</option>
                                    <option value="ended">Terminé</option>
                                    <option value="cancelled">Annulé</option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>

                            <!-- Filtre Visibilité -->
                            <div class="relative">
                                <select id="filter-visibility"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7256B2] focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="">Toutes visibilités</option>
                                    <option value="public">Public</option>
                                    <option value="private">Privé</option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>

                            <!-- Filtre Formateurs -->
                            <div class="relative">
                                <select id="filter-ownership"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7256B2] focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="">Tous les instructeurs</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">
                                            {{ $instructor->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div id="user-avatar" data-avatar="{{ $user->getAvatar(48) }}" style='display:none;'></div>


                    <!-- Loading State -->
                    <div id="loading-state" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="animate-pulse">
                                <div class="bg-gray-200 rounded-xl h-72"></div>
                            </div>
                            <div class="animate-pulse">
                                <div class="bg-gray-200 rounded-xl h-72"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state"
                        class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-video text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun live trouvé</h3>
                            <p class="text-gray-600 mb-6">Les Lives s'afficheront ici</p>
                            @if ($user->role->name == 'teacher')
                                 <button onclick="showCreateLiveModal()"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-br from-[#7256B2] to-[#5E44A6] text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Créer mon premier Live
                            </button>
                            @endif
                           
                        </div>
                    </div>

                    <!-- Grid des Live Classes -->
                  
                    <div id="live-classes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                     
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-container" class="mt-8 flex justify-center">
                        
                    </div>
                </div>

                <!-- Vue Détails d'un Live -->
                <div id="live-class-detail-view" class="hidden">
                    <!-- Bouton retour -->
                    <button onclick="showListView()"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6 group transition-colors">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        <span class="font-medium">Retour aux lives</span>
                    </button>

                   
                    <div id="live-detail-content">
                       
                    </div>
                </div>

                <!-- Modal de création/édition -->
                <div id="live-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                        <div
                            class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900" id="modal-title">Créer un Live Class</h2>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form id="live-form" class="p-6 space-y-6">
                            <input type="hidden" id="live-id">

                            <!-- Titre -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Titre du live <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="live-title" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Ex: Introduction à Laravel">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="live-description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Décrivez le contenu de votre live..."></textarea>
                            </div>

                            <!-- Date et Heure -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="live-date" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Heure <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" id="live-time" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Durée et Participants -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Durée (minutes) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="live-duration" required min="15" value="60"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nombre max de participants
                                    </label>
                                    <input type="number" id="live-max-participants" min="1"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Illimité si vide">
                                </div>
                            </div>

                            <!-- Image de couverture -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Image de couverture
                                </label>
                                <div class="flex items-center gap-4">
                                    <input type="file" id="live-cover" accept="image/*" class="hidden">
                                    <button type="button" onclick="document.getElementById('live-cover').click()"
                                        class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-upload mr-2"></i>
                                        Choisir une image
                                    </button>
                                    <span id="cover-filename" class="text-sm text-gray-500">Aucun fichier
                                        sélectionné</span>
                                </div>
                                <div id="cover-preview" class="mt-4 hidden">
                                    <img src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="space-y-4 bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900">Options</h3>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" id="live-is-public"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <div>
                                        <p class="font-medium text-gray-900">Live public</p>
                                        <p class="text-sm text-gray-600">Accessible à tous via un lien</p>
                                    </div>
                                </label>


                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-3 pt-4">
                                <button type="button" onclick="closeModal()"
                                    class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-br from-[#7256B2] to-[#5E44A6] text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all">
                                    <i class="fas fa-save mr-2"></i>
                                    <span id="submit-text">Créer le live</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>

            <!-- ========================================
                 SIDEBAR DROITE - SUGGESTIONS
            ======================================== -->
            {{-- @include('vip/composants/sidebar_droite') --}}

        </div>
    </div>

    <!-- ========================================
         MENU MOBILE (Overlay)
    ======================================== -->
    @include('vip/composants/mobile')

    {{-- @include('vip/composants/js') --}}
    <script src="{{ asset('assets/vip/notification.js') }}" defer></script>

    <style>
        /* Animations personnalisées */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .live-card {
            animation: slideInUp 0.3s ease-out;
        }

        /* Badge status animé pour "live" */
        @keyframes pulse-live {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .status-live-badge {
            animation: pulse-live 2s ease-in-out infinite;
        }

        /* Effet de brillance au hover */
        .card-shine {
            position: relative;
            overflow: hidden;
        }

        .card-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }


        .card-shine:hover::before {
            left: 100%;
        }
    </style>

    <script>
        // ========================================
        // ÉTAT GLOBAL
        // ========================================
        let allLiveClasses = [];
        let filteredLiveClasses = [];
        let currentPage = 1;
        const itemsPerPage = 6;
        const currentUserId = {{ auth()->id() ?? 'null' }};
        const USER_AVATAR = getUserAvatar();


        // ========================================
        // INITIALISATION
        // ========================================
        document.addEventListener('DOMContentLoaded', function() {
            loadLiveClasses();
            setupFilterListeners();
            setupFormListeners();
        });

        // ========================================
        // CHARGEMENT DES DONNÉES
        // ========================================
        async function loadLiveClasses() {
            showLoading(true);

            try {
                const response = await fetch('/vip/live-classes', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur de chargement');

                const data = await response.json();
                allLiveClasses = data.data || data;
                filteredLiveClasses = [...allLiveClasses];

                // updateStats();
                applyFilters();

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Impossible de charger les lives', 'error');
            } finally {
                showLoading(false);
            }
        }

        // ========================================
        // AFFICHAGE DES CARDS
        // ========================================
        function renderLiveClasses() {
            const grid = document.getElementById('live-classes-grid');
            const emptyState = document.getElementById('empty-state');

            if (filteredLiveClasses.length === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            grid.classList.remove('hidden');
            emptyState.classList.add('hidden');

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedClasses = filteredLiveClasses.slice(startIndex, endIndex);

            grid.innerHTML = paginatedClasses.map(liveClass => createLiveCard(liveClass)).join('');
            renderPagination();
        }

        function createLiveCard(live) {
            const statusConfig = getStatusConfig(live.status);
            const isOwner = live.instructor_id === currentUserId;
            const scheduledDate = new Date(live.scheduled_at);
            const now = new Date();
            const isUpcoming = scheduledDate > now;
            const instructorPhoto = live.instructor?.avatar || '/default-avatar.png';

            return `
        <div class="live-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 card-shine cursor-pointer" onclick="showLiveDetail(${live.id})">
            <!-- Image de couverture -->
            <div class="relative h-40 bg-gradient-to-br from-[#7256B2] to-[#5E44A6] overflow-hidden">
                ${live.live_cover ? `
                        <img src="${live.url_path}" alt="${live.title}" class="w-full h-full object-cover">
                    ` : `
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-video text-5xl text-white/30"></i>
                        </div>
                    `}
                
                <!-- Badge de statut -->
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusConfig.class} ${live.status === 'live' ? 'status-live-badge' : ''} shadow-lg">
                        <i class="${statusConfig.icon} mr-1"></i>
                        ${statusConfig.label}
                    </span>
                </div>
                
                <!-- Badge de visibilité -->
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${live.is_public ? 'bg-green-500 text-white' : 'bg-gray-700 text-white'} shadow-lg">
                        <i class="fas ${live.is_public ? 'fa-globe' : 'fa-lock'}"></i>
                    </span>
                </div>
            </div>
            
            <!-- Contenu de la card -->
            <div class="p-3">
                <!-- Photo et nom du formateur + bouton notification -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <div class="w-7 h-7 rounded-full overflow-hidden border-2 border-gray-200 flex-shrink-0">
                            <img src="${USER_AVATAR}" alt="${live.instructor?.full_name || 'Instructeur'}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-900 truncate">${live.instructor?.full_name || 'Instructeur'}</p>
                            ${isOwner ? `
                                    <span class="text-[10px] text-blue-600">Mon live</span>
                                ` : ''}
                        </div>
                    </div>
                    
                    <!-- Bouton notification -->
                    ${true ? `
                            <button onclick="event.stopPropagation(); remindMe(${live.id})" 
                                    class="p-1.5 rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 transition-colors flex-shrink-0"
                                    title="Recevoir une notification">
                                <i class="fas fa-bell text-xs"></i>
                            </button>
                        ` : ''}
                </div>
                
                <!-- Titre -->
                <h3 class="text-sm font-bold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors leading-tight">
                    ${live.title}
                </h3>
                
                <!-- Informations -->
                <div class="space-y-1 mb-2">
                    <div class="flex items-center text-[11px] text-gray-700">
                        <i class="fas fa-calendar-alt w-3.5 text-gray-400 mr-1.5"></i>
                        <span class="font-medium truncate">${formatDate(live.scheduled_at)}</span>
                    </div>
                    
                    <div class="flex items-center text-[11px] text-gray-700">
                        <i class="fas fa-clock w-3.5 text-gray-400 mr-1.5"></i>
                        <span>${live.duration_minutes} min</span>
                    </div>
                    
                    <div class="flex items-center text-[11px] text-gray-700">
                        <i class="fas fa-users w-3.5 text-gray-400 mr-1.5"></i>
                        <span>${live.enrollments_count || 0}${live.max_participants ? `/${live.max_participants}` : ''} inscrit${(live.enrollments_count || 0) > 1 ? 's' : ''}</span>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex gap-1.5 pt-2 border-t border-gray-100">
                    <button onclick="event.stopPropagation(); showLiveDetail(${live.id})" 
                            class="flex-1 px-2.5 py-1.5 bg-[#7256B2] text-white text-xs font-semibold rounded-md hover:bg-[#5E44A6] transition-colors">
                        <i class="fas fa-info-circle mr-1"></i>
                        Détails
                    </button>
                    
                    ${isOwner && (live.status == 'pending' || live.status == 'scheduled') ? `
                            <button onclick="event.stopPropagation(); editLive(${live.id})" 
                                    class="px-2.5 py-1.5 bg-gray-100 text-gray-700 text-xs rounded-md hover:bg-gray-200 transition-colors"
                                    title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                        ` : ''}
                </div>
            </div>
        </div>
    `;
        }

        // ========================================
        //  FONCTIONS
        // ========================================

        function getStatusConfig(status) {
            const configs = {
                'pending': {
                    label: 'En attente',
                    class: 'bg-yellow-100 text-yellow-800',
                    icon: 'fas fa-hourglass-half'
                },
                'scheduled': {
                    label: 'A venir',
                    class: 'bg-blue-100 text-blue-800',
                    icon: 'fas fa-calendar-check'
                },
                'live': {
                    label: 'EN DIRECT',
                    class: 'bg-red-500 text-white',
                    icon: 'fas fa-circle'
                },
                'ended': {
                    label: 'Terminé',
                    class: 'bg-gray-100 text-gray-800',
                    icon: 'fas fa-check-circle'
                },
                'cancelled': {
                    label: 'Annulé',
                    class: 'bg-red-100 text-red-800',
                    icon: 'fas fa-times-circle'
                }
            };

            return configs[status] || configs['pending'];
        }

        // ========================================
        // FILTRES
        // ========================================
        function setupFilterListeners() {
            const filters = ['filter-status', 'filter-visibility', 'filter-ownership',
                // 'filter-date',
                //  'search-live'
            ];

            filters.forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element) {
                    element.addEventListener('change', applyFilters);
                    if (filterId === 'search-live') {
                        element.addEventListener('input', debounce(applyFilters, 300));
                    }
                }
            });
        }

        function applyFilters() {
            const statusFilter = document.getElementById('filter-status').value;
            const visibilityFilter = document.getElementById('filter-visibility').value;
            const ownershipFilter = document.getElementById('filter-ownership').value;


            filteredLiveClasses = allLiveClasses.filter(live => {
                if (statusFilter && live.status !== statusFilter) return false;
                if (visibilityFilter === 'public' && !live.is_public) return false;
                if (visibilityFilter === 'private' && live.is_public) return false;
                if (ownershipFilter && live.instructor_id != ownershipFilter) return false;


                return true;
            });

            currentPage = 1;
            renderLiveClasses();
        }

        function matchDateFilter(live, filter) {
            const scheduledDate = new Date(live.scheduled_at);
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            switch (filter) {
                case 'today':
                    return scheduledDate.toDateString() === today.toDateString();
                case 'week':
                    const weekFromNow = new Date(today);
                    weekFromNow.setDate(weekFromNow.getDate() + 7);
                    return scheduledDate >= today && scheduledDate <= weekFromNow;
                case 'month':
                    return scheduledDate.getMonth() === now.getMonth() &&
                        scheduledDate.getFullYear() === now.getFullYear();
                case 'upcoming':
                    return scheduledDate > now;
                case 'past':
                    return scheduledDate < now;
                default:
                    return true;
            }
        }

        // ========================================
        // STATISTIQUES
        // ========================================
        function updateStats() {
            document.getElementById('stat-total').textContent = allLiveClasses.length;
            document.getElementById('stat-live').textContent = allLiveClasses.filter(l => l.status === 'live').length;
            document.getElementById('stat-scheduled').textContent = allLiveClasses.filter(l => l.status === 'scheduled')
                .length;
            document.getElementById('stat-mine').textContent = allLiveClasses.filter(l => l.instructor_id === currentUserId)
                .length;
        }

        // ========================================
        // PAGINATION
        // ========================================
        function renderPagination() {
            const container = document.getElementById('pagination-container');
            const totalPages = Math.ceil(filteredLiveClasses.length / itemsPerPage);

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<div class="flex gap-2 items-center">';

            // Previous button
            html += `
        <button onclick="changePage(${currentPage - 1})" 
                ${currentPage === 1 ? 'disabled' : ''}
                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

            // Logique de pagination améliorée
            const maxVisiblePages = 3; // Nombre de pages visibles au centre
            let pagesToShow = [];

            if (totalPages <= 5) {
                // Si 5 pages ou moins, afficher toutes les pages
                for (let i = 1; i <= totalPages; i++) {
                    pagesToShow.push(i);
                }
            } else {
                // Toujours afficher la première page
                pagesToShow.push(1);

                // Déterminer les pages du milieu à afficher
                let startMiddle, endMiddle;

                if (currentPage <= 3) {
                    // Si on est au début (pages 1, 2, 3)
                    startMiddle = 2;
                    endMiddle = 3;
                } else if (currentPage >= totalPages - 2) {
                    // Si on est à la fin
                    startMiddle = totalPages - 2;
                    endMiddle = totalPages - 1;
                } else {
                    // Si on est au milieu
                    startMiddle = currentPage - 1;
                    endMiddle = currentPage + 1;
                }

                // Ajouter les points de suspension si nécessaire (début)
                if (startMiddle > 2) {
                    pagesToShow.push('...');
                }

                // Ajouter les pages du milieu
                for (let i = startMiddle; i <= endMiddle; i++) {
                    if (i > 1 && i < totalPages) {
                        pagesToShow.push(i);
                    }
                }

                // Ajouter les points de suspension si nécessaire (fin)
                if (endMiddle < totalPages - 1) {
                    pagesToShow.push('...');
                }

                // Toujours afficher la dernière page
                if (totalPages > 1) {
                    pagesToShow.push(totalPages);
                }
            }

            // Générer les boutons
            pagesToShow.forEach(page => {
                if (page === '...') {
                    html += '<span class="px-2 text-gray-500">...</span>';
                } else {
                    html += `
                <button onclick="changePage(${page})" 
                        class="px-4 py-2 rounded-lg border ${page === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 hover:bg-gray-50'} transition-colors">
                    ${page}
                </button>
            `;
                }
            });

            // Next button
            html += `
        <button onclick="changePage(${currentPage + 1})" 
                ${currentPage === totalPages ? 'disabled' : ''}
                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

            html += '</div>';
            container.innerHTML = html;
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredLiveClasses.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            renderLiveClasses();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ========================================
        // NAVIGATION VERS DÉTAILS
        // ========================================
        function showLiveDetail(liveId) {
            const live = allLiveClasses.find(l => l.id === liveId);
            if (!live) return;

            document.getElementById('live-classes-list-view').classList.add('hidden');
            document.getElementById('live-class-detail-view').classList.remove('hidden');

            renderLiveDetail(live);
        }

        function showListView() {
            document.getElementById('live-class-detail-view').classList.add('hidden');
            document.getElementById('live-classes-list-view').classList.remove('hidden');
        }

        function renderLiveDetail(live) {
            const isOwner = live.instructor_id === currentUserId;
            const statusConfig = getStatusConfig(live.status);
            const isEnrolled = live.is_enrolled || false;
            const canStart = live.can_start || false;
            const enrolledCount = live.enrollments_count || 0;
            const isFull = live.max_participants && enrolledCount >= live.max_participants;
            const fullUrl = `${window.location.origin}/live-class/public/${live.public_token}`;
            const displayUrl = shortenUrl(fullUrl);

            const detailHTML = `
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Image de couverture -->
            <div class="relative h-80 bg-gradient-to-br from-[#7256B2] to-[#5E44A6]">
                ${live.live_cover ? `
                                                    <img src="${live.url_path}" alt="${live.title}" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                                ` : `
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-video text-9xl text-white/20"></i>
                                                    </div>
                                                `}
                
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold ${statusConfig.class} ${live.status === 'live' ? 'status-live-badge' : ''}">
                            <i class="${statusConfig.icon} mr-1"></i>
                            ${statusConfig.label}
                        </span>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold ${live.is_public ? 'bg-green-500' : 'bg-gray-700'}">
                            <i class="fas ${live.is_public ? 'fa-globe' : 'fa-lock'} mr-1"></i>
                            ${live.is_public ? 'Public' : 'Privé'}
                        </span>
                        ${isEnrolled && !isOwner ? `
                                                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-blue-500">
                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                Inscrit
                                                            </span>
                                                        ` : ''}
                    </div>
                    <h1 class="text-4xl font-bold mb-2">${live.title}</h1>
                    <p class="text-lg text-gray-200">${live.description || 'Aucune description'}</p>
                </div>
            </div>
            
            <div class="p-4 lg:p-8">
                <!-- Tabs pour mobile -->
              <div class="lg:hidden mb-6">
    <div class="flex border-b border-gray-200">
        <button
            onclick="switchTab('info-tab')" id="btn-info-tab"   class="flex-1 px-1 py-3 text-xs font-semibold
                   text-blue-600 border-b-2 border-blue-600
                   transition-colors text-center  whitespace-nowrap">
            Informations
        </button>

        <button  onclick="switchTab('params-tab')" id="btn-params-tab"   class="flex-1 px-1 py-3 text-xs font-semibold
                   text-gray-600 border-b-2 border-transparent
                   hover:text-gray-900 transition-colors text-center
                   whitespace-nowrap">
            Paramètres
        </button>

        <button  onclick="switchTab('actions-tab')"  id="btn-actions-tab" class="flex-1 px-1 py-3 text-xs font-semibold  text-gray-600 border-b-2 border-transparent
                   hover:text-gray-900 transition-colors text-center
                   whitespace-nowrap">
            Actions
        </button>
    </div>
</div>


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne gauche: Informations et Paramètres -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Informations -->
                        <div id="info-tab" class="tab-content">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 hidden lg:block">Informations</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Date et heure</p>
                                        <p class="font-semibold text-gray-900">${formatDate(live.scheduled_at)}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Durée</p>
                                        <p class="font-semibold text-gray-900">${live.duration_minutes} minutes</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Instructeur</p>
                                        <p class="font-semibold text-gray-900">${live.instructor?.full_name || 'Non défini'}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-users text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Inscrits</p>
                                        <p class="font-semibold text-gray-900">
                                            ${enrolledCount}${live.max_participants ? ` / ${live.max_participants}` : ''}
                                            ${isFull ? ' <span class="text-red-600">(Complet)</span>' : ''}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paramètres -->
                        <div id="params-tab" class="tab-content hidden lg:block">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 hidden lg:block">Paramètres</h2>
                            <div class="space-y-3">
                                
                                
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-door-open text-gray-600"></i>
                                        <span class="font-medium text-gray-900">Salle</span>
                                    </div>
                                    <code class="px-3 py-1 bg-white rounded text-sm font-mono text-gray-700 border border-gray-200 truncate max-w-[150px]">
                                        ${live.room_name}
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite: Actions -->
                    <div id="actions-tab" class="tab-content hidden lg:block space-y-6">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                            <div class="space-y-3">
                                ${isOwner ? `
                                                                    <!-- ACTIONS POUR LE PROPRIÉTAIRE -->
                                                                    ${live.status === 'scheduled' ? `
                                        ${canStart ? `
                                                                            <button onclick="startLive(${live.id})" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 shadow-lg">
                                                                                <i class="fas fa-broadcast-tower mr-2"></i>
                                                                                Lancer le live
                                                                            </button>
                                                                        ` : `
                                                                            <div class="w-full px-6 py-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                                                                                <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                                                                                <p class="text-sm text-yellow-800 font-medium">Vous pourrez démarrer le live 15 minutes avant l'heure prévue</p>
                                                                            </div>
                                                                        `}
                                    ` : live.status === 'live' ? `
                                        <button onclick="joinLive(${live.id})" class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                                            <i class="fas fa-play-circle mr-2"></i>
                                            Rejoindre
                                        </button>
                                        
                                        <button onclick="endLive(${live.id})" class="w-full px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                                            <i class="fas fa-stop-circle mr-2"></i>
                                            Terminer
                                        </button>
                                    ` : ''}
                                                                    
                                                                    ${isOwner && (live.status == 'pending' || live.status == 'scheduled') ? `
                                        <button onclick="editLive(${live.id})" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Modifier
                                        </button>
                                    ` : ''}
                                                                    
                                                                    <button onclick="deleteLive(${live.id})" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                                                        <i class="fas fa-trash mr-2"></i>
                                                                        Supprimer
                                                                    </button>
                                                                ` : `
                                                                    <!-- ACTIONS POUR LES PARTICIPANTS -->
                                                                    ${live.status === 'live' ? `
                                        ${isEnrolled || live.is_public ? `
                                                                            <button onclick="joinLive(${live.id})" class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                                                                                <i class="fas fa-play-circle mr-2"></i>
                                                                                Rejoindre le live
                                                                            </button>
                                                                        ` : `
                                                                            <div class="w-full px-6 py-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center mb-3">
                                                                                <p class="text-sm text-yellow-800 font-medium">Vous devez être inscrit pour rejoindre ce live</p>
                                                                            </div>
                                                                            ${!isFull ? `
                                                <button onclick="enrollLive(${live.id})" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    S'inscrire maintenant
                                                </button>
                                            ` : `
                                                <div class="w-full px-6 py-4 bg-red-50 border border-red-200 rounded-lg text-center">
                                                    <i class="fas fa-users-slash text-red-600 text-2xl mb-2"></i>
                                                    <p class="text-sm text-red-800 font-medium">Ce live est complet</p>
                                                </div>
                                            `}
                                                                        `}
                                    ` : live.status === 'scheduled' ? `
                                        ${isEnrolled ? `
                                                                            <div class="w-full px-6 py-4 bg-green-50 border border-green-200 rounded-lg text-center mb-3">
                                                                                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                                                                                <p class="text-sm text-green-800 font-medium">Vous êtes déjà inscrit à ce live</p>
                                                                            </div>
                                                                            
                                                                            <button onclick="unenrollLive(${live.id})" class="w-full px-3 py-3 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                                                                <i class="fas fa-user-minus mr-2"></i>
                                                                                Se désinscrire
                                                                            </button>
                                                                        ` : `
                                                                            ${!isFull ? `
                                                <button onclick="enrollLive(${live.id})" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    S'inscrire
                                                </button>
                                            ` : `
                                                <div class="w-full px-6 py-4 bg-red-50 border border-red-200 rounded-lg text-center">
                                                    <i class="fas fa-users-slash text-red-600 text-2xl mb-2"></i>
                                                    <p class="text-sm text-red-800 font-medium">Ce live est complet</p>
                                                </div>
                                            `}
                                                                        `}
                                    ` : live.status === 'ended' ? `
                                        <div class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                                            <i class="fas fa-flag-checkered text-gray-600 text-2xl mb-2"></i>
                                            <p class="text-sm text-gray-800 font-medium">Ce live est terminé</p>
                                        </div>
                                    ` : ''}
                                                                `}
                            </div>
                        </div>
                        
                        <!-- Lien public -->
                        ${live.is_public && live.public_token ? `
                                                            <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                                                                <h3 class="font-bold text-gray-900 mb-3">Lien public</h3>
                                                                <div class="flex items-center gap-2">
                                                                    <input type="text" value="${displayUrl}" readonly
                                                                        class="flex-1 min-w-0 px-3 py-2 bg-white border border-green-200 rounded-lg text-sm truncate">
                                                                    <button onclick="copyPublicLink('${fullUrl}')"
                                                                        class="shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                                        <i class="fas fa-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;

            document.getElementById('live-detail-content').innerHTML = detailHTML;
        }

        /**
         * Gestion des tabs sur mobile
         */
        function switchTab(tabId) {
            // Masquer tous les contenus de tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
                tab.classList.remove('lg:block');
            });

            // Réinitialiser tous les boutons
            document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-600', 'border-transparent');
            });

            // Afficher le tab sélectionné
            const selectedTab = document.getElementById(tabId);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
                selectedTab.classList.add('block');
            }

            // Activer le bouton correspondant
            const selectedBtn = document.getElementById(`btn-${tabId}`);
            if (selectedBtn) {
                selectedBtn.classList.remove('text-gray-600', 'border-transparent');
                selectedBtn.classList.add('text-blue-600', 'border-blue-600');
            }
        }

        // ========================================
        // MODAL & FORMULAIRE
        // ========================================
        function setupFormListeners() {
            // Preview de l'image
            document.getElementById('live-cover')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('cover-filename').textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('cover-preview');
                        preview.querySelector('img').src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Soumission du formulaire
            document.getElementById('live-form')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                await saveLiveClass();
            });
        }

        function showCreateLiveModal() {
            // Réinitialiser complètement le formulaire d'abord
            document.getElementById('live-form').reset();
            document.getElementById('live-id').value = '';
            document.getElementById('cover-preview').classList.add('hidden');
            document.getElementById('cover-filename').textContent = 'Aucun fichier sélectionné';

            // Mettre à jour les textes du modal
            document.getElementById('modal-title').textContent = 'Créer un Live Class';
            document.getElementById('submit-text').textContent = 'Créer le live';

            // Afficher le modal
            document.getElementById('live-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('live-modal').classList.add('hidden');
            // Réinitialiser complètement le formulaire
            document.getElementById('live-form').reset();
            document.getElementById('live-id').value = '';
            document.getElementById('cover-preview').classList.add('hidden');
            document.getElementById('cover-filename').textContent = 'Aucun fichier sélectionné';

            // Réinitialiser le bouton submit au cas où il est en état de chargement
            const submitButton = document.querySelector('#live-form button[type="submit"]');
            if (submitButton && submitButton.dataset.originalHtml) {
                setButtonLoading(submitButton, false);
            }
        }

        /**
         * Sauvegarder un live (création/édition)
         */
        async function saveLiveClass() {
            // Récupérer le bouton submit du formulaire
            const submitButton = document.querySelector('#live-form button[type="submit"]');
            setButtonLoading(submitButton, true);

            const liveId = document.getElementById('live-id').value;
            const isEdit = liveId !== '';

            const formData = new FormData();
            formData.append('title', document.getElementById('live-title').value);
            formData.append('description', document.getElementById('live-description').value);

            const date = document.getElementById('live-date').value;
            const time = document.getElementById('live-time').value;
            formData.append('scheduled_at', `${date} ${time}`);

            formData.append('duration_minutes', document.getElementById('live-duration').value);
            formData.append('max_participants', document.getElementById('live-max-participants').value || '');
            formData.append('is_public', document.getElementById('live-is-public').checked ? '1' : '0');
            // formData.append('auto_record', document.getElementById('live-auto-record').checked ? '1' : '0');

            const coverFile = document.getElementById('live-cover').files[0];
            if (coverFile) {
                formData.append('live_cover', coverFile);
            }

            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            try {
                const url = isEdit ? `/vip/live-classes/${liveId}` : '/vip/live-classes';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Erreur lors de la sauvegarde');

                const responseData = await response.json();
                const savedLiveId = responseData.data?.id || liveId; // Récupérer l'ID du live sauvegardé

                showNotification(isEdit ? 'Live modifié avec succès' : 'Live créé avec succès', 'success');

                // Vérifier si on est sur la page de détails AVANT de fermer le modal
                const detailView = document.getElementById('live-class-detail-view');
                const isOnDetailView = !detailView.classList.contains('hidden');

                closeModal();
                await loadLiveClasses();

                // Si on était sur la page de détails, recharger les détails
                if (isOnDetailView && savedLiveId) {
                    showLiveDetail(savedLiveId);
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la sauvegarde', 'error');
                setButtonLoading(submitButton, false);
            }
        }

        // ========================================
        // ACTIONS
        // ========================================
        function editLive(liveId) {
            const live = allLiveClasses.find(l => l.id === liveId);
            if (!live) return;

            document.getElementById('live-modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Modifier le Live Class';
            document.getElementById('submit-text').textContent = 'Enregistrer les modifications';

            document.getElementById('live-id').value = live.id;
            document.getElementById('live-title').value = live.title;
            document.getElementById('live-description').value = live.description || '';

            const scheduledAt = new Date(live.scheduled_at);
            document.getElementById('live-date').value = scheduledAt.toISOString().split('T')[0];
            document.getElementById('live-time').value = scheduledAt.toTimeString().slice(0, 5);

            document.getElementById('live-duration').value = live.duration_minutes;
            document.getElementById('live-max-participants').value = live.max_participants || '';
            document.getElementById('live-is-public').checked = live.is_public;
            // document.getElementById('live-auto-record').checked = live.auto_record;

            if (live.live_cover) {
                const preview = document.getElementById('cover-preview');
                preview.querySelector('img').src = `${live.url_path}`;;
                preview.classList.remove('hidden');
                document.getElementById('cover-filename').textContent = 'Image actuelle';
            }
        }

        /**
         * Rejoindre un live en cours
         * Ouvre Jitsi dans un nouvel onglet avec JWT
         */
        async function joinLive(liveId) {
            const button = event.target.closest('button');
            setButtonLoading(button, true);
            try {
                const response = await fetch(`/vip/live-classes/${liveId}/join`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.url) {
                    showNotification(data.message, 'success');
                    // Ouvrir la salle Jitsi dans un nouvel onglet
                    window.open(data.url, '_blank');
                    // Recharger les données pour mettre à jour l'affichage
                    await loadLiveClasses();
                    // Si on est sur la page de détails, recharger les détails
                    const detailView = document.getElementById('live-class-detail-view');
                    if (!detailView.classList.contains('hidden')) {
                        showLiveDetail(liveId);
                    }
                } else {
                    showNotification(data.message || 'Erreur lors de la connexion', 'error');
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la connexion au live', 'error');
                setButtonLoading(submitButton, false);
            }
        }

        /**
         * Démarrer un live (Instructeur uniquement)
         * Ouvre Jitsi dans un nouvel onglet avec JWT
         */
        async function startLive(liveId) {
            const button = window.event?.target?.closest('button');
            const confirmed = await showConfirmModal({
                title: 'Démarrer le live',
                message: 'Voulez-vous vraiment démarrer ce live maintenant ?',
                confirmText: 'Démarrer',
                cancelText: 'Annuler',
                confirmClass: 'bg-green-600 hover:bg-green-700',
                icon: 'fa-broadcast-tower',
                iconColor: 'text-green-600'
            });
            if (!confirmed) return;

            setButtonLoading(button, true);
            try {
                const response = await fetch(`/vip/live-classes/${liveId}/start`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.url) {
                    showNotification(data.message, 'success');
                    // Ouvrir la salle Jitsi dans un nouvel onglet
                    window.open(data.url, '_blank');
                    // Recharger les données pour mettre à jour le statut
                    await loadLiveClasses();
                    // Si on est sur la page de détails, recharger les détails
                    const detailView = document.getElementById('live-class-detail-view');
                    if (!detailView.classList.contains('hidden')) {
                        showLiveDetail(liveId);
                    }
                } else {
                    showNotification(data.message || 'Erreur lors du démarrage', 'error');
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors du démarrage du live', 'error');
                setButtonLoading(submitButton, false);
            }
        }


        async function endLive(liveId) {
            const button = window.event?.target?.closest('button');
            const confirmed = await showConfirmModal({
                title: 'Terminer le live',
                message: 'Voulez-vous vraiment terminer ce live ?',
                confirmText: 'Terminer',
                cancelText: 'Annuler',
                confirmClass: 'bg-orange-600 hover:bg-orange-700',
                icon: 'fa-stop-circle',
                iconColor: 'text-orange-600'
            });

            if (!confirmed) return;

            setButtonLoading(button, true);

            try {
                const response = await fetch(`/vip/live-classes/${liveId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Recharger les données pour mettre à jour le statut
                    await loadLiveClasses();
                    // Si on est sur la page de détails, recharger les détails
                    const detailView = document.getElementById('live-class-detail-view');
                    if (!detailView.classList.contains('hidden')) {
                        showLiveDetail(liveId);
                    }
                } else {
                    showNotification(data.message || 'Erreur lors de la fin du live', 'error');
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la fin du live', 'error');
                setButtonLoading(submitButton, false);
            }
        }

        async function deleteLive(liveId) {
            const button = window.event?.target?.closest('button');
            const confirmed = await showConfirmModal({
                title: 'Supprimer le live',
                message: 'Voulez-vous vraiment supprimer ce live ? Cette action est irréversible.',
                confirmText: 'Supprimer',
                cancelText: 'Annuler',
                confirmClass: 'bg-red-600 hover:bg-red-700',
                icon: 'fa-trash',
                iconColor: 'text-red-600'
            });

            if (!confirmed) return;


            setButtonLoading(button, true);

            try {
                const response = await fetch(`/vip/live-classes/${liveId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur');

                showNotification('Live supprimé avec succès', 'success');
                showListView();
                loadLiveClasses();

            } catch (error) {
                showNotification('Erreur lors de la suppression', 'error');
                setButtonLoading(submitButton, false);
            }
        }




        function copyPublicLink(link) {
            copyToClipboard(link);
        }
        /**
         * S'inscrire à un live
         */
        async function enrollLive(liveId) {
            try {
                const button = event.target.closest('button');
                setButtonLoading(button, true);
                const response = await fetch(`/vip/live-classes/${liveId}/enroll`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Recharger les données pour mettre à jour l'affichage
                    await loadLiveClasses();
                    // Si on est sur la page de détails, recharger les détails
                    const detailView = document.getElementById('live-class-detail-view');
                    if (!detailView.classList.contains('hidden')) {
                        showLiveDetail(liveId);
                    }
                } else {
                    showNotification(data.message, 'error');
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de l\'inscription', 'error');
                setButtonLoading(submitButton, false);
            }
        }

        /**
         * Se désinscrire d'un live
         */
        async function unenrollLive(liveId) {
            const button = window.event?.target?.closest('button');
            const confirmed = await showConfirmModal({
                title: 'Se désinscrire',
                message: 'Voulez-vous vraiment vous désinscrire de ce live ?',
                confirmText: 'Se désinscrire',
                cancelText: 'Annuler',
                confirmClass: 'bg-red-600 hover:bg-red-700',
                icon: 'fa-user-minus',
                iconColor: 'text-red-600'
            });

            if (!confirmed) return;

            setButtonLoading(button, true);
            try {
                const response = await fetch(`/vip/live-classes/${liveId}/unenroll`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Recharger les données pour mettre à jour l'affichage
                    await loadLiveClasses();
                    // Si on est sur la page de détails, recharger les détails
                    const detailView = document.getElementById('live-class-detail-view');
                    if (!detailView.classList.contains('hidden')) {
                        showLiveDetail(liveId);
                    }
                } else {
                    showNotification(data.message, 'error');
                }

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la désinscription', 'error');
                setButtonLoading(submitButton, false);
            }
        }

        /**
         * Créer un rappel dans le calendrier
         */
        function remindMe(liveId) {
            const live = allLiveClasses.find(l => l.id === liveId);
            if (!live) {
                showNotification('Live non trouvé', 'error');
                return;
            }

            // Formater les dates pour le calendrier
            const startDate = new Date(live.scheduled_at);
            const endDate = new Date(startDate.getTime() + live.duration_minutes * 60000);

            // Formater les dates au format iCal (YYYYMMDDTHHMMSSZ)
            const formatICalDate = (date) => {
                return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
            };

            const startFormatted = formatICalDate(startDate);
            const endFormatted = formatICalDate(endDate);

            // Créer la description
            const description = live.description || 'Live class';
            const location = 'Plateforme  RMI CLASS';

            // Détecter le système
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isAndroid = /Android/.test(navigator.userAgent);

            if (isIOS) {
                // Pour iOS - Créer un fichier .ics
                const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//VIP RMI//Live Classes//FR
BEGIN:VEVENT
UID:${liveId}@viprmi.com
DTSTAMP:${formatICalDate(new Date())}
DTSTART:${startFormatted}
DTEND:${endFormatted}
SUMMARY:${live.title}
DESCRIPTION:${description}
LOCATION:${location}
STATUS:CONFIRMED
BEGIN:VALARM
TRIGGER:-PT15M
ACTION:DISPLAY
DESCRIPTION:Le live commence dans 15 minutes
END:VALARM
END:VEVENT
END:VCALENDAR`;

                // Créer un blob et télécharger
                const blob = new Blob([icsContent], {
                    type: 'text/calendar;charset=utf-8'
                });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `live-${liveId}.ics`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                showNotification('Fichier calendrier téléchargé. Ouvrez-le pour ajouter l\'événement.', 'success');
            } else if (isAndroid) {
                // Pour Android - Utiliser Google Calendar
                const googleCalendarUrl =
                    `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(live.title)}&dates=${startFormatted}/${endFormatted}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;

                window.open(googleCalendarUrl, '_blank');
                showNotification('Redirection vers Google Calendar', 'success');
            } else {
                // Pour Desktop - Proposer Google Calendar et téléchargement .ics
                const googleCalendarUrl =
                    `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(live.title)}&dates=${startFormatted}/${endFormatted}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;

                // Créer un modal de choix
                const modalHTML = `
            <div id="calendar-modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-calendar-plus text-blue-600 mr-2"></i>
                        Ajouter au calendrier
                    </h3>
                    <p class="text-gray-600 mb-6">Choisissez comment vous souhaitez ajouter cet événement :</p>
                    
                    <div class="space-y-3">
                        <button onclick="openGoogleCalendar('${googleCalendarUrl}')" 
                                class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fab fa-google"></i>
                            Google Calendar
                        </button>
                        
                        <button onclick="downloadICS(${liveId})" 
                                class="w-full px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i>
                            Télécharger (.ics)
                        </button>
                        
                        <button onclick="closeCalendarModal()" 
                                class="w-full px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        `;

                // Ajouter le modal au DOM
                const modalContainer = document.createElement('div');
                modalContainer.innerHTML = modalHTML;
                document.body.appendChild(modalContainer.firstElementChild);
            }
        }

        /**
         * Ouvrir Google Calendar
         */
        function openGoogleCalendar(url) {
            window.open(url, '_blank');
            closeCalendarModal();
            showNotification('Ouverture de Google Calendar', 'success');
        }

        /**
         * Télécharger le fichier .ics
         */
        function downloadICS(liveId) {
            const live = allLiveClasses.find(l => l.id === liveId);
            if (!live) return;

            const startDate = new Date(live.scheduled_at);
            const endDate = new Date(startDate.getTime() + live.duration_minutes * 60000);

            const formatICalDate = (date) => {
                return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
            };

            const startFormatted = formatICalDate(startDate);
            const endFormatted = formatICalDate(endDate);

            const description = live.description || 'Live class';
            const location = live.is_public && live.public_token ?
                `${window.location.origin}/live-class/public/${live.public_token}` :
                'Plateforme RMI CLASS';

            const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//VIP RMI//Live Classes//FR
BEGIN:VEVENT
UID:${liveId}@viprmi.com
DTSTAMP:${formatICalDate(new Date())}
DTSTART:${startFormatted}
DTEND:${endFormatted}
SUMMARY:${live.title}
DESCRIPTION:${description}
LOCATION:${location}
STATUS:CONFIRMED
BEGIN:VALARM
TRIGGER:-PT15M
ACTION:DISPLAY
DESCRIPTION:Le live commence dans 15 minutes
END:VALARM
END:VEVENT
END:VCALENDAR`;

            const blob = new Blob([icsContent], {
                type: 'text/calendar;charset=utf-8'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `live-${live.title.replace(/[^a-z0-9]/gi, '-').toLowerCase()}.ics`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            closeCalendarModal();
            showNotification('Fichier calendrier téléchargé', 'success');
        }

        /**
         * Fermer le modal de choix de calendrier
         */
        function closeCalendarModal() {
            const modal = document.getElementById('calendar-modal');
            if (modal) {
                modal.remove();
            }
        }

        // ========================================
        // UTILITAIRES
        // ========================================

        function getUserAvatar() {
            const avatarEl = document.getElementById('user-avatar');
            return avatarEl ? avatarEl.dataset.avatar : null;
        }

        function showConfirmModal(options = {}) {
            return new Promise((resolve) => {
                const {
                    title = 'Confirmation',
                        message = 'Êtes-vous sûr de vouloir continuer ?',
                        confirmText = 'Confirmer',
                        cancelText = 'Annuler',
                        confirmClass = 'bg-red-600 hover:bg-red-700',
                        icon = 'fa-exclamation-triangle',
                        iconColor = 'text-red-600'
                } = options;

                // Supprimer un éventuel modal existant
                const existingModal = document.getElementById('confirm-modal');
                if (existingModal) {
                    existingModal.remove();
                }

                const modalHTML = `
            <div id="confirm-modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-scale-in">
                    <!-- Header avec icône -->
                    <div class="p-6 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas ${icon} ${iconColor} text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">${title}</h3>
                        <p class="text-gray-600">${message}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-3 px-6 pb-6">
                        <button id="confirm-cancel-btn" 
                                class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            ${cancelText}
                        </button>
                        <button id="confirm-ok-btn" 
                                class="flex-1 px-6 py-3 ${confirmClass} text-white font-semibold rounded-lg transition-colors">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        `;

                // Ajouter les animations CSS si elles n'existent pas
                if (!document.getElementById('confirm-modal-styles')) {
                    const style = document.createElement('style');
                    style.id = 'confirm-modal-styles';
                    style.textContent = `
                @keyframes fade-in {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes scale-in {
                    from { 
                        opacity: 0;
                        transform: scale(0.95);
                    }
                    to { 
                        opacity: 1;
                        transform: scale(1);
                    }
                }
                .animate-fade-in {
                    animation: fade-in 0.2s ease-out;
                }
                .animate-scale-in {
                    animation: scale-in 0.3s ease-out;
                }
            `;
                    document.head.appendChild(style);
                }

                // Ajouter le modal au DOM
                const modalContainer = document.createElement('div');
                modalContainer.innerHTML = modalHTML;
                document.body.appendChild(modalContainer.firstElementChild);

                const modal = document.getElementById('confirm-modal');
                const confirmBtn = document.getElementById('confirm-ok-btn');
                const cancelBtn = document.getElementById('confirm-cancel-btn');

                // Fonction pour fermer le modal
                const closeModal = (confirmed) => {
                    modal.classList.add('opacity-0');
                    setTimeout(() => {
                        modal.remove();
                        resolve(confirmed);
                    }, 200);
                };

                // Événements
                confirmBtn.addEventListener('click', () => closeModal(true));
                cancelBtn.addEventListener('click', () => closeModal(false));

                // Fermer en cliquant à l'extérieur
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(false);
                    }
                });

                // Fermer avec la touche Échap
                const escHandler = (e) => {
                    if (e.key === 'Escape') {
                        closeModal(false);
                        document.removeEventListener('keydown', escHandler);
                    }
                };
                document.addEventListener('keydown', escHandler);
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);

            return date.toLocaleString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',

            });
        }

        function showLoading(show) {
            const loading = document.getElementById('loading-state');
            const grid = document.getElementById('live-classes-grid');

            if (show) {
                loading.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                loading.classList.add('hidden');
            }
        }

        function shortenUrl(url, start = 25, end = 8) {
            if (!url || url.length <= start + end) return url;

            return `${url.slice(0, start)}...${url.slice(-end)}`;
        }

        function showNotification(message, type = 'info', duration = 5000) {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
                document.body.appendChild(toastContainer);
            }

            const types = {
                success: {
                    bg: 'bg-green-500',
                    icon: '✓',
                    iconBg: 'bg-green-600',
                    progressBg: 'bg-green-300'
                },
                error: {
                    bg: 'bg-red-500',
                    icon: '✕',
                    iconBg: 'bg-red-600',
                    progressBg: 'bg-red-300'
                },
                warning: {
                    bg: 'bg-yellow-500',
                    icon: '⚠',
                    iconBg: 'bg-yellow-600',
                    progressBg: 'bg-yellow-300'
                },
                info: {
                    bg: 'bg-blue-500',
                    icon: 'ℹ',
                    iconBg: 'bg-blue-600',
                    progressBg: 'bg-blue-300'
                }
            };

            const config = types[type] || types.info;

            const toast = document.createElement('div');
            toast.className =
                `${config.bg} text-white rounded-lg shadow-lg min-w-[300px] max-w-md transform transition-all duration-300 translate-x-[400px] opacity-0 overflow-hidden`;

            toast.innerHTML = `
        <div class="px-4 py-3 flex items-center gap-3">
            <div class="${config.iconBg} w-8 h-8 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                ${config.icon}
            </div>
            <p class="flex-1 text-sm font-medium">${message}</p>
            <button class="text-white hover:text-gray-200 transition-colors ml-2 close-toast">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="h-1 ${config.progressBg} progress-bar" style="animation: shrink ${duration}ms linear;"></div>
    `;

            // Ajouter le CSS pour l'animation de la barre de progression
            if (!document.getElementById('toast-styles')) {
                const style = document.createElement('style');
                style.id = 'toast-styles';
                style.textContent = `
            @keyframes shrink {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
                document.head.appendChild(style);
            }

            toastContainer.appendChild(toast);

            // Bouton de fermeture
            toast.querySelector('.close-toast').addEventListener('click', () => {
                toast.classList.add('translate-x-[400px]', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            });

            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('translate-x-[400px]', 'opacity-0');
            }, 10);

            // Auto-suppression
            setTimeout(() => {
                toast.classList.add('translate-x-[400px]', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // ========================================
        // GESTION DU CHARGEMENT DES BOUTONS
        // ========================================
        function setButtonLoading(button, loading = true) {
            if (loading) {
                button.dataset.originalHtml = button.innerHTML;
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');

                // Remplacer le contenu par un spinner
                const icon = '<i class="fas fa-spinner fa-spin mr-2"></i>';
                const text = button.textContent.trim();
                button.innerHTML = icon + (text || 'Chargement...');
            } else {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');

                // Restaurer le contenu original
                if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                    delete button.dataset.originalHtml;
                }
            }
        }



        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Lien copié dans le presse-papier !', 'success');
            }).catch(() => {
                showNotification('Erreur lors de la copie', 'error');
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</body>

</html>
