// ==================== GESTION DU MENU MOBILE ====================
const backButton = document.getElementById('backButton');
const sidebar = document.getElementById('sidebar');
const channelItems = document.querySelectorAll('.channel-item');

// Initialement, afficher la sidebar sur mobile
if (window.innerWidth < 768) {
    sidebar.classList.remove('-translate-x-full');
}

backButton?.addEventListener('click', () => {
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

function toggleChannelInfo() {
    const modal = document.getElementById('channelInfoModal');
    
    if (currentChannelData) {
        document.getElementById('channelInfoImage').src = currentChannelData.image || '';
        document.getElementById('channelInfoName').textContent = currentChannelData.name || '';
        document.getElementById('channelInfoDescription').textContent = currentChannelData.description || 'Canal de discussion';
        document.getElementById('channelInfoMembers').textContent = currentChannelData.members || '0 membres';
        document.getElementById('channelInfoOnline').textContent = (currentChannelData.online || '0') + ' en ligne';
    }
    
    modal.classList.add('active');
}

function closeChannelInfo() {
    document.getElementById('channelInfoModal').classList.remove('active');
}

// ==================== VARIABLES GLOBALES ====================
let currentUserId = null;
let currentUser = null;
let currentChannelData = null;
let isFirstChannelLoad = true;
let autoRefreshInterval = null;

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
    // Déterminer si on change de canal ou si on recharge le même
    const isSameChannel = currentChannelData && currentChannelData.id === channelId;
    const keepScroll = isSameChannel && !isFirstChannelLoad;
    
    // Retirer la classe active
    document.querySelectorAll('.channel-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');

    // Mettre à jour le header
    const headerImage = document.getElementById('channelHeaderImage');
    const headerTitle = document.getElementById('channelHeaderName');
    if (headerImage) headerImage.src = channelImage;
    if (headerTitle) headerTitle.textContent = channelName;

    // Stocker les données du canal
    currentChannelData = {
        id: channelId,
        name: channelName,
        image: channelImage,
        description: element.dataset.channelDescription || 'Canal de discussion',
        members: element.dataset.channelMembers || '0',
        online: element.dataset.channelOnline || '0'
    };

    // Afficher loader uniquement si ce n'est pas un rechargement
    const container = document.getElementById('messagesContainer');
    if (container && !keepScroll) {
        container.innerHTML = `
            <div class="max-w-4xl mx-auto px-4 py-6">
                <div class="flex justify-center items-center min-h-[400px]">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                        <p class="text-gray-500 text-sm">Chargement des messages...</p>
                    </div>
                </div>
            </div>
        `;
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
        displayAllContent(result.posts, result.forum, keepScroll);
        
        // Marquer que le premier chargement est terminé
        if (isFirstChannelLoad) {
            isFirstChannelLoad = false;
        }
    } else {
        if (container) {
            container.innerHTML = `
                <div class="max-w-4xl mx-auto px-4 py-6">
                    <div class="flex justify-center items-center min-h-[400px]">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-red-500 font-medium">Erreur de chargement</p>
                            <p class="text-gray-500 text-sm mt-2">Impossible de charger les messages</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }
}

// ==================== AFFICHAGE DU CONTENU ====================
function displayAllContent(posts, forumTopics, keepScrollPosition = false) {
    const container = document.getElementById('messagesContainer');
    if (!container) {
        console.error('Container #messagesContainer non trouvé');
        return;
    }

    // Sauvegarder la position de scroll avant de mettre à jour
    const scrollHeightBefore = container.scrollHeight;
    const scrollTopBefore = container.scrollTop;
    const isAtBottom = (scrollHeightBefore - scrollTopBefore - container.clientHeight) < 50;

    // Fusionner posts et forum topics
    let allContent = [];

    // Ajouter les POSTS (table posts)
    if (posts && posts.length > 0) {
        posts.forEach(post => {
            allContent.push({
                id: post.id,
                type: post.type || 'text',
                content: post.content,
                user: {
                    id: post.user.id,
                    name: post.user.full_name || post.user.name,
                    avatar: post.user.avatar || null,
                    initials: getInitials(post.user.full_name || post.user.name),
                    color: getRandomColor()
                },
                media: post.media || [],
                poll: post.poll || null,
                created_at: post.created_at,
                created_at_human: formatDate(post.created_at),
                likes_count: post.likes_count || 0,
                is_liked: false,
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
                    color: getRandomColor()
                },
                media: [],
                poll: null,
                created_at: topic.created_at,
                created_at_human: formatDate(topic.created_at),
                likes_count: 0,
                is_liked: false,
                source: 'topics'
            });
        });
    }

    // Trier par date (du plus ancien au plus récent)
    allContent.sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });

    // Afficher
    if (allContent.length === 0) {
        container.innerHTML = `
            <div class="max-w-4xl mx-auto px-4 py-6">
                <div class="flex justify-center items-center min-h-[400px]">
                    <div class="text-center">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Aucun message</p>
                        <p class="text-gray-400 text-sm mt-2">Soyez le premier à publier dans ce canal</p>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    // Créer le HTML des posts dans un container centré
    const postsHTML = allContent.map(item => renderPost(item)).join('');
    container.innerHTML = `
        <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">
            ${postsHTML}
        </div>
    `;
    
    // Gérer le scroll
    setTimeout(() => {
        if (keepScrollPosition && !isAtBottom) {
            // Garder la position relative si on n'était pas en bas
            const scrollHeightAfter = container.scrollHeight;
            const scrollDifference = scrollHeightAfter - scrollHeightBefore;
            container.scrollTop = scrollTopBefore + scrollDifference;
        } else {
            // Scroll vers le bas (premier chargement ou si on était déjà en bas)
            container.scrollTop = container.scrollHeight;
        }
    }, 100);
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
    const isOwner = post.user.id === currentUserId;
    
    return `
        <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                ${post.user.avatar 
                    ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover" alt="${escapeHtml(post.user.name)}">`
                    : `<div class="w-10 h-10 bg-gradient-to-br from-${post.user.color}-500 to-${post.user.color}-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`
                }
            </div>
            
            <!-- Message Bubble -->
            <div class="flex-1 min-w-0">
                <div class="post-bubble p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                            <span class="text-xs text-gray-500">${post.created_at_human}</span>
                        </div>
                        ${isOwner ? `
                        <div class="relative">
                            <button onclick="toggleMenu(this)" class="p-1 hover:bg-gray-100 rounded transition">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border py-1 z-10">
                                <button onclick="editPost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">Modifier</button>
                                <button onclick="deletePost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Content -->
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap break-words">${escapeHtml(post.content)}</p>
                </div>
                
                <!-- Reactions -->
                ${generateReactions(post)}
            </div>
        </div>
    `;
}

function generateMediaPost(post) {
    const isOwner = post.user.id === currentUserId;
    
    let mediaHTML = '';
    const mediaCount = post.media.length;
    
    if (mediaCount === 1) {
        const media = post.media[0];
        mediaHTML = `
            <div class="rounded-lg overflow-hidden mb-3 cursor-pointer hover:opacity-95 transition">
                <img src="/store/${store}" class="w-full max-h-96 object-cover" alt="Image">
            </div>
        `;
    } else if (mediaCount === 2) {
        mediaHTML = `<div class="grid grid-cols-2 gap-2 rounded-lg overflow-hidden mb-3">`;
        post.media.forEach(media => {
            mediaHTML += `
                <div class="aspect-square cursor-pointer hover:opacity-95 transition">
                    <img src="/store/${media.path}" class="w-full h-full object-cover" alt="Image">
                </div>
            `;
        });
        mediaHTML += `</div>`;
    } else if (mediaCount > 2) {
        mediaHTML = `<div class="grid grid-cols-2 gap-2 rounded-lg overflow-hidden mb-3">`;
        post.media.slice(0, 4).forEach((media, index) => {
            if (index === 3 && mediaCount > 4) {
                mediaHTML += `
                    <div class="aspect-square relative cursor-pointer">
                        <img src="/store/${media.path}" class="w-full h-full object-cover" alt="Image">
                        <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">+${mediaCount - 4}</span>
                        </div>
                    </div>
                `;
            } else {
                mediaHTML += `
                    <div class="aspect-square cursor-pointer hover:opacity-95 transition">
                        <img src="/store/${media.path}" class="w-full h-full object-cover" alt="Image">
                    </div>
                `;
            }
        });
        mediaHTML += `</div>`;
    }
    
    return `
        <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
            <div class="flex-shrink-0">
                ${post.user.avatar 
                    ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover" alt="${escapeHtml(post.user.name)}">`
                    : `<div class="w-10 h-10 bg-gradient-to-br from-${post.user.color}-500 to-${post.user.color}-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`
                }
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="post-bubble p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                            <span class="text-xs text-gray-500">${post.created_at_human}</span>
                        </div>
                        ${isOwner ? `
                        <div class="relative">
                            <button onclick="toggleMenu(this)" class="p-1 hover:bg-gray-100 rounded transition">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border py-1 z-10">
                                <button onclick="deletePost(${post.id}, '${post.source}')" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    ${post.content ? `<p class="text-sm text-gray-700 leading-relaxed mb-3 whitespace-pre-wrap break-words">${escapeHtml(post.content)}</p>` : ''}
                    ${mediaHTML}
                </div>
                
                ${generateReactions(post)}
            </div>
        </div>
    `;
}

function generatePollPost(post) {
    const isOwner = post.user.id === currentUserId;
    const totalVotes = post.poll.options.reduce((sum, opt) => sum + opt.votes, 0);
    const maxVotes = Math.max(...post.poll.options.map(o => o.votes));
    
    let optionsHTML = '';
    post.poll.options.forEach((option) => {
        const percentage = totalVotes > 0 ? Math.round((option.votes / totalVotes) * 100) : 0;
        const isLeading = option.votes === maxVotes && maxVotes > 0;
        
        optionsHTML += `
            <div class="relative border ${isLeading ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200 bg-white'} rounded-lg p-3 cursor-pointer hover:shadow-sm transition">
                <div class="absolute inset-0 bg-indigo-100 rounded-lg transition-all duration-300" style="width: ${percentage}%; opacity: 0.3;"></div>
                <div class="relative flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900 break-words pr-2">${escapeHtml(option.option)}</span>
                    <span class="text-xs font-semibold ${isLeading ? 'text-indigo-600' : 'text-gray-600'} flex-shrink-0">${percentage}%</span>
                </div>
                ${option.votes > 0 ? `<div class="relative text-xs text-gray-500 mt-1">${option.votes} vote${option.votes > 1 ? 's' : ''}</div>` : ''}
            </div>
        `;
    });
    
    return `
        <div class="flex gap-3 items-start" data-post-id="${post.id}" data-post-source="${post.source}">
            <div class="flex-shrink-0">
                ${post.user.avatar 
                    ? `<img src="${post.user.avatar}" class="w-10 h-10 rounded-full object-cover" alt="${escapeHtml(post.user.name)}">`
                    : `<div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">${post.user.initials}</div>`
                }
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="post-bubble p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-sm text-gray-900">${escapeHtml(post.user.name)}</span>
                            <span class="text-xs text-gray-500">${post.created_at_human}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-semibold text-indigo-600 uppercase">Sondage</span>
                    </div>
                    
                    <h3 class="font-semibold text-base text-gray-900 mb-4 break-words">${escapeHtml(post.poll.question)}</h3>
                    
                    <div class="space-y-2 mb-3">${optionsHTML}</div>
                    
                    <div class="text-xs text-gray-500 pt-2 border-t border-gray-100">
                        ${totalVotes} vote${totalVotes > 1 ? 's' : ''} • ${post.created_at_human}
                    </div>
                </div>
                
                ${generateReactions(post)}
            </div>
        </div>
    `;
}

function generateReactions(post) {
    return `
        <div class="flex items-center gap-4 mt-2 px-2">
            <button onclick="toggleLike(${post.id}, '${post.source}', this)" class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-red-500 transition group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span>${post.likes_count}</span>
            </button>
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

function getRandomColor() {
    const colors = ['blue', 'purple', 'pink', 'indigo', 'cyan', 'teal', 'green', 'orange'];
    return colors[Math.floor(Math.random() * colors.length)];
}

function formatDate(date) {
    if (!date) return 'À l\'instant';
    
    const now = new Date();
    const postDate = new Date(date);
    const diffMs = now - postDate;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'À l\'instant';
    if (diffMins < 60) return `Il y a ${diffMins} min`;
    if (diffHours < 24) return `Il y a ${diffHours}h`;
    if (diffDays < 7) return `Il y a ${diffDays}j`;
    
    return postDate.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
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
    
    svg.classList.add('animate-ping');
    setTimeout(() => svg.classList.remove('animate-ping'), 600);
    
    console.log('Like:', { postId, source });
}

function editPost(postId, source) {
    console.log('Modifier:', { postId, source });
}

function deletePost(postId, source) {
    if (confirm('Supprimer ce post ?')) {
        console.log('Supprimer:', { postId, source });
    }
}

// ==================== MESSAGE RAPIDE ====================
function handleMessageKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendQuickMessage();
    }
}

async function sendQuickMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    console.log('Envoi message rapide:', {
        channel: currentChannelData?.id,
        message: message
    });
    
    // TODO: Implémenter l'envoi API
    
    input.value = '';
}

