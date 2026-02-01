// Gestion du menu mobile avec bouton retour
const backButton = document.getElementById('backButton');
const sidebar = document.getElementById('sidebar');
const channelItems = document.querySelectorAll('.channel-item');




// Initialement, afficher la sidebar sur mobile
if (window.innerWidth < 768) {
    sidebar.classList.remove('-translate-x-full');
}

backButton.addEventListener('click', () => {
    if (window.innerWidth < 768) {
        sidebar.classList.remove('-translate-x-full');
    }
});

channelItems.forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }
    });
});

function toggleSidebarSearch() {
    const searchBox = document.getElementById('sidebarSearchBox');
    searchBox.classList.toggle('hidden');
    searchBox.classList.toggle('md:block');
    if (!searchBox.classList.contains('hidden')) {
        searchBox.querySelector('input').focus();
    }
}

function toggleSearchBox() {
    const searchBox = document.getElementById('searchBox');
    searchBox.classList.toggle('hidden');
    if (!searchBox.classList.contains('hidden')) {
        document.getElementById('channelSearch').focus();
    }
}

function toggleChannelInfo() {
    const modal = document.getElementById('channelInfoModal');

    // Remplir les informations du canal actuel
    document.getElementById('channelInfoImage').src = currentChannelData.image;
    document.getElementById('channelInfoName').textContent = currentChannelData.name;
    document.getElementById('channelInfoDescription').textContent = currentChannelData.description;
    

    modal.classList.add('active');
}
function closeChannelInfo() {
    document.getElementById('channelInfoModal').classList.remove('active');
}

document.addEventListener('click', (e) => {
    const attachMenu = document.getElementById('attachMenu');
    const attachBtn = e.target.closest('button[onclick="openAttachMenu()"]');

    if (!attachMenu.contains(e.target) && !attachBtn) {
        attachMenu.classList.add('hidden');
    }
});
// ==================== VARIABLES GLOBALES ====================
let currentUserId = null;
let currentUser = null;
let currentChannelData = null;

// ==================== FONCTION CSRF ====================
function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// ==================== RÉCUPÉRATION DES POSTS ====================
async function getPosts(channelId) {
    try {
        const response = await fetch('/posts/fetch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                channel_id: channelId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        console.log('POSTS FETCHED:', data);

        return {
            success: true,
            posts: data.posts || [],
            forum: data.forum || [],
            current_user: data.current_user || null
        };

    } catch (error) {
        console.error('getPosts FAILED:', error);
        return {
            success: false,
            error: error.message,
            posts: [],
            forum: []
        };
    }
}

// ==================== SWITCH CHANNEL ====================
async function switchChannel(element, channelId, channelName, channelImage) {
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

    // Stocker les données du canal
    currentChannelData = {
        id: channelId,
        name: channelName,
        image: channelImage
    };

    // Afficher loader
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.innerHTML = '<div class="flex justify-center items-center py-12"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div></div>';
    }

    // Charger les données
    const result = await getPosts(channelId);

    if (result.success) {
        // Mettre à jour l'utilisateur connecté
        if (result.current_user && !currentUserId) {
            currentUserId = result.current_user.id;
            currentUser = result.current_user;
            console.log('User connecté:', currentUser);
        }

        // Fusionner posts + forum et afficher
        displayAllContent(result.posts, result.forum);
    } else {
        if (container) {
            container.innerHTML = '<div class="text-center py-12 text-red-500">Erreur de chargement</div>';
        }
    }
}

// ==================== AFFICHAGE DU CONTENU ====================
function displayAllContent(posts, forumTopics) {
    const container = document.getElementById('messagesContainer');
    if (!container) {
        console.error('Container #messagesContainer non trouvé');
        return;
    }

    // Fusionner posts et forum topics
    let allContent = [];

    // Ajouter les POSTS (table posts)
    if (posts && posts.length > 0) {
        posts.forEach(post => {
            allContent.push({
                id: post.id,
                type: post.type || 'text', // text, media, sondage
                content: post.content,
                user: {
                    id: post.user.id,
                    name: post.user.full_name || post.user.name,
                    avatar: post.user.avatar || null,
                    initials: getInitials(post.user.full_name || post.user.name),
                    color: 'blue'
                },
                media: post.media || [],
                poll: post.poll || null,
                created_at_human: formatDate(post.created_at),
                likes_count: post.likes_count || 0,
                comments_count: post.comments_count || 0,
                is_liked: false,
                comments: [],
                source: 'posts'
            });
        });
    }

    // Ajouter les FORUM TOPICS (table forum_topics)
    if (forumTopics && forumTopics.length > 0) {
        forumTopics.forEach(topic => {
            allContent.push({
                id: topic.id,
                type: 'text',
                content: topic.description || topic.title,
                user: {
                    id: topic.creator_id,
                    name: topic.creator?.full_name || topic.creator?.name || 'Utilisateur',
                    avatar: topic.creator?.avatar || null,
                    initials: getInitials(topic.creator?.full_name || topic.creator?.name || 'U'),
                    color: 'purple'
                },
                media: [],
                poll: null,
                created_at_human: formatDate(topic.created_at),
                likes_count: 0,
                comments_count: 0,
                is_liked: false,
                comments: [],
                source: 'topics'
            });
        });
    }

    // Trier par date (du plus récent au plus ancien)
    allContent.sort((a, b) => {
        // Vous pouvez améliorer le tri avec les vraies dates
        return 0; // Pour l'instant, garde l'ordre API
    });

    // Afficher
    if (allContent.length === 0) {
        container.innerHTML = '<div class="text-center py-12 text-gray-500">Aucun message pour le moment</div>';
        return;
    }

    container.innerHTML = allContent.map(item => renderPost(item)).join('');
}

