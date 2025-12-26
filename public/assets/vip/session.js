const sessions = [
    {
        id: 1,
        name: 'Formation de Trading 2025 FR',
        message: 'porro quisquam est qui dolorem ipsum quia...',
        time: '4:30 PM',
        sender: 'Alex',
        avatar: 'FT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 2,
        name: 'Binance In Trading 2024 💪',
        message: '📹 Video Lorem ipsum dolor sit amet...',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'BT',
        unread: 1,
        active: false,
        type: 'group'
    },
    {
        id: 3,
        name: 'Trade with French Professionals',
        message: 'Neque porro quisquam est qui dolorem...',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'TF',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 4,
        name: 'Trade Your wealth here',
        message: '🎵 Audio Lorem ipsum dolor sit amet consect...',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'TW',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 5,
        name: 'Conférence des Traders de Paris',
        message: '🎉 Félicitations pour la conclusion',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'CT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 6,
        name: 'Communauté des Traders 229 🇧🇯',
        message: '📞 Voice call Lorem ipsum dolor sit amet c...',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'CT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 7,
        name: 'Traders des Côtes d\'azures FR',
        message: '✨ Sticker Lorem ipsum dolor sit armt con...',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'TC',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 8,
        name: 'Welcome Trader Newbie 🌟',
        message: 'Ce message a été supprimé',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'WT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 9,
        name: 'Hackaton for Traders EN 🇺🇸',
        message: '🎬 GIF',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'HT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 10,
        name: 'Become the best Traders Gen🎉',
        message: '📄 Document de collaboration',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'BT',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 11,
        name: 'Solaris : New Gen Traders 📈',
        message: '📍 Localisation en temps réel',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'SG',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 12,
        name: 'Club des Traders du Bénin 💛',
        message: '📹 Appel Vidéos',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'CB',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 13,
        name: 'Zone Franche des Traders ⚠️',
        message: '📎 Document',
        time: '4:30 PM',
        sender: 'You',
        avatar: 'ZF',
        unread: 0,
        active: false,
        type: 'group'
    },
    {
        id: 14,
        name: 'SOLDERS SENDERS TRADERS',
        message: '👤 Contact',
        time: '4:30 PM',
        sender: 'Sender',
        avatar: 'SS',
        unread: 0,
        active: false,
        type: 'group'
    }
];

function getAvatarColor(index) {
    const colors = [
        'from-blue-500 to-indigo-600',
        'from-purple-500 to-pink-600',
        'from-green-500 to-emerald-600',
        'from-orange-500 to-red-600',
        'from-cyan-500 to-blue-600',
        'from-violet-500 to-purple-600',
        'from-amber-500 to-orange-600',
        'from-teal-500 to-cyan-600'
    ];
    return colors[index % colors.length];
}

function renderSessions() {
    const list = document.getElementById('sessionsList');
    list.innerHTML = sessions.map((session, index) => `
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition ${session.active ? 'bg-indigo-50' : ''}" 
                    onclick="openSession(${session.id})">
                    <div class="relative flex-shrink-0">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br ${getAvatarColor(index)} flex items-center justify-center text-white font-bold text-sm shadow-md">
                            ${session.avatar}
                        </div>
                        ${session.unread > 0 ? `<span class="absolute -top-1 -right-1 w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-bold">${session.unread}</span>` : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h3 class="font-semibold text-sm truncate ${session.unread > 0 ? 'text-gray-900' : 'text-gray-700'}">${session.name}</h3>
                            <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${session.time}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-gray-600 font-medium">${session.sender}:</span>
                            <p class="text-xs ${session.unread > 0 ? 'text-gray-700 font-medium' : 'text-gray-500'} truncate flex-1">${session.message}</p>
                        </div>
                    </div>
                </div>
            `).join('');
}

function openSession(id) {
    // Mobile: masquer la liste et afficher le chat
    if (window.innerWidth < 768) {
        document.getElementById('sidebarSessions').classList.add('mobile-hidden');
        document.getElementById('chatZone').classList.remove('mobile-hidden');
    }

    sessions.forEach(s => s.active = s.id === id);
    renderSessions();

    // Ici vous pouvez charger le contenu de la session
    loadSessionContent(id);
}

function loadSessionContent(sessionId) {
    const session = sessions.find(s => s.id === sessionId);
    const chatZone = document.getElementById('chatZone');

    chatZone.innerHTML = `
                <div class="flex-1 flex flex-col bg-white">
                    <!-- Header Session -->
                    <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <button class="md:hidden p-2 hover:bg-gray-100 rounded-full" onclick="backToList()">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br ${getAvatarColor(sessionId - 1)} flex items-center justify-center text-white font-bold text-sm shadow-md">
                                ${session.avatar}
                            </div>
                            <div>
                                <h2 class="font-semibold flex items-center gap-2">
                                    ${session.name}
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">Session</span>
                                </h2>
                                <p class="text-xs text-gray-500">Publication par les admins uniquement</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages de la session -->
                    <div class="flex-1 overflow-y-auto p-4 bg-gray-50 space-y-4">
                        <!-- Message de l'admin -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <div class="flex items-start gap-3">
                                <img src="https://ui-avatars.com/api/?name=Admin+Trading&background=6366f1&color=fff&size=40" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-sm">Admin Trading</span>
                                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-medium">Admin</span>
                                        <span class="text-xs text-gray-500">Aujourd'hui à 14:30</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Bienvenue dans la session de formation Trading 2025 ! 🎯<br><br>
                                        Cette session est dédiée aux annonces importantes et aux formations exclusives. Seuls les administrateurs peuvent publier du contenu ici pour garantir la qualité des informations partagées.
                                    </p>
                                    <div class="mt-3 flex gap-4 text-xs text-gray-500">
                                        <button class="hover:text-indigo-600 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            24
                                        </button>
                                        <button class="hover:text-indigo-600 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            8 commentaires
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autre message admin -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <div class="flex items-start gap-3">
                                <img src="https://ui-avatars.com/api/?name=Sophie+Martin&background=ec4899&color=fff&size=40" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-sm">Sophie Martin</span>
                                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-medium">Admin</span>
                                        <span class="text-xs text-gray-500">Hier à 16:45</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed mb-3">
                                        📊 Nouvelle formation disponible : Analyse technique avancée
                                    </p>
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-4 text-white">
                                        <h4 class="font-semibold mb-1">Formation Analyse Technique</h4>
                                        <p class="text-sm opacity-90">Maîtrisez les indicateurs et patterns de trading</p>
                                        <button class="mt-3 bg-white text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
                                            Accéder à la formation
                                        </button>
                                    </div>
                                    <div class="mt-3 flex gap-4 text-xs text-gray-500">
                                        <button class="hover:text-indigo-600 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            42
                                        </button>
                                        <button class="hover:text-indigo-600 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            15 commentaires
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zone info (pas de saisie pour les membres) -->
                    <div class="bg-gray-100 border-t border-gray-200 p-4">
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Seuls les administrateurs peuvent publier dans cette session</span>
                        </div>
                    </div>
                </div>
            `;
}

function backToList() {
    document.getElementById('sidebarSessions').classList.remove('mobile-hidden');
    document.getElementById('chatZone').classList.add('mobile-hidden');

    // Restaurer l'état vide
    const chatZone = document.getElementById('chatZone');
    chatZone.innerHTML = `
                <div class="text-center max-w-md px-4">
                    <div class="mb-6 flex justify-center">
                        <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome to Your Conversations</h2>
                    <p class="text-gray-500 text-sm">
                        Select a chat from the list to start exploring your messages or begin a new conversation
                    </p>
                </div>
            `;
}

renderSessions();