// ==================== MODALS ====================
function openPollModal() {
    document.getElementById('pollModal').classList.add('active');
    document.getElementById('pollQuestion').focus();
}

function closePollModal() {
    document.getElementById('pollModal').classList.remove('active');
    document.getElementById('pollQuestion').value = '';
    document.getElementById('pollOptions').innerHTML = `
        <input type="text" class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Option 1">
        <input type="text" class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Option 2">
    `;
}

function addPollOption() {
    const container = document.getElementById('pollOptions');
    const optionCount = container.querySelectorAll('.poll-option-input').length;
    
    if (optionCount >= 10) {
        alert('Maximum 10 options');
        return;
    }
    
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg';
    input.placeholder = `Option ${optionCount + 1}`;
    
    container.appendChild(input);
    input.focus();
}

async function publishPoll() {
    const question = document.getElementById('pollQuestion').value.trim();
    const options = Array.from(document.querySelectorAll('.poll-option-input'))
        .map(input => input.value.trim())
        .filter(val => val);
    
    if (!question || options.length < 2) {
        alert('Veuillez remplir la question et au moins 2 options');
        return;
    }
    
    console.log('Publier sondage:', {
        channel: currentChannelData?.id,
        question: question,
        options: options
    });
    
    // TODO: Implémenter la publication
    
    closePollModal();
}