// ==================== RENDU D'UN POST ====================
function renderPost(post) {
    switch (post.type) {
        case 'text':
            return generateTextPost(post);
        case 'media':
            return generateMediaPost(post);
        case 'sondage':
        case 'poll':
            return generatePollPost(post);
        default:
            return generateTextPost(post);
    }
}

function generateTextPost(post) {
    const avatar = post.user.avatar 
        ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="${post.user.name}">`
        : `<div class="w-10 h-10 bg-gradient-to-br from-${post.user.color}-500 to-${post.user.color}-600 rounded-full flex-shrink-0 flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`;
    
    const isOwner = post.user.id === currentUserId;
    
    return `
        <div class="flex gap-3 items-start mb-6" data-post-id="${post.id}" data-post-source="${post.source}">
            ${avatar}
            
            <div class="flex-1">
                <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                        ${isOwner ? `
                        <div class="relative">
                            <button onclick="toggleMenu(this)" class="p-1 hover:bg-gray-100 rounded">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border py-1 z-10" style="display: none;">
                                <button onclick="editPost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">Modifier</button>
                                <button onclick="deletePost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <p class="text-sm text-gray-700 leading-relaxed mb-2">${escapeHtml(post.content)}</p>
                    <span class="text-xs text-gray-500">${post.created_at_human}</span>
                </div>
                
                ${generateReactions(post)}
                ${generateCommentsSection(post)}
            </div>
        </div>
    `;
}

function generateMediaPost(post) {
    const avatar = post.user.avatar 
        ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="${post.user.name}">`
        : `<div class="w-10 h-10 bg-gradient-to-br from-${post.user.color}-500 to-${post.user.color}-600 rounded-full flex-shrink-0 flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`;
    
    const isOwner = post.user.id === currentUserId;
    
    let mediaHTML = '';
    const mediaCount = post.media.length;
    
    if (mediaCount === 1) {
        const media = post.media[0];
        mediaHTML = `
            <div class="rounded-lg overflow-hidden mb-2 cursor-pointer">
                <img src="/store/${media.path}" class="w-full" alt="Image">
            </div>
        `;
    } else if (mediaCount > 1) {
        mediaHTML = `<div class="grid grid-cols-2 gap-2 rounded-lg overflow-hidden mb-2">`;
        post.media.forEach(media => {
            mediaHTML += `
                <div class="aspect-square cursor-pointer">
                    <img src="/store/${media.path}" class="w-full h-full object-cover" alt="Image">
                </div>
            `;
        });
        mediaHTML += `</div>`;
    }
    
    return `
        <div class="flex gap-3 items-start mb-6" data-post-id="${post.id}" data-post-source="${post.source}">
            ${avatar}
            
            <div class="flex-1">
                <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                        ${isOwner ? `
                        <div class="relative">
                            <button onclick="toggleMenu(this)" class="p-1 hover:bg-gray-100 rounded">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border py-1 z-10" style="display: none;">
                                <button onclick="deletePost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    ${post.content ? `<p class="text-sm text-gray-700 leading-relaxed mb-3">${escapeHtml(post.content)}</p>` : ''}
                    ${mediaHTML}
                    <span class="text-xs text-gray-500">${post.created_at_human}</span>
                </div>
                
                ${generateReactions(post)}
                ${generateCommentsSection(post)}
            </div>
        </div>
    `;
}

function generatePollPost(post) {
    const avatar = post.user.avatar 
        ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="${post.user.name}">`
        : `<div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full flex-shrink-0 flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`;
    
    const isOwner = post.user.id === currentUserId;
    const totalVotes = post.poll.options.reduce((sum, opt) => sum + opt.votes, 0);
    
    let optionsHTML = '';
    post.poll.options.forEach((option) => {
        const percentage = totalVotes > 0 ? Math.round((option.votes / totalVotes) * 100) : 0;
        const isLeading = option.votes === Math.max(...post.poll.options.map(o => o.votes));
        
        optionsHTML += `
            <div class="relative border${isLeading ? '-2 border-blue-200 bg-blue-50' : ''} rounded-lg p-2.5 cursor-pointer ${isLeading ? '' : 'hover:bg-gray-50'}">
                <div class="absolute inset-0 bg-blue-100 rounded-lg" style="width: ${percentage}%; opacity: ${isLeading ? '0.3' : '0.2'};"></div>
                <div class="relative flex items-center justify-between">
                    <span class="text-sm">${escapeHtml(option.option)}</span>
                    <span class="text-xs font-${isLeading ? 'bold text-blue-700' : 'semibold text-gray-600'}">${percentage}%</span>
                </div>
            </div>
        `;
    });
    
    return `
        <div class="flex gap-3 items-start mb-6" data-post-id="${post.id}" data-post-source="${post.source}">
            ${avatar}
            
            <div class="flex-1">
                <div class="bg-white rounded-2xl rounded-tl-none shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs font-semibold text-blue-600 uppercase">Sondage</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-3">${escapeHtml(post.poll.question)}</h3>
                    </div>
                    
                    <div class="space-y-2 mb-3">${optionsHTML}</div>
                    <div class="text-xs text-gray-500">${totalVotes} vote${totalVotes > 1 ? 's' : ''} • ${post.created_at_human}</div>
                </div>
                
                ${generateReactions(post)}
                ${generateCommentsSection(post)}
            </div>
        </div>
    `;
}

