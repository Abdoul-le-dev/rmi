// CONFIG
const C = {
    REFRESH: 60000,  // ✅ 60 secondes au lieu de 10
    POSTS_URL: '/posts/fetch',
    VOTE_URL: '/polls/vote',
    DEL_URL: '/posts/delete',
    SHARE_URL: '/posts/share',
    DEFAULT_AVATAR: 'https://api.dicebear.com/7.x/avataaars/svg?seed='
};

const S = { 
    posts: [], 
    user: null, 
    voted: new Set(),
    isLoading: false,
    isActive: true
};

// UTILS
const $ = (s) => document.querySelector(s);
const csrf = () => $('meta[name="csrf-token"]').content;

// COOKIES
const Cookie = {
    get: (k) => (document.cookie.match(`(^|;)\\s*${k}\\s*=\\s*([^;]+)`) || [])[2],
    set: (k, v, d = 10) => {
        const e = new Date(Date.now() + d * 864e5);
        document.cookie = `${k}=${v};expires=${e.toUTCString()};path=/`;
    }
};

// API avec gestion d'erreur
const api = async (url, data) => {
    try {
        const r = await fetch(url, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return await r.json();
    } catch (e) {
        console.error('API Error:', e);
        return { success: false, error: e.message };
    }
};

// PERMISSIONS
const canDel = (p) => S.user && (S.user.role === 'admin' || p.user.id === S.user.id);

// TIME
const ago = (d) => {
    const s = ~~((Date.now() - new Date(d)) / 1000);
    return s < 60 ? '1min' : s < 3600 ? `${~~(s/60)}min` : s < 86400 ? `${~~(s/3600)}h` : `${~~(s/86400)}j`;
};

// VOTED
const hasVoted = (id) => {
    if (S.voted.has(id)) return true;
    const v = Cookie.get('votes');
    return v && JSON.parse(v).includes(id);
};

const addVote = (id) => {
    S.voted.add(id);
    const v = Cookie.get('votes');
    const arr = v ? JSON.parse(v) : [];
    if (!arr.includes(id)) {
        arr.push(id);
        Cookie.set('votes', JSON.stringify(arr));
    }
};

// RENDER
const avatar = (u) => u.avatar || `${C.DEFAULT_AVATAR}${encodeURIComponent(u.name)}`;

const badge = (p) => {
    const b = { bronze: '🥉', silver: '🥈', gold: '🥇', diamond: '💎', none: '⭐' };
    return `<span class="text-xl">${b[p] || '⭐'}</span>`;
};

const card = (u, p, m) => `
    <div class="absolute left-0 top-12 w-60 bg-white rounded-xl shadow-2xl p-4 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">
        <div class="flex items-center gap-3 mb-3">
            <img src="${avatar(u)}" class="w-12 h-12 rounded-full" onerror="this.src='${C.DEFAULT_AVATAR}${encodeURIComponent(u.name)}'">
            <div>
                <p class="font-bold text-sm text-gray-900">${u.name}</p>
                <p class="text-xs text-gray-500">${u.role}</p>
            </div>
        </div>
        <div class="flex items-center justify-between pt-2 border-t">
            <span class="text-sm font-semibold text-gray-700">${m.toFixed(2)}$</span>
            ${badge(p)}
        </div>
    </div>
`;

const media = (m) => {
    if (!m.length) return '';
    return `<div class="grid ${m.length > 1 ? 'grid-cols-2' : ''} gap-2 mb-3">${
        m.map(x => x.type === 'image' 
            ? `<img src="/storage/${x.path}" class="w-full rounded-lg cursor-pointer hover:opacity-95 transition" onclick="showMedia('${x.path}')" onerror="this.style.display='none'">`
            : `<video src="/storage/${x.path}" controls class="w-full rounded-lg" onerror="this.style.display='none'"></video>`
        ).join('')
    }</div>`;
};

const poll = (pl, pid) => {
    const voted = hasVoted(pl.id);
    const tot = pl.options.reduce((a, o) => a + o.votes, 0);
    
    return `<div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 mb-3">
        <p class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <span class="text-xl">📊</span>${pl.question}
        </p>
        <div class="space-y-2">${pl.options.map(o => {
            const pct = tot ? ~~(o.votes / tot * 100) : 0;
            return voted ? `
                <div class="relative bg-white rounded-lg p-3 overflow-hidden shadow-sm">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-10 transition-all duration-700" style="width:${pct}%"></div>
                    <div class="relative flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">${o.option}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">${o.votes}</span>
                            <span class="text-sm font-bold text-indigo-600">${pct}%</span>
                        </div>
                    </div>
                </div>
            ` : `
                <button onclick="vote(${pl.id}, ${o.id}, ${pid})" 
                        class="vote-btn w-full text-left bg-white border-2 border-gray-200 rounded-lg p-3 hover:border-indigo-400 hover:shadow-md transition-all duration-200">
                    <span class="text-sm font-medium text-gray-900">${o.option}</span>
                </button>
            `;
        }).join('')}</div>
        <p class="text-xs text-gray-500 mt-3">${tot} vote${tot > 1 ? 's' : ''}</p>
    </div>`;
};

const post = (p) => `
    <div class="post-card bg-white rounded-xl shadow-sm hover:shadow-md p-4 mb-3 transition-all duration-200" data-id="${p.id}">
        <div class="flex items-start gap-3 mb-3">
            <div class="relative group flex-shrink-0">
                <img src="${avatar(p.user)}" class="w-10 h-10 rounded-full ring-2 ring-gray-100" onerror="this.src='${C.DEFAULT_AVATAR}${encodeURIComponent(p.user.name)}'">
                ${card(p.user, p.plaque, p.montant)}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-gray-900 truncate">${p.user.name}</p>
                <p class="text-xs text-gray-500">${ago(p.created_at)}</p>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="share(${p.id})" class="p-2 hover:bg-gray-100 rounded-full transition" title="Partager">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                </button>
                ${canDel(p) ? `
                    <button onclick="del(${p.id})" class="p-2 hover:bg-red-50 rounded-full transition" title="Supprimer">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                ` : ''}
            </div>
        </div>
        
        ${p.content ? `<p class="text-gray-800 mb-3 whitespace-pre-wrap break-words">${p.content}</p>` : ''}
        ${p.type === 'media' && p.media?.length ? media(p.media) : ''}
        ${p.type === 'sondage' && p.poll ? poll(p.poll, p.id) : ''}
        
        <div class="flex items-center gap-4 pt-3 border-t border-gray-100 text-gray-500 text-sm">
            <span class="flex items-center gap-1 hover:text-indigo-600 cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                ${p.comments_count}
            </span>
            <span class="flex items-center gap-1 hover:text-red-500 cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                ${p.likes_count}
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                ${p.shares_count}
            </span>
        </div>
    </div>
`;

// ✅ LOAD OPTIMISÉ
const load = async () => {
    if (S.isLoading || !S.isActive) return;
    
    S.isLoading = true;
    
    try {
        const d = await api(C.POSTS_URL, { limit: 10 });
        
        if (!d.success) {
            console.error('Load failed:', d.error);
            return;
        }
        
        S.posts = d.posts;
        S.user = d.current_user;
        
        const c = $('#posts');
        if (!c) return;
        
        // Sauvegarder le scroll
        const scroll = window.scrollY;
        
        // Render sans flash
        c.innerHTML = d.posts.map(post).join('');
        
        // Restaurer le scroll
        window.scrollTo(0, scroll);
        
    } catch (e) {
        console.error('Load error:', e);
    } finally {
        S.isLoading = false;
    }
};

// ✅ DÉTECTION ONGLET ACTIF
document.addEventListener('visibilitychange', () => {
    S.isActive = !document.hidden;
    if (S.isActive) load();
});

// ACTIONS
const vote = async (pollId, optId, postId) => {
    if (hasVoted(pollId)) return toast('Déjà voté', 'warning');
    
    const r = await api(C.VOTE_URL, { poll_id: pollId, option_id: optId });
    
    if (r.success) {
        addVote(pollId);
        
        const p = S.posts.find(x => x.id === postId);
        if (p && p.poll) {
            const opt = p.poll.options.find(o => o.id === optId);
            if (opt) opt.votes++;
            $(`[data-id="${postId}"]`).outerHTML = post(p);
        }
        
        toast('Vote enregistré 🎉', 'success');
    }
};

const share = async (id) => {
    const r = await api(C.SHARE_URL, { post_id: id });
    if (r.success) {
        const p = S.posts.find(x => x.id === id);
        if (p) {
            p.shares_count++;
            $(`[data-id="${id}"]`).outerHTML = post(p);
        }
        toast('Post partagé', 'success');
    }
};

const del = async (id) => {
    if (!confirm('Supprimer ?')) return;
    
    const el = $(`[data-id="${id}"]`);
    el.style.transform = 'translateX(-100%)';
    el.style.opacity = '0';
    
    const r = await api(C.DEL_URL, { post_id: id });
    
    setTimeout(() => {
        if (r.success) {
            el.remove();
            S.posts = S.posts.filter(p => p.id !== id);
            toast('Post supprimé', 'success');
        }
    }, 300);
};

const showMedia = (path) => {
    const d = document.createElement('div');
    d.className = 'fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 cursor-pointer';
    d.onclick = () => d.remove();
    d.innerHTML = `<img src="/storage/${path}" class="max-w-full max-h-full p-4 rounded-lg" onerror="this.parentElement.remove()">`;
    document.body.appendChild(d);
};

const toast = (msg, type = 'info') => {
    const col = { success: 'bg-green-600', error: 'bg-red-600', warning: 'bg-yellow-600', info: 'bg-gray-900' };
    const t = $('#toast');
    if (!t) return;
    t.className = `fixed bottom-4 right-4 ${col[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 transform transition-all`;
    t.textContent = msg;
    t.style.transform = 'translateY(0)';
    setTimeout(() => t.style.transform = 'translateY(150px)', 3000);
};

// ✅ INIT
document.addEventListener('DOMContentLoaded', () => {
    load();
    setInterval(() => S.isActive && !S.isLoading && load(), C.REFRESH);
});

window.vote = vote;
window.share = share;
window.del = del;
window.showMedia = showMedia;