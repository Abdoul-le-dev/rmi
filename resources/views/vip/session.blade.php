<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions - Groupes Trading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slideIn 0.4s ease-out; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-scale-in { animation: scaleIn 0.3s ease-out; }
        .animate-slide-right { animation: slideRight 0.3s ease-out; }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; }
            .chat-zone.mobile-hidden { display: none; }
            .sidebar.mobile-hidden { display: none; }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        .comment-input:focus-within {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .like-btn:active {
            transform: scale(0.9);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 h-screen overflow-hidden">

    <div class="flex h-screen">
        <!-- Sidebar Navigation (Desktop) -->
        <div class="hidden md:flex flex-col w-20 bg-white border-r border-gray-200 shadow-sm">
            <div class="p-4 flex flex-col items-center gap-6">
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                </button>
                <button class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl relative shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 relative group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group mt-auto">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Liste des Sessions -->
        <div class="sidebar w-full md:w-96 bg-white border-r border-gray-200 flex flex-col shadow-sm" id="sidebarSessions">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold gradient-text">Sessions</h1>
                    <button class="p-2 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-full transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                </div>
                <!-- Barre de recherche -->
                <div class="relative group">
                    <input type="text" placeholder="Rechercher une session..." 
                        class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 group-focus-within:text-indigo-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Liste -->
            <div class="flex-1 overflow-y-auto scrollbar-hide" id="sessionsList"></div>
        </div>

        <!-- Zone vide (état initial) -->
        <div class="chat-zone flex-1 flex items-center justify-center" id="chatZone">
            <div class="text-center max-w-md px-4 animate-fade-in">
                <div class="mb-6 flex justify-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Bienvenue sur vos Sessions</h2>
                <p class="text-gray-500">
                    Sélectionnez une session pour découvrir les publications et participer aux discussions
                </p>
            </div>
        </div>
    </div>

    <script>
        const sessions = [
            { id: 1, name: 'Formation de Trading 2025 FR', message: 'porro quisquam est qui dolorem ipsum quia...', time: '4:30 PM', sender: 'Alex', avatar: 'FT', unread: 0, active: false },
            { id: 2, name: 'Binance In Trading 2024 💪', message: '📹 Video Lorem ipsum dolor sit amet...', time: '4:30 PM', sender: 'Sender', avatar: 'BT', unread: 1, active: false },
            { id: 3, name: 'Trade with French Professionals', message: 'Neque porro quisquam est qui dolorem...', time: '4:30 PM', sender: 'You', avatar: 'TF', unread: 0, active: false },
            { id: 4, name: 'Trade Your wealth here', message: '🎵 Audio Lorem ipsum dolor sit amet consect...', time: '4:30 PM', sender: 'You', avatar: 'TW', unread: 0, active: false },
            { id: 5, name: 'Conférence des Traders de Paris', message: '🎉 Félicitations pour la conclusion', time: '4:30 PM', sender: 'You', avatar: 'CT', unread: 0, active: false },
            { id: 6, name: 'Communauté des Traders 229 🇧🇯', message: '📞 Voice call Lorem ipsum dolor sit amet c...', time: '4:30 PM', sender: 'Sender', avatar: 'CT', unread: 0, active: false },
            { id: 7, name: 'Traders des Côtes d\'azures FR', message: '✨ Sticker Lorem ipsum dolor sit armt con...', time: '4:30 PM', sender: 'You', avatar: 'TC', unread: 0, active: false },
            { id: 8, name: 'Welcome Trader Newbie 🌟', message: 'Ce message a été supprimé', time: '4:30 PM', sender: 'Sender', avatar: 'WT', unread: 0, active: false },
            { id: 9, name: 'Hackaton for Traders EN 🇺🇸', message: '🎬 GIF', time: '4:30 PM', sender: 'Sender', avatar: 'HT', unread: 0, active: false },
            { id: 10, name: 'Become the best Traders Gen🎉', message: '📄 Document de collaboration', time: '4:30 PM', sender: 'You', avatar: 'BT', unread: 0, active: false },
            { id: 11, name: 'Solaris : New Gen Traders 📈', message: '📍 Localisation en temps réel', time: '4:30 PM', sender: 'Sender', avatar: 'SG', unread: 0, active: false },
            { id: 12, name: 'Club des Traders du Bénin 💛', message: '📹 Appel Vidéos', time: '4:30 PM', sender: 'Sender', avatar: 'CB', unread: 0, active: false },
            { id: 13, name: 'Zone Franche des Traders ⚠️', message: '📎 Document', time: '4:30 PM', sender: 'You', avatar: 'ZF', unread: 0, active: false },
            { id: 14, name: 'SOLDERS SENDERS TRADERS', message: '👤 Contact', time: '4:30 PM', sender: 'Sender', avatar: 'SS', unread: 0, active: false }
        ];

        let posts = {};
        let showComments = {};

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
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 cursor-pointer transition-all duration-300 ${session.active ? 'bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500' : ''} animate-slide-right" 
                    style="animation-delay: ${index * 0.05}s"
                    onclick="openSession(${session.id})">
                    <div class="relative flex-shrink-0">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br ${getAvatarColor(index)} flex items-center justify-center text-white font-bold text-sm shadow-md hover-lift transition-all duration-300">
                            ${session.avatar}
                        </div>
                        ${session.unread > 0 ? `<span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full text-xs flex items-center justify-center font-bold shadow-lg animate-pulse">${session.unread}</span>` : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h3 class="font-semibold text-sm truncate ${session.unread > 0 ? 'text-gray-900' : 'text-gray-700'}">${session.name}</h3>
                            <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${session.time}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-indigo-600 font-medium">${session.sender}:</span>
                            <p class="text-xs ${session.unread > 0 ? 'text-gray-700 font-medium' : 'text-gray-500'} truncate flex-1">${session.message}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function toggleComments(postId) {
            showComments[postId] = !showComments[postId];
            const commentsSection = document.getElementById(`comments-${postId}`);
            if (showComments[postId]) {
                commentsSection.classList.remove('hidden');
                commentsSection.classList.add('animate-scale-in');
            } else {
                commentsSection.classList.add('hidden');
            }
        }

        function toggleLike(postId) {
            const post = posts[postId];
            post.liked = !post.liked;
            post.likes += post.liked ? 1 : -1;
            
            const likeBtn = document.getElementById(`like-${postId}`);
            const likeCount = document.getElementById(`like-count-${postId}`);
            
            likeBtn.innerHTML = post.liked 
                ? '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>'
                : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/></svg>';
            
            likeCount.textContent = post.likes;
            
            // Animation
            likeBtn.classList.add('scale-125');
            setTimeout(() => likeBtn.classList.remove('scale-125'), 200);
        }

        function addComment(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            const text = input.value.trim();
            
            if (!text) return;
            
            const post = posts[postId];
            post.comments.push({
                id: Date.now(),
                author: 'Vous',
                avatar: 'https://ui-avatars.com/api/?name=Vous&background=6366f1&color=fff&size=32',
                text: text,
                time: 'À l\'instant'
            });
            
            input.value = '';
            
            // Update comments count
            document.getElementById(`comment-count-${postId}`).textContent = `${post.comments.length} commentaire${post.comments.length > 1 ? 's' : ''}`;
            
            // Re-render comments
            renderComments(postId);
        }

        function renderComments(postId) {
            const post = posts[postId];
            const commentsContainer = document.getElementById(`comments-list-${postId}`);
            
            commentsContainer.innerHTML = post.comments.map((comment, index) => `
                <div class="flex gap-3 animate-slide-in" style="animation-delay: ${index * 0.1}s">
                    <img src="${comment.avatar}" class="w-10 h-10 rounded-full flex-shrink-0 shadow-sm">
                    <div class="flex-1">
                        <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-gray-900">${comment.author}</span>
                                <span class="text-xs text-gray-500">${comment.time}</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">${comment.text}</p>
                        </div>
                        <div class="flex items-center gap-4 mt-2 ml-4 text-xs text-gray-600">
                            <button class="hover:text-indigo-600 font-semibold transition-colors">J'aime</button>
                            <button class="hover:text-indigo-600 font-semibold transition-colors">Répondre</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function openSession(id) {
            if (window.innerWidth < 768) {
                document.getElementById('sidebarSessions').classList.add('mobile-hidden');
                document.getElementById('chatZone').classList.remove('mobile-hidden');
            }
            
            sessions.forEach(s => s.active = s.id === id);
            renderSessions();
            loadSessionContent(id);
        }

        function loadSessionContent(sessionId) {
            const session = sessions.find(s => s.id === sessionId);
            const chatZone = document.getElementById('chatZone');
            
            // Initialize posts
            posts = {
                1: {
                    id: 1,
                    likes: 24,
                    liked: false,
                    comments: [
                        { id: 1, author: 'Marie Dubois', avatar: 'https://ui-avatars.com/api/?name=Marie+Dubois&background=ec4899&color=fff&size=32', text: 'Super intéressant ! Hâte de commencer 🚀', time: 'Il y a 2h' },
                        { id: 2, author: 'Jean Martin', avatar: 'https://ui-avatars.com/api/?name=Jean+Martin&background=10b981&color=fff&size=32', text: 'Merci pour cette session, très instructive !', time: 'Il y a 1h' }
                    ]
                },
                2: {
                    id: 2,
                    likes: 42,
                    liked: false,
                    comments: [
                        { id: 1, author: 'Sophie Bernard', avatar: 'https://ui-avatars.com/api/?name=Sophie+Bernard&background=f59e0b&color=fff&size=32', text: 'Excellente formation ! 💯', time: 'Il y a 3h' }
                    ]
                }
            };
            
            showComments = {};
            
            chatZone.innerHTML = `
                <div class="flex flex-col h-full bg-white animate-fade-in">
                    <!-- Header Session Fixe -->
                    <div class="flex-shrink-0 bg-white border-b border-gray-200 shadow-sm px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <button class="md:hidden p-2 hover:bg-gray-100 rounded-full transition-all duration-300" onclick="backToList()">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br ${getAvatarColor(sessionId - 1)} flex items-center justify-center text-white font-bold shadow-md">
                                    ${session.avatar}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-lg font-bold text-gray-900">${session.name}</h2>
                                        <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-2.5 py-0.5 rounded-full font-semibold">Session</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                            </svg>
                                            ${Math.floor(Math.random() * 500) + 100} membres
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                            ${Math.floor(Math.random() * 50) + 20} en ligne
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 hover:bg-gray-100 rounded-full transition-all duration-300 group">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button class="p-2 hover:bg-gray-100 rounded-full transition-all duration-300 group">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Zone de contenu défilable -->
                    <div class="flex-1 overflow-y-auto bg-gray-50">
                        <div class="max-w-3xl mx-auto p-6 space-y-5">
                            <!-- Post 1 - Message de bienvenue -->
                            <article class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden animate-scale-in">
                                <!-- Header du post -->
                                <div class="p-5">
                                    <div class="flex items-center gap-3 mb-4">
                                        <img src="https://ui-avatars.com/api/?name=Admin+Trading&background=6366f1&color=fff&size=48" class="w-12 h-12 rounded-full shadow">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900">Admin Trading</span>
                                                <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-2 py-0.5 rounded-md font-medium">Admin</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                                <span>Aujourd'hui à 14:30</span>
                                                <span>•</span>
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Contenu -->
                                    <div class="text-gray-700 leading-relaxed space-y-3">
                                        <p>🎯 <strong>Bienvenue dans la session de formation Trading 2025 !</strong></p>
                                        <p class="text-gray-600">
                                            Cette session est votre espace privilégié pour découvrir nos formations exclusives, recevoir les dernières actualités du trading et accéder à des ressources premium.
                                        </p>
                                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-4">
                                            <p class="text-sm text-gray-700">
                                                💬 <strong>N'hésitez pas à commenter</strong> pour partager vos questions et enrichir les discussions avec la communauté !
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stats et actions -->
                                <div class="border-t border-gray-100">
                                    <div class="px-5 py-2 flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <div class="flex -space-x-1">
                                                <span class="w-5 h-5 rounded-full bg-gradient-to-br from-red-400 to-pink-500 border-2 border-white flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                                <span class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 border-2 border-white flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <span id="like-count-1" class="font-medium">24</span>
                                        </div>
                                        <button onclick="toggleComments(1)" class="hover:underline font-medium">
                                            <span id="comment-count-1">2 commentaires</span>
                                        </button>
                                    </div>
                                    
                                    <div class="px-5 py-1 flex border-t border-gray-100">
                                        <button class="like-btn flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group" id="like-1" onclick="toggleLike(1)">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">J'aime</span>
                                        </button>
                                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group" onclick="toggleComments(1)">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">Commenter</span>
                                        </button>
                                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">Partager</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Commentaires -->
                                <div class="hidden border-t border-gray-200 bg-gray-50" id="comments-1">
                                    <div class="px-5 py-4 space-y-3 max-h-80 overflow-y-auto" id="comments-list-1"></div>
                                    
                                    <div class="px-5 pb-4 pt-3 border-t border-gray-200">
                                        <div class="comment-input flex gap-3 bg-white rounded-full p-2 shadow-sm border border-gray-200">
                                            <img src="https://ui-avatars.com/api/?name=Vous&background=6366f1&color=fff&size=36" class="w-9 h-9 rounded-full">
                                            <input type="text" 
                                                id="comment-input-1"
                                                placeholder="Ajoutez un commentaire..." 
                                                class="flex-1 bg-transparent outline-none text-sm"
                                                onkeypress="if(event.key === 'Enter') addComment(1)">
                                            <button onclick="addComment(1)" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-2 rounded-full hover:shadow-md transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- Post 2 - Formation -->
                            <article class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden animate-scale-in" style="animation-delay: 0.1s">
                                <div class="p-5">
                                    <div class="flex items-center gap-3 mb-4">
                                        <img src="https://ui-avatars.com/api/?name=Sophie+Martin&background=ec4899&color=fff&size=48" class="w-12 h-12 rounded-full shadow">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900">Sophie Martin</span>
                                                <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-2 py-0.5 rounded-md font-medium">Admin</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                                <span>Hier à 16:45</span>
                                                <span>•</span>
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <p class="text-gray-700 mb-4">📊 <strong>Nouvelle formation exclusive disponible !</strong></p>
                                    
                                    <!-- Card Formation Premium -->
                                    <div class="relative overflow-hidden rounded-xl shadow-md">
                                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500"></div>
                                        <div class="relative p-6 text-white">
                                            <div class="flex items-start justify-between mb-4">
                                                <div>
                                                    <div class="flex gap-2 mb-3">
                                                        <span class="bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-semibold">Nouveau</span>
                                                        <span class="bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-semibold">Premium</span>
                                                    </div>
                                                    <h4 class="text-xl font-bold mb-2">Analyse Technique Avancée</h4>
                                                    <p class="text-white/90 text-sm">Maîtrisez les indicateurs et stratégies des professionnels</p>
                                                </div>
                                                <div class="bg-white/15 backdrop-blur-sm p-3 rounded-lg">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-3 gap-3 mb-4">
                                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2.5 text-center">
                                                    <div class="text-xl font-bold">12h</div>
                                                    <div class="text-xs opacity-80">de contenu</div>
                                                </div>
                                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2.5 text-center">
                                                    <div class="text-xl font-bold">50+</div>
                                                    <div class="text-xs opacity-80">vidéos</div>
                                                </div>
                                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2.5 text-center">
                                                    <div class="text-xl font-bold">4.9★</div>
                                                    <div class="text-xs opacity-80">notation</div>
                                                </div>
                                            </div>
                                            <button class="w-full bg-white text-indigo-600 px-5 py-3 rounded-lg text-sm font-bold hover:shadow-lg transform hover:scale-[1.02] transition flex items-center justify-center gap-2">
                                                Accéder à la formation
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stats et actions -->
                                <div class="border-t border-gray-100">
                                    <div class="px-5 py-2 flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <div class="flex -space-x-1">
                                                <span class="w-5 h-5 rounded-full bg-gradient-to-br from-red-400 to-pink-500 border-2 border-white"></span>
                                                <span class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 border-2 border-white"></span>
                                                <span class="w-5 h-5 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 border-2 border-white"></span>
                                            </div>
                                            <span id="like-count-2" class="font-medium">42</span>
                                        </div>
                                        <button onclick="toggleComments(2)" class="hover:underline font-medium">
                                            <span id="comment-count-2">1 commentaire</span>
                                        </button>
                                    </div>
                                    
                                    <div class="px-5 py-1 flex border-t border-gray-100">
                                        <button class="like-btn flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group" id="like-2" onclick="toggleLike(2)">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">J'aime</span>
                                        </button>
                                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group" onclick="toggleComments(2)">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">Commenter</span>
                                        </button>
                                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 rounded-lg transition group">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">Partager</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Commentaires -->
                                <div class="hidden border-t border-gray-200 bg-gray-50" id="comments-2">
                                    <div class="px-5 py-4 space-y-3 max-h-80 overflow-y-auto" id="comments-list-2"></div>
                                    
                                    <div class="px-5 pb-4 pt-3 border-t border-gray-200">
                                        <div class="comment-input flex gap-3 bg-white rounded-full p-2 shadow-sm border border-gray-200">
                                            <img src="https://ui-avatars.com/api/?name=Vous&background=6366f1&color=fff&size=36" class="w-9 h-9 rounded-full">
                                            <input type="text" 
                                                id="comment-input-2"
                                                placeholder="Ajoutez un commentaire..." 
                                                class="flex-1 bg-transparent outline-none text-sm"
                                                onkeypress="if(event.key === 'Enter') addComment(2)">
                                            <button onclick="addComment(2)" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-2 rounded-full hover:shadow-md transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            `;
            
            // Render existing comments
            renderComments(1);
            renderComments(2);
        }

        function backToList() {
            document.getElementById('sidebarSessions').classList.remove('mobile-hidden');
            document.getElementById('chatZone').classList.add('mobile-hidden');
            
            const chatZone = document.getElementById('chatZone');
            chatZone.innerHTML = `
                <div class="text-center max-w-md px-4 animate-fade-in">
                    <div class="mb-6 flex justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">Bienvenue sur vos Sessions</h2>
                    <p class="text-gray-500">
                        Sélectionnez une session pour découvrir les publications et participer aux discussions
                    </p>
                </div>
            `;
        }

        renderSessions();
    </script>

</body>
</html>