function generateReactions(post) {
    return `
        <div class="flex items-center gap-3 mt-2 ml-2">
            <button onclick="toggleLike(${post.id}, '${post.source}', this)" class="flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span>${post.likes_count}</span>
            </button>
            
            <button onclick="toggleComments(${post.id}, this)" class="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>${post.comments_count}</span>
            </button>
            
            <button onclick="sharePost(${post.id})" class="flex items-center gap-1 text-xs text-gray-500 hover:text-green-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </button>
        </div>
    `;
}

function generateCommentsSection(post) {
    return `
        <div class="comments mt-3 ml-2 space-y-3" style="display: none;" data-post-id="${post.id}">
            <div class="flex gap-2">
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <input type="text" placeholder="Ajouter un commentaire..." 
                    class="flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
        </div>
    `;
}

// ==================== UTILITAIRES ====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getInitials(name) {
    if (!name) return 'U';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

function formatDate(date) {
    if (!date) return 'À l\'instant';
    // Format simple, vous pouvez améliorer
    return 'Il y a quelques instants';
}

// ==================== INTERACTIONS ====================
function toggleMenu(button) {
    const menu = button.nextElementSibling;
    const isVisible = menu.style.display === 'block';
    
    document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
    menu.style.display = isVisible ? 'none' : 'block';
    
    if (!isVisible) {
        setTimeout(() => {
            document.addEventListener('click', function closeMenu(e) {
                if (!button.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                    document.removeEventListener('click', closeMenu);
                }
            });
        }, 10);
    }
}

function toggleLike(postId, source, button) {
    const span = button.querySelector('span');
    const svg = button.querySelector('svg');
    const count = parseInt(span.textContent);
    
    if (button.classList.contains('text-red-500')) {
        button.classList.remove('text-red-500');
        svg.setAttribute('fill', 'none');
        span.textContent = count - 1;
    } else {
        button.classList.add('text-red-500');
        svg.setAttribute('fill', 'currentColor');
        span.textContent = count + 1;
    }
    
    console.log('Like:', { postId, source });
}

function toggleComments(postId, button) {
    const container = button.closest('.flex-1');
    const commentsSection = container.querySelector(`.comments[data-post-id="${postId}"]`);
    
    if (commentsSection) {
        commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
    }
}

function sharePost(postId) {
    console.log('Partager:', postId);
}

function editPost(postId, source) {
    console.log('Modifier:', { postId, source });
}

function deletePost(postId, source) {
    if (confirm('Supprimer ce post ?')) {
        console.log('Supprimer:', { postId, source });
    }
}

// ==================== CHARGEMENT INITIAL ====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé');
    
    const activeChannel = document.querySelector('.channel-item.active');
    if (activeChannel) {
        const channelId = activeChannel.dataset.channelId;
        console.log('Chargement initial du canal:', channelId);
        
        // Simuler un clic pour charger les posts
        const channelName = activeChannel.dataset.channelName || '';
        const channelImage = activeChannel.querySelector('img')?.src || '';
        switchChannel(activeChannel, channelId, channelName, channelImage);
    }
});