// ==================== RECHARGEMENT AUTOMATIQUE ====================
function startAutoRefresh(intervalSeconds = 8000) {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    
    autoRefreshInterval = setInterval(async () => {
        if (currentChannelData && currentChannelData.id) {
            console.log('🔄 Rechargement automatique...');
            const result = await getPosts(currentChannelData.id);
            
            if (result.success) {
                displayAllContent(result.posts, result.forum, true);
            }
        }
    }, intervalSeconds * 1000);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    }
}

async function refreshMessages() {
    if (!currentChannelData || !currentChannelData.id) return;
    
    console.log('🔄 Rechargement manuel...');
    const result = await getPosts(currentChannelData.id);
    
    if (result.success) {
        displayAllContent(result.posts, result.forum, true);
    }
}

// ==================== CHARGEMENT INITIAL ====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé - Initialisation...');
    
    const activeChannel = document.querySelector('.channel-item.active');
    if (activeChannel) {
        const channelId = activeChannel.dataset.channelId;
        const channelName = activeChannel.dataset.channelName || '';
        const channelImage = activeChannel.querySelector('img')?.src || '';
        
        console.log('Chargement initial du canal:', channelId);
        switchChannel(activeChannel, channelId, channelName, channelImage);
        
        // Démarrer le rafraîchissement automatique
        startAutoRefresh(30);
    } else {
        console.warn('Aucun canal actif trouvé');
    }
    
    // Fermer les modals en cliquant à l'extérieur
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
});

// Arrêter l'auto-refresh quand on quitte la page
window.addEventListener('beforeunload', () => {
    stopAutoRefresh();
});