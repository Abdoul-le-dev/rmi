<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Hub - Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .calendar-cell {
            min-height: 35px;
            transition: all 0.2s;
        }
        .calendar-cell:hover {
            background: #f3f4f6;
            cursor: pointer;
        }
        .calendar-cell.today {
            background: #6366f1;
            color: white;
            font-weight: bold;
        }
        .time-slot {
            height: 60px;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }
        .event-card {
            position: absolute;
            border-radius: 6px;
            padding: 8px;
            font-size: 12px;
            cursor: move;
            overflow: hidden;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .event-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            z-index: 20;
        }
        
        @media (max-width: 768px) {
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                z-index: 50;
            }
            body {
                padding-bottom: 4rem;
            }
            .sidebar-desktop {
                position: fixed;
                left: -100%;
                top: 57px;
                bottom: 0;
                z-index: 40;
                transition: left 0.3s;
                overflow-y: auto;
            }
            .sidebar-desktop.active {
                left: 0;
            }
            .overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 35;
            }
            .overlay.active {
                display: block;
            }
        }
        
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
                
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                    <span class="text-lg md:text-xl font-bold">Trade <span class="text-indigo-600">Hub</span></span>
                </div>
            </div>
            
            <div class="flex-1 max-w-2xl mx-4 hidden md:block">
                <div class="relative">
                    <input type="text" placeholder="Rechercher" 
                        class="w-full bg-gray-100 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-4">
                <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </button>
                
                <button class="relative p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <button class="relative p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">12</span>
                </button>
                <img src="https://ui-avatars.com/api/?name=User&background=6366f1&color=fff&size=40" class="w-8 h-8 md:w-10 md:h-10 rounded-full cursor-pointer ring-2 ring-indigo-500 ring-offset-2">
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar Profil & Navigation -->
        <aside class="sidebar-desktop w-64 bg-white border-r border-gray-200 min-h-screen sticky top-[57px] overflow-y-auto">
            <!-- Profil -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col items-center">
                    <img src="https://ui-avatars.com/api/?name=CHOUTI+Abdoulaye&background=6366f1&color=fff&size=120" 
                        class="w-24 h-24 rounded-full mb-4 ring-4 ring-indigo-100">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        CHOUTI Abdoulaye
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Modérateur</p>
                    
                    <div class="grid grid-cols-3 gap-4 w-full">
                        <div class="text-center">
                            <div class="font-bold text-lg">500K</div>
                            <div class="text-xs text-gray-500">Post</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-lg">23.5M</div>
                            <div class="text-xs text-gray-500">Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-lg">50</div>
                            <div class="text-xs text-gray-500">Following</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            <nav class="p-4">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition mb-1">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span class="font-medium">Communautés</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition mb-1">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                    <span class="font-medium">Classrooms</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-700 mb-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Évènements</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition mb-1">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Localisations des Membres</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition mb-1">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    <span class="font-medium">Vos Sessions</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition mb-1">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Paramètres</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Centre d'aide</span>
                </a>
            </nav>

            <!-- Mini Calendar -->
            <div class="p-4 border-t border-gray-200">
                <div class="text-center mb-3">
                    <h4 class="font-semibold">January</h4>
                </div>
                <div class="grid grid-cols-7 gap-1 text-xs">
                    <div class="text-center text-gray-500 font-medium">M</div>
                    <div class="text-center text-gray-500 font-medium">T</div>
                    <div class="text-center text-gray-500 font-medium">W</div>
                    <div class="text-center text-gray-500 font-medium">T</div>
                    <div class="text-center text-gray-500 font-medium">F</div>
                    <div class="text-center text-gray-500 font-medium">S</div>
                    <div class="text-center text-gray-500 font-medium">S</div>
                    
                    <div class="calendar-cell text-center p-1 rounded">01</div>
                    <div class="calendar-cell text-center p-1 rounded today">02</div>
                    <div class="calendar-cell text-center p-1 rounded">03</div>
                    <div class="calendar-cell text-center p-1 rounded">04</div>
                    <div class="calendar-cell text-center p-1 rounded">05</div>
                    <div class="calendar-cell text-center p-1 rounded">06</div>
                    <div class="calendar-cell text-center p-1 rounded">07</div>
                    <div class="calendar-cell text-center p-1 rounded">08</div>
                    <div class="calendar-cell text-center p-1 rounded">09</div>
                    <div class="calendar-cell text-center p-1 rounded">10</div>
                    <div class="calendar-cell text-center p-1 rounded">11</div>
                    <div class="calendar-cell text-center p-1 rounded">12</div>
                    <div class="calendar-cell text-center p-1 rounded">13</div>
                    <div class="calendar-cell text-center p-1 rounded">14</div>
                    <div class="calendar-cell text-center p-1 rounded">15</div>
                    <div class="calendar-cell text-center p-1 rounded">16</div>
                    <div class="calendar-cell text-center p-1 rounded">17</div>
                    <div class="calendar-cell text-center p-1 rounded">18</div>
                    <div class="calendar-cell text-center p-1 rounded">19</div>
                    <div class="calendar-cell text-center p-1 rounded">20</div>
                    <div class="calendar-cell text-center p-1 rounded">21</div>
                    <div class="calendar-cell text-center p-1 rounded">22</div>
                    <div class="calendar-cell text-center p-1 rounded">23</div>
                    <div class="calendar-cell text-center p-1 rounded">24</div>
                    <div class="calendar-cell text-center p-1 rounded">25</div>
                    <div class="calendar-cell text-center p-1 rounded">26</div>
                    <div class="calendar-cell text-center p-1 rounded">27</div>
                    <div class="calendar-cell text-center p-1 rounded">28</div>
                    <div class="calendar-cell text-center p-1 rounded">29</div>
                    <div class="calendar-cell text-center p-1 rounded">30</div>
                    <div class="calendar-cell text-center p-1 rounded">31</div>
                </div>

                <div class="mt-4 text-center">
                    <h5 class="font-semibold text-sm mb-2">Upcoming events</h5>
                    <div id="upcomingEvents" class="text-gray-400 text-xs py-4">
                        <div class="mb-2">🎉</div>
                        <div>No upcoming events</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content - Calendar View -->
        <main class="flex-1 p-4 md:p-6 overflow-x-auto">
            <!-- Calendar Header -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </button>
                        <h2 class="text-xl md:text-2xl font-bold">01-07 January 2022</h2>
                        <select class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white">
                            <option>Week</option>
                            <option>Day</option>
                            <option>Month</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition" onclick="openModal()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="hidden md:inline">Add event</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Weekly Calendar -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="min-w-[800px]">
                        <!-- Days Header -->
                        <div class="grid grid-cols-8 border-b border-gray-200 bg-gray-50">
                            <div class="p-3"></div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">Mon</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">Tue</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-semibold">Wed</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">Thur</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">Fri</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                    <span class="font-semibold">Sat</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">Sun</span>
                                </div>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="divide-y divide-gray-200">
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">00:00</div>
                                <div id="day-1" class="border-l border-gray-100 relative"></div>
                                <div id="day-2" class="border-l border-gray-100 relative"></div>
                                <div id="day-3" class="border-l border-gray-100 relative"></div>
                                <div id="day-4" class="border-l border-gray-100 relative"></div>
                                <div id="day-5" class="border-l border-gray-100 relative"></div>
                                <div id="day-6" class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div id="day-7" class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">01:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">02:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">03:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">04:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">05:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">06:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">07:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">08:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">09:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                            <div class="grid grid-cols-8 time-slot">
                                <div class="p-2 text-xs text-gray-500 text-right pr-3">10:00</div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                                <div class="border-l border-gray-100 bg-gray-50 relative"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Overlay pour mobile -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- Modal Ajout Événement -->
    <div class="modal" id="eventModal">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold">Nouvel Événement</h3>
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <form onsubmit="addEvent(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                    <input type="text" id="eventTitle" required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" id="eventDate" required 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jour</label>
                        <select id="eventDay" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="1">Lundi</option>
                            <option value="2">Mardi</option>
                            <option value="3">Mercredi</option>
                            <option value="4">Jeudi</option>
                            <option value="5">Vendredi</option>
                            <option value="6">Samedi</option>
                            <option value="7">Dimanche</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Heure début *</label>
                        <input type="time" id="eventStartTime" required 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Heure fin *</label>
                        <input type="time" id="eventEndTime" required 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectColor('bg-blue-500')" class="w-10 h-10 rounded-lg bg-blue-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                        <button type="button" onclick="selectColor('bg-green-500')" class="w-10 h-10 rounded-lg bg-green-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                        <button type="button" onclick="selectColor('bg-red-500')" class="w-10 h-10 rounded-lg bg-red-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                        <button type="button" onclick="selectColor('bg-purple-500')" class="w-10 h-10 rounded-lg bg-purple-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                        <button type="button" onclick="selectColor('bg-orange-500')" class="w-10 h-10 rounded-lg bg-orange-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                        <button type="button" onclick="selectColor('bg-pink-500')" class="w-10 h-10 rounded-lg bg-pink-500 border-2 border-transparent hover:border-gray-800 transition"></button>
                    </div>
                    <input type="hidden" id="eventColor" value="bg-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="eventDescription" rows="3" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold transition">
                        Annuler
                    </button>
                    <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav md:hidden flex justify-around items-center p-3">
        <a href="#" class="flex flex-col items-center gap-1 text-gray-600">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span class="text-xs">Accueil</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-indigo-600 font-semibold">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs">Événements</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-gray-600">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
            </svg>
            <span class="text-xs">Sessions</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-gray-600">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs">Profil</span>
        </a>
    </nav>

    <script>
        // État global
        let events = [];
        let selectedColor = 'bg-blue-500';
        let draggedEvent = null;
        let currentWeekStart = new Date(2022, 0, 1); // 1er janvier 2022

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            updateCalendar();
            renderMiniCalendar();
            renderUpcomingEvents();
            
            // Exemple d'événements
            events = [
                {
                    id: 1,
                    title: 'Réunion Trading',
                    day: 2,
                    startTime: '09:00',
                    endTime: '10:30',
                    color: 'bg-blue-500',
                    description: 'Stratégies de trading pour Q1'
                },
                {
                    id: 2,
                    title: 'Formation Crypto',
                    day: 4,
                    startTime: '14:00',
                    endTime: '16:00',
                    color: 'bg-purple-500',
                    description: 'Introduction aux cryptomonnaies'
                },
                {
                    id: 3,
                    title: 'Webinar',
                    day: 5,
                    startTime: '11:00',
                    endTime: '12:00',
                    color: 'bg-green-500',
                    description: 'Analyse technique avancée'
                }
            ];
            
            renderEvents();
        });

        // Toggle sidebar mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-desktop');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar-desktop');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        // Modal
        function openModal() {
            document.getElementById('eventModal').classList.add('active');
            // Pré-remplir avec date actuelle
            const today = new Date();
            document.getElementById('eventDate').valueAsDate = today;
        }

        function closeModal() {
            document.getElementById('eventModal').classList.remove('active');
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDescription').value = '';
        }

        function selectColor(color) {
            selectedColor = color;
            document.getElementById('eventColor').value = color;
            // Mettre à jour l'UI
            document.querySelectorAll('[onclick^="selectColor"]').forEach(btn => {
                btn.classList.remove('border-gray-800');
                btn.classList.add('border-transparent');
            });
            event.target.classList.remove('border-transparent');
            event.target.classList.add('border-gray-800');
        }

        // Ajout d'événement
        function addEvent(e) {
            e.preventDefault();
            
            const newEvent = {
                id: Date.now(),
                title: document.getElementById('eventTitle').value,
                day: parseInt(document.getElementById('eventDay').value),
                startTime: document.getElementById('eventStartTime').value,
                endTime: document.getElementById('eventEndTime').value,
                color: document.getElementById('eventColor').value,
                description: document.getElementById('eventDescription').value,
                date: document.getElementById('eventDate').value
            };
            
            events.push(newEvent);
            renderEvents();
            renderUpcomingEvents();
            closeModal();
            
            // Notification
            showNotification('Événement créé avec succès !');
        }

        // Rendu des événements
        function renderEvents() {
            // Nettoyer les anciens événements
            document.querySelectorAll('.event-card').forEach(el => el.remove());
            
            events.forEach(event => {
                const eventEl = createEventElement(event);
                const dayCol = document.querySelector(`#day-${event.day}`);
                if (dayCol) {
                    dayCol.appendChild(eventEl);
                }
            });
        }

        function createEventElement(event) {
            const div = document.createElement('div');
            div.className = `event-card ${event.color} text-white`;
            div.draggable = true;
            div.dataset.eventId = event.id;
            
            // Calculer position et hauteur
            const startMinutes = timeToMinutes(event.startTime);
            const endMinutes = timeToMinutes(event.endTime);
            const duration = endMinutes - startMinutes;
            
            const top = (startMinutes / 60) * 60; // 60px par heure
            const height = (duration / 60) * 60;
            
            div.style.top = `${top}px`;
            div.style.height = `${height}px`;
            div.style.width = 'calc(100% - 8px)';
            div.style.left = '4px';
            
            div.innerHTML = `
                <div class="font-semibold text-xs mb-1">${event.title}</div>
                <div class="text-xs opacity-90">${event.startTime} - ${event.endTime}</div>
            `;
            
            // Drag & drop
            div.addEventListener('dragstart', handleDragStart);
            div.addEventListener('click', () => showEventDetails(event));
            
            return div;
        }

        function timeToMinutes(time) {
            const [hours, minutes] = time.split(':').map(Number);
            return hours * 60 + minutes;
        }

        // Drag & Drop
        function handleDragStart(e) {
            draggedEvent = parseInt(e.target.dataset.eventId);
            e.target.style.opacity = '0.5';
        }

        // Ajouter les zones de drop
        document.addEventListener('DOMContentLoaded', function() {
            const dayCols = document.querySelectorAll('[id^="day-"]');
            dayCols.forEach(col => {
                col.addEventListener('dragover', handleDragOver);
                col.addEventListener('drop', handleDrop);
                col.addEventListener('dragleave', handleDragLeave);
            });
        });

        function handleDragOver(e) {
            e.preventDefault();
            e.currentTarget.style.background = '#f3f4f6';
        }

        function handleDragLeave(e) {
            e.currentTarget.style.background = '';
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.style.background = '';
            
            const newDay = parseInt(e.currentTarget.id.split('-')[1]);
            const eventIndex = events.findIndex(ev => ev.id === draggedEvent);
            
            if (eventIndex !== -1) {
                events[eventIndex].day = newDay;
                renderEvents();
                showNotification('Événement déplacé !');
            }
        }

        // Détails événement
        function showEventDetails(event) {
            const confirmed = confirm(`${event.title}
${event.startTime} - ${event.endTime}

${event.description || 'Aucune description'}

Voulez-vous supprimer cet événement ?`);
            
            if (confirmed) {
                deleteEvent(event.id);
            }
        }

        function deleteEvent(id) {
            events = events.filter(e => e.id !== id);
            renderEvents();
            renderUpcomingEvents();
            showNotification('Événement supprimé');
        }

        // Mini calendrier
        function renderMiniCalendar() {
            // Déjà rendu en HTML statique
        }

        // Upcoming events
        function renderUpcomingEvents() {
            const container = document.getElementById('upcomingEvents');
            if (!container) return;
            
            const upcoming = events.slice(0, 3);
            
            if (upcoming.length === 0) {
                container.innerHTML = `
                    <div class="text-gray-400 text-xs py-4">
                        <div class="mb-2">🎉</div>
                        <div>No upcoming events</div>
                    </div>
                `;
            } else {
                container.innerHTML = upcoming.map(event => `
                    <div class="text-xs p-2 rounded-lg ${event.color} bg-opacity-10 mb-2">
                        <div class="font-semibold">${event.title}</div>
                        <div class="text-gray-600">${event.startTime}</div>
                    </div>
                `).join('');
            }
        }

        // Notification
        function showNotification(message) {
            const notif = document.createElement('div');
            notif.className = 'fixed bottom-20 md:bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
            notif.textContent = message;
            document.body.appendChild(notif);
            
            setTimeout(() => {
                notif.remove();
            }, 3000);
        }

        // Navigation calendrier
        function updateCalendar() {
            // Logique de mise à jour du calendrier hebdomadaire
        }

        // Préparer les colonnes pour le drop
        setTimeout(() => {
            for (let i = 1; i <= 7; i++) {
                const dayCol = document.querySelector(`#day-${i}`);
                if (!dayCol) {
                    const cols = document.querySelectorAll('.time-slot > div');
                    cols.forEach((col, index) => {
                        if ((index - 1) % 8 === i) {
                            col.id = `day-${i}`;
                            col.style.position = 'relative';
                        }
                    });
                }
            }
        }, 100);

        // Animation slide-in pour notifications
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slide-in {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            .animate-slide-in { animation: slide-in 0.3s ease-out; }
        `;
        document.head.appendChild(style);
    </script>

</body>
</html>