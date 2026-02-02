
// ========== CONFIGURATION ==========
let currentForumId = {{ $currentForum->id ?? 'null' }};
let isLoading = false;
let hasMorePosts = true;
let oldestPostId = null;
const postsPerPage = 30;

// ========== CHARGEMENT DES POSTS ==========
async function loadPosts(forumId, loadOlder = false) {
    if (isLoading || (!hasMorePosts && loadOlder)) return;
    
    isLoading = true;
    const loadingIndicator = showLoadingIndicator();
    
    try {
        const url = `/api/forums/${forumId}/posts${loadOlder && oldestPostId ? `?oldest_id=${oldestPostId}` : ''}`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            if (loadOlder) {
                // Ajouter en haut (posts plus anciens)
                prependPosts(data.posts);
            } else {
                // Remplacer tous les posts (nouveau canal)
                replacePosts(data.posts);
            }
            
            hasMorePosts = data.has_more;
            
            // Mettre à jour l'ID du post le plus ancien
            if (data.posts.length > 0) {
                oldestPostId = data.posts[data.posts.length - 1].id;
            }
        }
    } catch (error) {
        console.error('Erreur chargement posts:', error);
        showError('Impossible de charger les messages');
    } finally {
        isLoading = false;
        removeLoadingIndicator(loadingIndicator);
    }
}

// ========== AFFICHAGE DES POSTS ==========
function replacePosts(posts) {
    const container = document.getElementById('messagesContainer');
    container.innerHTML = '<div class="max-w-3xl mx-auto space-y-3" id="postsWrapper"></div>';
    const wrapper = document.getElementById('postsWrapper');
    
    posts.forEach(post => {
        wrapper.innerHTML += generatePostHTML(post);
    });
}

function prependPosts(posts) {
    const wrapper = document.getElementById('postsWrapper');
    const scrollBefore = wrapper.scrollHeight;
    
    // Inverser l'ordre pour afficher du plus ancien au plus récent
    posts.reverse().forEach(post => {
        wrapper.insertAdjacentHTML('afterbegin', generatePostHTML(post));
    });
    
    // Maintenir la position de scroll
    const scrollAfter = wrapper.scrollHeight;
    wrapper.scrollTop = scrollAfter - scrollBefore;
}

