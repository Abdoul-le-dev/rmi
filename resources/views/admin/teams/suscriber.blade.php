@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">suscriber</div>
            </div>
        </div>


        <div class="min-h-screen p-8">
        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button id="tabIndividual" class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition flex items-center justify-center gap-2 text-indigo-600 border-b-2 border-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Ajout Individuel
                    </button>
                    <button id="tabBulk" class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition flex items-center justify-center gap-2 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Ajout en Masse
                    </button>
                </div>
            </div>

            <!-- Content Ajout Individuel -->
            <div id="individualContent" class="p-8">
                <div class="space-y-8">
                    <!-- Recherche par email -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Email de l'étudiant</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input id="emailSearch" type="email" placeholder="Rechercher par email..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-800">

                               
                            </div>
                        </div>
                    </div>

                   <!-- Info étudiant trouvé -->
                    <div id="studentInfo" class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-6 border border-indigo-200 hidden">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div id="studentAvatar" class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                ?
                            </div>

                            <div class="flex-1">
                                <!-- Nom + email -->
                                <h3 id="studentName" class="font-bold text-gray-900 text-lg">Utilisateur inconnu</h3>
                                <p id="studentEmail" class="text-sm text-gray-600">email@example.com</p>

                                <!-- Ligne 1 : rôle, statut, connexions -->
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span id="studentRole" class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-full font-medium">
                                        Rôle: user
                                    </span>
                                    <span id="studentStatus" class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-medium">
                                        Statut: Actif
                                    </span>
                                    <span id="studentLogins" class="text-xs text-gray-500">
                                        Connexions: 0
                                    </span>
                                </div>

                                <!-- Ligne 2 : fuseau horaire, date de création -->
                                <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-gray-500">
                                    <span id="studentTimezone">Fuseau horaire: -</span>
                                    <span id="studentCreatedAt">Inscrit le: -</span>
                                </div>

                                <!-- Ligne 3 : abonnement (si tu veux garder l’idée) -->
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span id="currentSub" class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-medium hidden">
                                        Abonnement actuel: -
                                    </span>
                                    <span id="expiryInfo" class="text-xs text-gray-500 hidden">
                                        Expire dans - jours
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sélection de la durée -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Durée de l'abonnement à ajouter</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <button class="duration-btn p-6 rounded-xl border-2 border-gray-200 hover:border-indigo-300 bg-white transition-all" data-days="30">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-800">30</div>
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </button>
                            <button class="duration-btn p-6 rounded-xl border-2 border-gray-200 hover:border-indigo-300 bg-white transition-all" data-days="60">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-800">60</div>
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </button>
                            <button class="duration-btn p-6 rounded-xl border-2 border-gray-200 hover:border-indigo-300 bg-white transition-all" data-days="90">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-800">90</div>
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </button>
                            <div class="p-6 rounded-xl border-2 border-gray-200 bg-white transition-all">
                                <div class="text-center">
                                    <input id="customDays" type="number" min="1" placeholder="Autre" class="w-full text-3xl font-bold text-center bg-transparent border-none outline-none text-gray-800 placeholder-gray-400">
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'action -->
                    <div class="flex justify-end">
                        <button id="addSubBtn" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition font-medium shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter l'abonnement
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Ajout en Masse -->
            <div id="bulkContent" class="p-8 hidden">
                <div class="space-y-8">
                    <!-- Filtre par abonnement -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-700">Filtrer par abonnement actuel</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button class="filter-btn active px-4 py-3 rounded-lg border-2 border-indigo-600 bg-indigo-50 text-indigo-700 font-medium text-sm transition" data-filter="all">
                                Tous les étudiants
                            </button>
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="1">
                                Abonnés 1 mois
                            </button>
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="2">
                                Abonnés 2 mois
                            </button>
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="3">
                                Abonnés 3 mois
                            </button>
                        </div>
                    </div>

                    <!-- Durée à ajouter -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Durée à ajouter</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button class="bulk-duration-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-days="30">
                                30 jours
                            </button>
                            <button class="bulk-duration-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-days="60">
                                60 jours
                            </button>
                            <button class="bulk-duration-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-days="90">
                                90 jours
                            </button>
                            <input id="bulkCustomDays" type="number" min="1" placeholder="Personnalisé..." class="px-4 py-3 rounded-lg border-2 border-gray-200 text-gray-700 font-medium text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition">
                        </div>
                    </div>

                    <!-- Liste des étudiants -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                        <span id="selectedCount">0</span>
                                    </div>
                                    <span class="text-sm text-gray-600">étudiant(s) sélectionné(s)</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button id="selectAllBtn" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tout sélectionner
                                </button>
                                <span class="text-gray-300">|</span>
                                <button id="deselectAllBtn" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tout désélectionner
                                </button>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-2">
                                Liste des étudiants (<span id="listCount">0</span>)
                            </div>
                        </div>

                        <div id="studentsList" class="space-y-2 max-h-96 overflow-y-auto"></div>
                    </div>

                    <!-- Bouton d'action -->
                    <div class="flex justify-end gap-3">
                        <button id="cancelBulkBtn" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Annuler la sélection
                        </button>
                        <button id="applyBulkBtn" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition font-medium shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Appliquer aux étudiants
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div id="notification" class="hidden top-8 right-8 bg-white rounded-lg shadow-lg p-4 border-l-4 border-green-500 transform translate-x-full transition-transform duration-300 max-w-md">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Abonnement ajouté</p>
                    <p id="notificationText" class="text-sm text-gray-600"></p>
                </div>
            </div>
        </div>

        <script>
            // Données simulées
            const students = {
                'jean.dupont@email.com': { name: 'Jean Dupont', currentSub: '1 mois', expiry: 15, subMonths: 1 },
                'marie.martin@email.com': { name: 'Marie Martin', currentSub: '3 mois', expiry: 45, subMonths: 3 },
                'pierre.bernard@email.com': { name: 'Pierre Bernard', currentSub: '2 mois', expiry: 35, subMonths: 2 },
                'sophie.dubois@email.com': { name: 'Sophie Dubois', currentSub: '1 mois', expiry: 20, subMonths: 1 },
                'lucas.moreau@email.com': { name: 'Lucas Moreau', currentSub: '3 mois', expiry: 60, subMonths: 3 },
                'emma.leroy@email.com': { name: 'Emma Leroy', currentSub: '2 mois', expiry: 40, subMonths: 2 }
            };

            let selectedDuration = null;
            let currentStudent = null;
            
            // Variables pour l'ajout en masse
            let bulkFilter = 'all';
            let bulkDuration = null;
            let selectedStudents = new Set();

            // Gestion des onglets
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.tab-btn').forEach(b => {
                        b.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                        b.classList.add('text-gray-600');
                    });
                    e.currentTarget.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
                    e.currentTarget.classList.remove('text-gray-600');
                    
                    if (e.currentTarget.id === 'tabIndividual') {
                        document.getElementById('individualContent').classList.remove('hidden');
                        document.getElementById('bulkContent').classList.add('hidden');
                    } else {
                        document.getElementById('individualContent').classList.add('hidden');
                        document.getElementById('bulkContent').classList.remove('hidden');
                    }
                });
            });

            // Élément global de la fiche étudiant
            const info = document.getElementById('studentInfo');
           

            // Recherche d'étudiant
            document.getElementById('emailSearch').addEventListener('input', async (e) => {
                const email = e.target.value.toLowerCase().trim();

                // Si l'input est vide, on cache la fiche
                if (!email) {
                    info.classList.add('hidden');
                    currentStudent = null;
                    updateAddButton();
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]').content;

                try {
                    const response = await fetch('/admin_d_fiacre/suscriber', {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({ email })
                    });

                    const json = await response.json();

                    // Adapte ici si ta réponse n'est pas exactement { user: { data: [...] } }
                    const student = json?.user?.data?.[0] ?? null;

                    alert(student);
                    // Juste pour debug si tu veux
                    // alert(JSON.stringify(student, null, 2));

                    if (student) {
                        // Construire un nom propre
                        const displayNameRaw = (student.full_name && student.full_name.trim()) || '';
                        const displayName = displayNameRaw || student.email || `Utilisateur #${student.id}`;

                        // Initiales (évite l'erreur split sur undefined)
                        const initials = displayName
                            .trim()
                            .split(/\s+/)
                            .map(part => part[0])
                            .join('')
                            .slice(0, 2)
                            .toUpperCase();

                        // Avatar
                        const avatarEl = document.getElementById('studentAvatar');
                        avatarEl.textContent = initials || '?';

                        // Couleurs de l’avatar si disponible
                        if (student.avatar_settings) {
                            try {
                                const avatarSettings = JSON.parse(student.avatar_settings);
                                if (avatarSettings.background) {
                                    avatarEl.style.background = `#${avatarSettings.background}`;
                                }
                                if (avatarSettings.color) {
                                    avatarEl.style.color = `#${avatarSettings.color}`;
                                }
                            } catch (err) {
                                console.warn('avatar_settings invalide', err);
                            }
                        }

                        // Remplissage des champs texte
                        document.getElementById('studentName').textContent = displayName;
                        document.getElementById('studentEmail').textContent = student.email || '-';

                        document.getElementById('studentRole').textContent =
                            `Rôle: ${student.role_name || 'user'}`;

                        document.getElementById('studentStatus').textContent =
                            `Statut: ${student.status === 'active' ? 'Actif' : (student.status || '-')}`;

                        document.getElementById('studentLogins').textContent =
                            `Connexions: ${student.logged_count ?? 0}`;

                        document.getElementById('studentTimezone').textContent =
                            `Fuseau horaire: ${student.timezone || '-'}`;

                        // Date d'inscription
                        const createdAtEl = document.getElementById('studentCreatedAt');
                        if (student.created_at) {
                            const date = new Date(student.created_at * 1000); // timestamp en secondes
                            createdAtEl.textContent = `Inscrit le: ${date.toLocaleDateString('fr-FR')}`;
                        } else {
                            createdAtEl.textContent = 'Inscrit le: -';
                        }

                        // Gestion abonnement si dispo
                        const currentSubEl = document.getElementById('currentSub');
                        const expiryInfoEl = document.getElementById('expiryInfo');

                        if (student.currentSub) {
                            currentSubEl.textContent = `Abonnement actuel: ${student.currentSub}`;
                            currentSubEl.classList.remove('hidden');
                        } else {
                            currentSubEl.classList.add('hidden');
                        }

                        if (student.expiry) {
                            expiryInfoEl.textContent = `Expire dans ${student.expiry} jours`;
                            expiryInfoEl.classList.remove('hidden');
                        } else {
                            expiryInfoEl.classList.add('hidden');
                        }

                        // Stocker l’étudiant actuel pour d’autres actions (bouton "ajouter")
                        currentStudent = { email, ...student };

                        info.classList.remove('hidden');
                    } else {
                        info.classList.add('hidden');
                        currentStudent = null;
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération du student', error);
                    info.classList.add('hidden');
                    currentStudent = null;
                }

                // Mise à jour de ton bouton (ou autre logique)
                updateAddButton();
            });


            // Sélection de durée
            document.querySelectorAll('.duration-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.duration-btn').forEach(b => {
                        b.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'text-white', 'border-transparent', 'shadow-lg', 'scale-105');
                        b.classList.add('border-gray-200', 'bg-white');
                    });
                    e.currentTarget.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'text-white', 'border-transparent', 'shadow-lg', 'scale-105');
                    e.currentTarget.classList.remove('border-gray-200', 'bg-white');
                    
                    selectedDuration = parseInt(e.currentTarget.dataset.days);
                    document.getElementById('customDays').value = '';
                    updateAddButton();
                });
            });

            // Durée personnalisée
            document.getElementById('customDays').addEventListener('input', (e) => {
                if (e.target.value) {
                    document.querySelectorAll('.duration-btn').forEach(b => {
                        b.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'text-white', 'border-transparent', 'shadow-lg', 'scale-105');
                        b.classList.add('border-gray-200', 'bg-white');
                    });
                    selectedDuration = parseInt(e.target.value);
                } else {
                    selectedDuration = null;
                }
                updateAddButton();
            });

            

            // Mise à jour du bouton
            function updateAddButton() {
                const btn = document.getElementById('addSubBtn');
                btn.disabled = !currentStudent || !selectedDuration;
            }

            // Ajout d'abonnement
            document.getElementById('addSubBtn').addEventListener('click', () => {
                if (currentStudent && selectedDuration) {
                    showNotification(`${selectedDuration} jours ajoutés à ${currentStudent.name}`);
                    
                    // Réinitialiser
                    document.getElementById('emailSearch').value = '';
                    document.getElementById('studentInfo').classList.add('hidden');
                    document.getElementById('customDays').value = '';
                    document.querySelectorAll('.duration-btn').forEach(b => {
                        b.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'text-white', 'border-transparent', 'shadow-lg', 'scale-105');
                        b.classList.add('border-gray-200', 'bg-white');
                    });
                    selectedDuration = null;
                    currentStudent = null;
                    updateAddButton();
                }
            });

            // Notification
            function showNotification(text) {
                const notif = document.getElementById('notification');
                document.getElementById('notificationText').textContent = text;
                notif.classList.remove('translate-x-full');
                setTimeout(() => {
                    notif.classList.add('translate-x-full');
                }, 3000);
            }

            // ===== AJOUT EN MASSE =====
            
            // Filtrage des étudiants
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700', 'active');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                    });
                    e.currentTarget.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700', 'active');
                    e.currentTarget.classList.remove('border-gray-200', 'bg-white');
                    
                    bulkFilter = e.currentTarget.dataset.filter;
                    renderStudentsList();
                });
            });

            // Sélection de durée en masse
            document.querySelectorAll('.bulk-duration-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.bulk-duration-btn').forEach(b => {
                        b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                    });
                    e.currentTarget.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    e.currentTarget.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                    
                    bulkDuration = parseInt(e.currentTarget.dataset.days);
                    document.getElementById('bulkCustomDays').value = '';
                    updateBulkButton();
                });
            });

            document.getElementById('bulkCustomDays').addEventListener('input', (e) => {
                if (e.target.value) {
                    document.querySelectorAll('.bulk-duration-btn').forEach(b => {
                        b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                    });
                    bulkDuration = parseInt(e.target.value);
                } else {
                    bulkDuration = null;
                }
                updateBulkButton();
            });

            // Rendu de la liste d'étudiants
            function renderStudentsList() {
                const list = document.getElementById('studentsList');
                const filteredStudents = Object.entries(students).filter(([email, student]) => {
                    if (bulkFilter === 'all') return true;
                    return student.subMonths === parseInt(bulkFilter);
                });

                document.getElementById('listCount').textContent = filteredStudents.length;

                list.innerHTML = filteredStudents.map(([email, student]) => {
                    const initials = student.name.split(' ').map(n => n[0]).join('');
                    const isSelected = selectedStudents.has(email);
                    
                    return `
                        <div class="student-item bg-white rounded-lg p-4 border-2 ${isSelected ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'} hover:border-indigo-300 transition cursor-pointer" data-email="${email}">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <input type="checkbox" ${isSelected ? 'checked' : ''} class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    ${initials}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">${student.name}</h4>
                                    <p class="text-sm text-gray-600">${email}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full font-medium">
                                        ${student.currentSub}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Expire dans ${student.expiry}j
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                // Ajouter les événements de clic
                document.querySelectorAll('.student-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const email = item.dataset.email;
                        if (selectedStudents.has(email)) {
                            selectedStudents.delete(email);
                        } else {
                            selectedStudents.add(email);
                        }
                        renderStudentsList();
                        updateSelectedCount();
                    });
                });
            }

            // Sélectionner tout
            document.getElementById('selectAllBtn').addEventListener('click', () => {
                const filteredStudents = Object.entries(students).filter(([email, student]) => {
                    if (bulkFilter === 'all') return true;
                    return student.subMonths === parseInt(bulkFilter);
                });
                filteredStudents.forEach(([email]) => selectedStudents.add(email));
                renderStudentsList();
                updateSelectedCount();
            });

            // Désélectionner tout
            document.getElementById('deselectAllBtn').addEventListener('click', () => {
                selectedStudents.clear();
                renderStudentsList();
                updateSelectedCount();
            });

            // Mise à jour du compteur
            function updateSelectedCount() {
                document.getElementById('selectedCount').textContent = selectedStudents.size;
                updateBulkButton();
            }

            // Mise à jour du bouton
            function updateBulkButton() {
                const btn = document.getElementById('applyBulkBtn');
                btn.disabled = selectedStudents.size === 0 || !bulkDuration;
            }

            // Annuler la sélection
            document.getElementById('cancelBulkBtn').addEventListener('click', () => {
                selectedStudents.clear();
                bulkDuration = null;
                document.getElementById('bulkCustomDays').value = '';
                document.querySelectorAll('.bulk-duration-btn').forEach(b => {
                    b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                });
                renderStudentsList();
                updateSelectedCount();
            });

            // Appliquer les changements
            document.getElementById('applyBulkBtn').addEventListener('click', () => {
                if (selectedStudents.size > 0 && bulkDuration) {
                    showNotification(`${bulkDuration} jours ajoutés à ${selectedStudents.size} étudiant(s)`);
                    
                    // Réinitialiser
                    selectedStudents.clear();
                    bulkDuration = null;
                    document.getElementById('bulkCustomDays').value = '';
                    document.querySelectorAll('.bulk-duration-btn').forEach(b => {
                        b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                    });
                    renderStudentsList();
                    updateSelectedCount();
                }
            });

            // Initialiser la liste
            renderStudentsList();
            updateSelectedCount();
            updateAddButton();
        </script>
    </div>
    </section>
@endsection
