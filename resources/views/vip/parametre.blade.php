<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Hub - Profil Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        .animate-slide-down { animation: slideDown 0.3s ease-out; }
        
        .tab-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .sidebar-icon {
            transition: all 0.3s ease;
        }
        .sidebar-icon:hover {
            transform: translateX(4px);
        }
        
        .cover-image {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-6 py-3 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold">Trade <span class="text-indigo-600">Hub</span></span>
            </div>
            
            <div class="flex-1 max-w-2xl mx-8 hidden md:block">
                <div class="relative">
                    <input type="text" placeholder="Rechercher" 
                        class="w-full bg-gray-100 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="relative p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <button class="relative p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">12</span>
                </button>
                <img src="https://ui-avatars.com/api/?name=User&background=6366f1&color=fff&size=40" class="w-10 h-10 rounded-full cursor-pointer ring-2 ring-indigo-500 ring-offset-2">
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-16 bg-white border-r border-gray-200 min-h-screen sticky top-[57px] hidden md:block">
            <nav class="flex flex-col items-center py-4 gap-2">
                <a href="#" class="sidebar-icon p-3 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </a>
                <a href="#" class="sidebar-icon p-3 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="sidebar-icon p-3 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="sidebar-icon p-3 rounded-lg bg-indigo-50 relative">
                    <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>
                <a href="#" class="sidebar-icon p-3 rounded-lg hover:bg-gray-100 transition relative">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>
                <a href="#" class="sidebar-icon p-3 rounded-lg hover:bg-gray-100 transition mt-auto">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen">
            <!-- Cover Image -->

            <!-- Profile Header -->
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-6xl mx-auto px-6">
                    <div class="flex items-end -mt-16 mb-4 h-96">
                        <img src="https://ui-avatars.com/api/?name=Abdoulaye+CHOUTI&background=6366f1&color=fff&size=160" 
                            class="w-32 h-32 rounded-full border-4 border-white shadow-xl">
                        <div class="ml-6 pb-2">
                            <h1 class="text-3xl font-bold text-gray-900">Abdoulaye CHOUTI</h1>
                            <p class="text-gray-500 mt-1">Modérateur</p>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-2 overflow-x-auto pb-2" id="tabs">
                        <button onclick="showTab('info')" class="tab-btn tab-active px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition">
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Informations du profil
                        </button>
                        <button onclick="showTab('delete')" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm text-gray-600 hover:bg-gray-100 whitespace-nowrap transition">
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Suppression de compte
                        </button>
                        <button onclick="showTab('moderator')" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm text-gray-600 hover:bg-gray-100 whitespace-nowrap transition">
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Espace modérateur
                        </button>
                        <button onclick="showTab('support')" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm text-gray-600 hover:bg-gray-100 whitespace-nowrap transition">
                            <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"/>
                            </svg>
                            Support
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="max-w-6xl mx-auto px-6 py-8">
                <!-- Info Tab -->
                <div id="tab-info" class="tab-content animate-fade-in">
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold mb-6">Informations personnelles</h2>
                        <form class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" value="abdoulayedev@company.com" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" value="CHOUTI" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénoms <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" value="Abdoulaye" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Rôle <span class="text-red-500">*</span>
                                    </label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option>Modérateur</option>
                                        <option>Admin</option>
                                        <option>Coach</option>
                                        <option>Membre</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3.5 rounded-lg font-semibold hover:shadow-lg transform hover:scale-[1.02] transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sauvegarder
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Delete Tab -->
                <div id="tab-delete" class="tab-content hidden">
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-8 animate-fade-in">
                        <div class="flex gap-4 mb-6">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-red-900 mb-2">
                                    ⚠️ Avertissement — Suppression de compte irréversible 🚫😢
                                </h3>
                                <p class="text-red-800 mb-4">La suppression de votre compte est définitive.</p>
                                <p class="text-red-700 text-sm mb-2">
                                    Une fois confirmée, aucune récupération ne sera possible : toutes vos données, fichiers et paramètres seront supprimés de manière permanente.
                                </p>
                                <p class="text-red-700 text-sm">
                                    💭 Veuillez continuer uniquement si vous êtes certain de votre décision.
                                </p>
                            </div>
                        </div>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Learn more
                        </button>
                    </div>
                </div>

                <!-- Moderator Tab -->
                <div id="tab-moderator" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm p-8 animate-fade-in">
                        <h2 class="text-xl font-bold mb-6">Inviter un Nouvel utilisateur</h2>
                        <form class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" placeholder="kossoujaures@company.com" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" placeholder="KOSSOU" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénoms <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" placeholder="Jaurès" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Rôle <span class="text-red-500">*</span>
                                    </label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option>Coach</option>
                                        <option>Modérateur</option>
                                        <option>Admin</option>
                                        <option>Membre</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3.5 rounded-lg font-semibold hover:shadow-lg transform hover:scale-[1.02] transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                                Inviter l'utilisateur
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Support Tab -->
                <div id="tab-support" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm p-8 animate-fade-in">
                        <h2 class="text-xl font-bold mb-6">Contacter le Support</h2>
                        <form class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" value="abdoulayechouti@company.com" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom Complet <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" value="Abdoulaye CHOUTI" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea rows="6" 
                                    placeholder="Standard glass, 3.9GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3.5 rounded-lg font-semibold hover:shadow-lg transform hover:scale-[1.02] transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                </svg>
                                Contacter le support client
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            // Add active class to clicked button
            event.target.classList.add('tab-active');
            event.target.classList.remove('text-gray-600', 'hover:bg-gray-100');
        }
    </script>

</body>
</html>