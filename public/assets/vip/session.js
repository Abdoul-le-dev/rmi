
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
                    <img src="${comment.avatar}" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm">${comment.author}</span>
                                <span class="text-xs text-gray-500">${comment.time}</span>
                            </div>
                            <p class="text-sm text-gray-700">${comment.text}</p>
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
                <div class="flex-1 flex flex-col bg-white animate-fade-in">
                    <!-- Header Session -->
                    <div class="bg-gradient-to-r from-white to-gray-50 border-b border-gray-200 p-4 flex justify-between items-center shadow-sm">
                        <div class="flex items-center gap-3">
                            <button class="md:hidden p-2 hover:bg-gray-100 rounded-full transition-all duration-300" onclick="backToList()">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br ${getAvatarColor(sessionId - 1)} flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                ${session.avatar}
                            </div>
                            <div>
                                <h2 class="font-bold flex items-center gap-2">
                                    ${session.name}
                                    <span class="bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-semibold">Session</span>
                                </h2>
                                <p class="text-xs text-gray-500">Publication par les admins • ${Math.floor(Math.random() * 500) + 100} membres</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-full transition-all duration-300">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Posts -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                        <!-- Post 1 -->
                        <div class="bg-white rounded-2xl shadow-sm hover-lift transition-all duration-300 overflow-hidden animate-scale-in">
                            <div class="p-5">
                                <div class="flex items-start gap-3 mb-4">
                                    <img src="https://ui-avatars.com/api/?name=Admin+Trading&background=6366f1&color=fff&size=48" class="w-12 h-12 rounded-full shadow-md">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-gray-900">Admin Trading</span>
                                            <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm">Admin</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Aujourd'hui à 14:30</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    Bienvenue dans la session de formation Trading 2025 ! 🎯<br><br>
                                    Cette session est dédiée aux annonces importantes et aux formations exclusives. Profitez des ressources partagées et n'hésitez pas à commenter pour enrichir les discussions ! 💬
                                </p>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-6 pt-3 border-t border-gray-100">
                                    <button class="like-btn flex items-center gap-2 text-gray-600 hover:text-red-500 transition-all duration-300" id="like-1" onclick="toggleLike(1)">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                        </svg>
                                        <span class="font-semibold" id="like-count-1">24</span>
                                    </button>
                                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-all duration-300" onclick="toggleComments(1)">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold" id="comment-count-1">2 commentaires</span>
                                    </button>
                                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-all duration-300 ml-auto">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Section Commentaires -->
                            <div class="hidden border-t border-gray-100 bg-gray-50 p-5" id="comments-1">
                                <div class="space-y-3 mb-4" id="comments-list-1"></div>
                                
                                <!-- Input commentaire -->
                                <div class="comment-input flex gap-3 bg-white rounded-full p-2 transition-all duration-300">
                                    <img src="https://ui-avatars.com/api/?name=Vous&background=6366f1&color=fff&size=36" class="w-9 h-9 rounded-full">
                                    <input type="text" 
                                        id="comment-input-1"
                                        placeholder="Écrire un commentaire..." 
                                        class="flex-1 bg-transparent outline-none text-sm px-2"
                                        onkeypress="if(event.key === 'Enter') addComment(1)">
                                    <button onclick="addComment(1)" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-2 rounded-full hover:shadow-lg transition-all duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Post 2 -->
                        <div class="bg-white rounded-2xl shadow-sm hover-lift transition-all duration-300 overflow-hidden animate-scale-in" style="animation-delay: 0.1s">
                            <div class="p-5">
                                <div class="flex items-start gap-3 mb-4">
                                    <img src="https://ui-avatars.com/api/?name=Sophie+Martin&background=ec4899&color=fff&size=48" class="w-12 h-12 rounded-full shadow-md">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-gray-900">Sophie Martin</span>
                                            <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm">Admin</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Hier à 16:45</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    📊 Nouvelle formation disponible : Analyse technique avancée
                                </p>
                                
                                <!-- Card Formation -->
                                <div class="bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-xl p-5 text-white shadow-lg mb-4">
                                    <h4 class="font-bold text-lg mb-2">Formation Analyse Technique</h4>
                                    <p class="text-sm opacity-90 mb-4">Maîtrisez les indicateurs et patterns de trading professionnels</p>
                                    <button class="bg-white text-indigo-600 px-5 py-2.5 rounded-lg text-sm font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        Accéder à la formation →
                                    </button>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-6 pt-3 border-t border-gray-100">
                                    <button class="like-btn flex items-center gap-2 text-gray-600 hover:text-red-500 transition-all duration-300" id="like-2" onclick="toggleLike(2)">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                        </svg>
                                        <span class="font-semibold" id="like-count-2">42</span>
                                    </button>
                                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-all duration-300" onclick="toggleComments(2)">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold" id="comment-count-2">1 commentaire</span>
                                    </button>
                                    <button class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-all duration-300 ml-auto">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Section Commentaires -->
                            <div class="hidden border-t border-gray-100 bg-gray-50 p-5" id="comments-2">
                                <div class="space-y-3 mb-4" id="comments-list-2"></div>
                                
                                <!-- Input commentaire -->
                                <div class="comment-input flex gap-3 bg-white rounded-full p-2 transition-all duration-300">
                                    <img src="https://ui-avatars.com/api/?name=Vous&background=6366f1&color=fff&size=36" class="w-9 h-9 rounded-full">
                                    <input type="text" 
                                        id="comment-input-2"
                                        placeholder="Écrire un commentaire..." 
                                        class="flex-1 bg-transparent outline-none text-sm px-2"
                                        onkeypress="if(event.key === 'Enter') addComment(2)">
                                    <button onclick="addComment(2)" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-2 rounded-full hover:shadow-lg transition-all duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
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