// ========== GÉNÉRATION HTML DES POSTS ==========
function generatePostHTML(post) {
    const userInitials = post.user.name.substring(0, 2).toUpperCase();
    const plaqueHTML = post.plaque && post.plaque !== 'Aucune' ? 
        `<span class="px-2 py-0.5 text-xs rounded-full font-medium ${getPlaqueClass(post.plaque)}">${post.plaque}</span>` : '';
    
    // POST TEXTE
    if (post.type === 'text') {
        return `
            <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm">
                    ${userInitials}
                </div>
                <div class="flex-1 max-w-xl">
                    <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-semibold text-sm">${post.user.name}</span>
                            ${plaqueHTML}
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed mb-2">${escapeHtml(post.content)}</p>
                        <span class="text-xs text-gray-500">${post.created_at_human}</span>
                    </div>
                    
                    <!-- Reactions -->
                    <div class="flex items-center gap-3 mt-2 ml-2">
                        <button class="flex items-center gap-1 text-xs text-gray-500 hover:text-indigo-600 transition"
                            onclick="handleLike(this, ${post.id}, '${post.source}')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>${post.likes_count}</span>
                        </button>
                        <button class="flex items-center gap-1 text-xs text-gray-500 hover:text-indigo-600 transition"
                            onclick="toggleComments(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span>${post.comments_count}</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    // POST MEDIA
    if (post.type === 'media') {
        const mediaCount = post.media.length;
        const gridClass = mediaCount === 1 ? 'grid-cols-1' : 'grid-cols-2';
        const mediaHTML = post.media.map(m => `
            <div class="aspect-square rounded-lg overflow-hidden cursor-pointer">
                <img src="${m.path}" alt="Media" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            </div>
        `).join('');
        
        return `
            <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm">
                    ${userInitials}
                </div>
                <div class="flex-1 max-w-xl">
                    <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-semibold text-sm">${post.user.name}</span>
                            ${plaqueHTML}
                        </div>
                        ${post.content ? `<p class="text-sm text-gray-700 leading-relaxed mb-3">${escapeHtml(post.content)}</p>` : ''}
                        <div class="grid ${gridClass} gap-2 rounded-lg overflow-hidden">
                            ${mediaHTML}
                        </div>
                        <span class="text-xs text-gray-500 mt-2 block">${post.created_at_human}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    // POST SONDAGE
    if (post.type === 'sondage' && post.poll) {
        const totalVotes = post.poll.total_votes;
        const optionsHTML = post.poll.options.map(option => {
            const percentage = totalVotes > 0 ? Math.round((option.votes / totalVotes) * 100) : 0;
            return `
                <div class="relative border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition"
                    onclick="votePoll(${post.poll.id}, ${option.id}, this)">
                    <div class="absolute inset-0 bg-indigo-100 rounded-lg transition-all" style="width: ${percentage}%; opacity: 0.3;"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="text-sm font-medium">${escapeHtml(option.option)}</span>
                        <span class="text-xs text-gray-600 font-semibold">${percentage}%</span>
                    </div>
                </div>
            `;
        }).join('');
        
        return `
            <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm">
                    ${userInitials}
                </div>
                <div class="flex-1 max-w-xl">
                    <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold text-sm">${post.user.name}</span>
                            <span class="text-xs font-semibold text-indigo-600 uppercase">Sondage</span>
                        </div>
                        <h3 class="font-semibold text-base mb-2">${escapeHtml(post.poll.question)}</h3>
                        ${post.content ? `<p class="text-sm text-gray-600 mb-3">${escapeHtml(post.content)}</p>` : ''}
                        <div class="space-y-2 mb-3">${optionsHTML}</div>
                        <p class="text-xs text-gray-500">
                            <span class="font-semibold">${totalVotes}</span> vote${totalVotes > 1 ? 's' : ''} • ${post.created_at_human}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    return '';
}

// ========== SCROLL INFINI ==========
const messagesContainer = document.getElementById('messagesContainer');
messagesContainer.addEventListener('scroll', function() {
    // Charger plus de posts quand on arrive en haut
    if (this.scrollTop < 200 && !isLoading && hasMorePosts) {
        loadPosts(currentForumId, true);
    }
});

// ========== CHANGEMENT DE CANAL ==========
function switchChannel(element, channelId, channelName, channelImage) {
    // Retirer la classe active
    document.querySelectorAll('.channel-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    // Mettre à jour le header
    const headerImage = document.querySelector('.bg-white.border-b img');
    const headerTitle = document.querySelector('.bg-white.border-b h2');
    if (headerImage) headerImage.src = channelImage;
    if (headerTitle) headerTitle.textContent = channelName;
    
    // Réinitialiser et charger les nouveaux posts
    currentForumId = channelId;
    oldestPostId = null;
    hasMorePosts = true;
    
    loadPosts(channelId, false);
    
    // Fermer sidebar sur mobile
    if (window.innerWidth < 768) {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    }
}

// ========== UTILITAIRES ==========
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getPlaqueClass(plaque) {
    const classes = {
        'diamond': 'bg-cyan-100 text-cyan-700',
        'gold': 'bg-amber-100 text-amber-700',
        'silver': 'bg-gray-200 text-gray-700',
        'bronze': 'bg-orange-100 text-orange-700'
    };
    return classes[plaque] || '';
}

function showLoadingIndicator() {
    const loader = document.createElement('div');
    loader.className = 'flex justify-center py-4';
    loader.innerHTML = '<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>';
    document.getElementById('postsWrapper')?.prepend(loader);
    return loader;
}

function removeLoadingIndicator(loader) {
    loader?.remove();
}

function showError(message) {
    alert(message); // Ou utiliser un système de notification plus élégant
}

// ========== CHARGEMENT INITIAL ==========
if (currentForumId) {
    loadPosts(currentForumId, false);
}
// ==================== FONCTIONS DE GÉNÉRATION DES POSTS ====================

/**
 * Génère un post de type TEXTE
 * @param {Object} post - Les données du post
 * @returns {string} - HTML du post
 */


/**
 * Génère un post de type MEDIA (images/vidéos)
 * @param {Object} post - Les données du post
 * @returns {string} - HTML du post
 */


/**
 * Génère un post de type FICHIER
 * @param {Object} post - Les données du post
 * @returns {string} - HTML du post
 */


/**
 * Génère un post de type SONDAGE
 * @param {Object} post - Les données du post
 * @returns {string} - HTML du post
 */


/**
 * Génère la section des réactions
 * @param {Object} post - Les données du post
 * @returns {string} - HTML des réactions
 */


/**
 * Fonction principale pour rendre un post selon son type
 * @param {Object} post - Les données du post
 * @returns {string} - HTML du post
 */


/**
 * Fonction utilitaire pour échapper le HTML
 * @param {string} text - Texte à échapper
 * @returns {string} - Texte échappé
 */


// ==================== EXEMPLE D'UTILISATION ====================

/**
 * Exemple de données de posts
 */
const examplePosts = [
    {
        id: 1,
        type: 'text',
        content: 'Bonjour à tous ! 👋 Je viens de terminer ma première semaine de trading.',
        user: {
            id: 1,
            name: 'Jean Dupont',
            initials: 'JD',
            color: 'blue',
            avatar: null
        },
        created_at_human: 'Il y a 1 heure',
        likes_count: 24,
        comments_count: 8,
        is_liked: false,
        comments: [
            {
                id: 1,
                content: 'Félicitations ! 🎉',
                user: { id: 2, name: 'Marie A', avatar: null },
                created_at_human: 'Il y a 30min'
            }
        ]
    },
    {
        id: 2,
        type: 'media',
        content: 'Bien sûr ! Je t\'envoie quelques images ! 📸',
        user: {
            id: 1,
            name: 'Jean Dupont',
            initials: 'JD',
            color: 'cyan',
            avatar: null
        },
        media: [
            { id: 1, type: 'image', url: 'https://via.placeholder.com/400', thumbnail: null },
            { id: 2, type: 'image', url: 'https://via.placeholder.com/400', thumbnail: null },
            { id: 3, type: 'image', url: 'https://via.placeholder.com/400', thumbnail: null },
            { id: 4, type: 'image', url: 'https://via.placeholder.com/400', thumbnail: null }
        ],
        created_at_human: 'Il y a 1 heure',
        likes_count: 12,
        comments_count: 4,
        is_liked: false,
        comments: []
    },
    {
        id: 3,
        type: 'file',
        content: 'Guide complet sur le trading algorithmique 📄',
        user: {
            id: 3,
            name: 'Thomas Martin',
            initials: 'TM',
            color: 'indigo',
            avatar: null
        },
        file: {
            name: 'Guide_Trading_2024.pdf',
            size: '2.4 MB',
            url: '/files/guide.pdf'
        },
        created_at_human: 'Il y a 3 heures',
        likes_count: 78,
        comments_count: 23,
        is_liked: true,
        comments: []
    },
    {
        id: 4,
        type: 'sondage',
        user: {
            id: 4,
            name: 'Admin Coach',
            initials: 'AC',
            color: 'purple',
            avatar: null
        },
        poll: {
            question: 'Quel est votre style de trading préféré ?',
            options: [
                { id: 1, option: 'Day Trading', votes: 70 },
                { id: 2, option: 'Swing Trading', votes: 47 },
                { id: 3, option: 'Scalping', votes: 23 },
                { id: 4, option: 'Position Trading', votes: 16 }
            ]
        },
        created_at_human: 'Il y a 5 heures',
        likes_count: 34,
        comments_count: 18,
        is_liked: false,
        comments: []
    }
];

/**
 * Variables globales
 */
let currentUserId = 1; // ID de l'utilisateur connecté
let currentUser = {
    id: 1,
    name: 'Utilisateur actuel',
    avatar: null
};
