function handleTextInput() {
    // temporaire : juste éviter l’erreur
}

// Variables globales
let isFirstChannelLoad = true; // Pour gérer le premier chargement
let autoRefreshInterval = null; // Pour stocker l'ID du setInterval
function handleKeyPress(event) {
    // temporaire : juste éviter l’erreur
}
const POST_CONFIG = {
    MAX_CHARS: 5000,              // Limite de caractères
    AUTO_SAVE_DELAY: 2000,        // Délai avant auto-save (ms)
    MAX_DRAFTS: 10,               // Nombre maximum de brouillons
    MAX_POLL_OPTIONS: 6,          // Nombre maximum d'options de sondage
    MAX_RECENT_EMOJIS: 8          // Nombre d'emojis récents à afficher
};

// État de l'application
let appState = {
    uploadedFiles: [],                                                      // Fichiers uploadés
    currentDrafts: JSON.parse(localStorage.getItem('postDrafts')) || [],   // Brouillons sauvegardés
    recentEmojis: JSON.parse(localStorage.getItem('recentEmojis')) || [],  // Emojis récemment utilisés
    autoSaveTimeout: null,                                                   // Timer pour auto-save
    isFirstPost: localStorage.getItem('hasPosted') !== 'true'               // Première publication
};

// Templates prédéfinis pour posts trading
const POST_TEMPLATES = {
    analysis: `📊 Analyse Technique - [Paire/Actif]

📈 Tendance: 
🎯 Niveaux clés:
- Support: 
- Résistance: 

💡 Stratégie:
⏰ Timeframe: 

#Trading #AnalyseTechnique`,

    trade: `💰 Rapport de Trade

📌 Actif: 
🔄 Type: Long/Short
💵 Entry: 
🎯 TP: 
🛑 SL: 
📊 Résultat: 

💭 Lessons learned:

#TradeReport`,

    question: `❓ Question pour la communauté

[Votre question ici]

🤔 Contexte:

Merci d'avance pour vos retours ! 🙏

#Trading #Question`,

    tip: `💡 Conseil du jour

[Votre conseil ici]

✨ Pourquoi c'est important:

#TradingTips #Astuce`
};

// Données utilisateurs pour mentions (à remplacer par vos vraies données API)
const MOCK_USERS = [
    { id: 1, name: 'Junior TOSSA', username: 'jtossa', avatar: 'avatar1.jpg', role: 'Modérateur' },
    { id: 2, name: 'Zeynab HOUNGBE', username: 'zhoungbe', avatar: 'avatar2.jpg', role: 'Coach' },
    { id: 3, name: 'Marie Dupont', username: 'mdupont', avatar: 'avatar3.jpg', role: 'Trader Pro' },
    { id: 4, name: 'Paul Martin', username: 'pmartin', avatar: 'avatar4.jpg', role: 'Analyste' }
];

// Hashtags populaires (à remplacer par vos vraies données)
const POPULAR_HASHTAGS = [
    { tag: 'Trading', count: 1250 },
    { tag: 'AnalyseTechnique', count: 890 },
    { tag: 'Crypto', count: 756 },
    { tag: 'Forex', count: 654 },
    { tag: 'DayTrading', count: 543 },
    { tag: 'SwingTrading', count: 432 },
    { tag: 'Bitcoin', count: 876 },
    { tag: 'Ethereum', count: 543 }
];
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

/**
 * Initialise tous les composants de l'application
 */
function initializeApp() {
    loadDrafts();
    updateDraftCount();
    setupDragAndDrop();
    setupKeyboardShortcuts();
    loadRecentEmojis();
    setupClickOutsideHandlers();
}

/**
 * ============================================
 * GESTION DES DROPDOWNS
 * ============================================
 */

/**
 * Toggle l'affichage du dropdown des brouillons
 */
function toggleDrafts() {
    const dropdown = document.getElementById('drafts-dropdown');
    dropdown.classList.toggle('hidden');

    // Fermer les autres dropdowns
    document.getElementById('templates-dropdown').classList.add('hidden');
    document.getElementById('preview-panel').classList.add('hidden');
}

/**
 * Toggle l'affichage du dropdown des templates
 */
function toggleTemplates() {
    const dropdown = document.getElementById('templates-dropdown');
    dropdown.classList.toggle('hidden');

    // Fermer les autres dropdowns
    document.getElementById('drafts-dropdown').classList.add('hidden');
    document.getElementById('preview-panel').classList.add('hidden');
}

/**
 * Toggle l'affichage du panel de preview
 */
function togglePreview() {
    const panel = document.getElementById('preview-panel');
    panel.classList.toggle('hidden');

    if (!panel.classList.contains('hidden')) {
        updatePreview();
    }

    // Fermer les autres dropdowns
    document.getElementById('drafts-dropdown').classList.add('hidden');
    document.getElementById('templates-dropdown').classList.add('hidden');
}

/**
 * ============================================
 * GESTION DU TEXTE & COMPTEUR
 * ============================================
 */

/**
 * Gère la saisie de texte dans le textarea
 * - Met à jour le compteur de caractères
 * - Détecte les mentions et hashtags
 * - Détecte les liens
 * - Déclenche l'auto-save
 */
function handleTextInput() {
    const textarea = document.getElementById('post-textarea');
    const text = textarea.value;
    const charCount = text.length;

    // Mise à jour du compteur
    updateCharCounter(charCount);

    // Détection des mentions et hashtags
    detectMentions(text, textarea);
    detectHashtags(text, textarea);

    // Détection des liens
    detectLinks(text);

    // Auto-save avec debounce
    clearTimeout(appState.autoSaveTimeout);
    appState.autoSaveTimeout = setTimeout(() => {
        autoSaveDraft();
    }, POST_CONFIG.AUTO_SAVE_DELAY);

    // Mise à jour du preview si ouvert
    if (!document.getElementById('preview-panel').classList.contains('hidden')) {
        updatePreview();
    }
}

/**
 * Met à jour le compteur de caractères et le cercle de progression
 * @param {number} count - Nombre de caractères actuels
 */
function updateCharCounter(count) {
    const counter = document.getElementById('char-counter');
    const circle = document.getElementById('char-progress-circle');

    if (!counter || !circle) return;

    const percentage = (count / POST_CONFIG.MAX_CHARS) * 100;
    const circumference = 87.96;
    const offset = circumference - (percentage / 100) * circumference;

    // Mise à jour du texte
    counter.textContent = `${count} / ${POST_CONFIG.MAX_CHARS}`;

    // Mise à jour du cercle SVG
    circle.style.strokeDashoffset = offset;

    // Changement de couleur selon le seuil
    counter.classList.remove('warning', 'danger');
    circle.classList.remove('warning', 'danger');

    if (percentage > 90) {
        counter.classList.add('danger');
        circle.classList.add('danger');
    } else if (percentage > 75) {
        counter.classList.add('warning');
        circle.classList.add('warning');
    }
}

/**
 * Gère les touches spéciales du clavier
 * @param {KeyboardEvent} event - Événement clavier
 */
function handleKeyPress(event) {
    // Navigation dans les suggestions avec les flèches
    const mentionSuggestions = document.getElementById('mention-suggestions');
    const hashtagSuggestions = document.getElementById('hashtag-suggestions');

    if (!mentionSuggestions.classList.contains('hidden') || !hashtagSuggestions.classList.contains('hidden')) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            // TODO: Implémenter la navigation au clavier dans les suggestions
        }
    }
}

/**
 * ============================================
 * GESTION DES MENTIONS (@)
 * ============================================
 */

/**
 * Détecte les mentions (@username) dans le texte
 * @param {string} text - Texte complet
 * @param {HTMLTextAreaElement} textarea - Élément textarea
 */
function detectMentions(text, textarea) {
    const cursorPos = textarea.selectionStart;
    const textBeforeCursor = text.substring(0, cursorPos);
    const mentionMatch = textBeforeCursor.match(/@(\w*)$/);

    if (mentionMatch) {
        const searchTerm = mentionMatch[1].toLowerCase();
        const filtered = MOCK_USERS.filter(user =>
            user.username.toLowerCase().includes(searchTerm) ||
            user.name.toLowerCase().includes(searchTerm)
        );
        showMentionSuggestions(filtered, textarea);
    } else {
        hideMentionSuggestions();
    }
}

/**
 * Affiche les suggestions de mentions
 * @param {Array} suggestions - Liste des utilisateurs suggérés
 * @param {HTMLTextAreaElement} textarea - Élément textarea
 */
function showMentionSuggestions(suggestions, textarea) {
    const container = document.getElementById('mention-suggestions');

    if (suggestions.length === 0) {
        hideMentionSuggestions();
        return;
    }

    container.innerHTML = suggestions.map(user => `
        <div class="suggestion-item" onclick="insertMention('${user.username}', '${user.name}')">
            <img src="${user.avatar}" alt="${user.name}" 
                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}'"
                 style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
            <div>
                <div style="font-weight: 600; font-size: 0.875rem;">${user.name}</div>
                <div style="font-size: 0.75rem; color: #6b7280;">@${user.username} • ${user.role}</div>
            </div>
        </div>
    `).join('');

    // Positionnement
    const rect = textarea.getBoundingClientRect();
    container.style.top = `${rect.bottom + window.scrollY}px`;
    container.style.left = `${rect.left}px`;
    container.classList.remove('hidden');
}

/**
 * Cache les suggestions de mentions
 */
function hideMentionSuggestions() {
    document.getElementById('mention-suggestions').classList.add('hidden');
}

/**
 * Insère une mention dans le textarea
 * @param {string} username - Nom d'utilisateur
 * @param {string} name - Nom complet
 */
function insertMention(username, name) {
    const textarea = document.getElementById('post-textarea');
    const text = textarea.value;
    const cursorPos = textarea.selectionStart;
    const textBeforeCursor = text.substring(0, cursorPos);
    const textAfterCursor = text.substring(cursorPos);

    // Trouver le début du @
    const mentionStart = textBeforeCursor.lastIndexOf('@');
    const newText = textBeforeCursor.substring(0, mentionStart) +
        `@${username} ` +
        textAfterCursor;

    textarea.value = newText;
    textarea.focus();

    const newCursorPos = mentionStart + username.length + 2;
    textarea.setSelectionRange(newCursorPos, newCursorPos);

    hideMentionSuggestions();
    handleTextInput();
}

/**
 * ============================================
 * GESTION DES HASHTAGS (#)
 * ============================================
 */

/**
 * Détecte les hashtags (#tag) dans le texte
 * @param {string} text - Texte complet
 * @param {HTMLTextAreaElement} textarea - Élément textarea
 */
function detectHashtags(text, textarea) {
    const cursorPos = textarea.selectionStart;
    const textBeforeCursor = text.substring(0, cursorPos);
    const hashtagMatch = textBeforeCursor.match(/#(\w*)$/);

    if (hashtagMatch) {
        const searchTerm = hashtagMatch[1].toLowerCase();
        const filtered = POPULAR_HASHTAGS.filter(tag =>
            tag.tag.toLowerCase().includes(searchTerm)
        );
        showHashtagSuggestions(filtered, textarea);
    } else {
        hideHashtagSuggestions();
    }
}

/**
 * Affiche les suggestions de hashtags
 * @param {Array} suggestions - Liste des hashtags suggérés
 * @param {HTMLTextAreaElement} textarea - Élément textarea
 */
function showHashtagSuggestions(suggestions, textarea) {
    const container = document.getElementById('hashtag-suggestions');

    if (suggestions.length === 0) {
        hideHashtagSuggestions();
        return;
    }

    container.innerHTML = suggestions.map(tag => `
        <div class="suggestion-item" onclick="insertHashtag('${tag.tag}')">
            <i class="fas fa-hashtag" style="color: #4f46e5;"></i>
            <div>
                <div style="font-weight: 600; font-size: 0.875rem;">${tag.tag}</div>
                <div style="font-size: 0.75rem; color: #6b7280;">${tag.count.toLocaleString()} posts</div>
            </div>
        </div>
    `).join('');

    // Positionnement
    const rect = textarea.getBoundingClientRect();
    container.style.top = `${rect.bottom + window.scrollY}px`;
    container.style.left = `${rect.left}px`;
    container.classList.remove('hidden');
}

/**
 * Cache les suggestions de hashtags
 */
function hideHashtagSuggestions() {
    document.getElementById('hashtag-suggestions').classList.add('hidden');
}

/**
 * Insère un hashtag dans le textarea
 * @param {string} tag - Hashtag (sans le #)
 */
function insertHashtag(tag) {
    const textarea = document.getElementById('post-textarea');
    const text = textarea.value;
    const cursorPos = textarea.selectionStart;
    const textBeforeCursor = text.substring(0, cursorPos);
    const textAfterCursor = text.substring(cursorPos);

    // Trouver le début du #
    const hashtagStart = textBeforeCursor.lastIndexOf('#');
    const newText = textBeforeCursor.substring(0, hashtagStart) +
        `#${tag} ` +
        textAfterCursor;

    textarea.value = newText;
    textarea.focus();

    const newCursorPos = hashtagStart + tag.length + 2;
    textarea.setSelectionRange(newCursorPos, newCursorPos);

    hideHashtagSuggestions();
    handleTextInput();
}

/**
 * ============================================
 * DÉTECTION DE LIENS
 * ============================================
 */

/**
 * Détecte les liens HTTP(S) dans le texte
 * @param {string} text - Texte à analyser
 */
function detectLinks(text) {
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    const matches = text.match(urlRegex);

    if (matches && matches.length > 0) {
        fetchLinkPreview(matches[0]); // Prendre le premier lien
    } else {
        removeLinkPreview();
    }
}

/**
 * Récupère les métadonnées d'un lien (Open Graph)
 * @param {string} url - URL à analyser
 */
function fetchLinkPreview(url) {
    // Dans un vrai projet, appeler votre backend pour récupérer les métadonnées
    // Ici on simule avec des données fictives
    const preview = document.getElementById('link-preview');

    // Simulation de données Open Graph
    document.getElementById('link-preview-image').src = 'https://via.placeholder.com/128';
    document.getElementById('link-preview-title').textContent = 'Titre de l\'article';
    document.getElementById('link-preview-description').textContent = 'Description du contenu partagé. Cette preview est générée automatiquement à partir des métadonnées du lien.';
    document.getElementById('link-preview-url').textContent = new URL(url).hostname;

    preview.classList.remove('hidden');
}

/**
 * Supprime le preview de lien
 */
function removeLinkPreview() {
    document.getElementById('link-preview').classList.add('hidden');
}

/**
 * ============================================
 * GESTION DES EMOJIS
 * ============================================
 */

/**
 * Toggle l'affichage du picker d'emojis
 */
function toggleEmojiPicker() {
    const picker = document.getElementById('emoji-picker');
    picker.classList.toggle('hidden');

    if (!picker.classList.contains('hidden')) {
        document.getElementById('emoji-search').focus();
    }
}

/**
 * Recherche d'emojis par titre
 */
function searchEmojis() {
    const searchTerm = document.getElementById('emoji-search').value.toLowerCase();
    const emojis = document.querySelectorAll('.emoji-btn');

    emojis.forEach(emoji => {
        const title = emoji.getAttribute('title')?.toLowerCase() || '';
        emoji.style.display = title.includes(searchTerm) ? 'block' : 'none';
    });
}

/**
 * Filtre les emojis par catégorie
 * @param {string} category - Catégorie à afficher
 */
function filterEmojis(category) {
    // Mise à jour des boutons de catégorie
    document.querySelectorAll('.emoji-cat').forEach(cat => {
        cat.classList.remove('emoji-cat-active');
    });
    event.target.classList.add('emoji-cat-active');

    showToast(`Filtrage: ${category}`, 'info');
}

/**
 * Insère un emoji dans le textarea
 * @param {string} emoji - Emoji à insérer
 */
function insertEmoji(emoji) {
    const textarea = document.getElementById('post-textarea');
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);

    textarea.value = textBefore + emoji + textAfter;
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = cursorPos + emoji.length;

    // Ajouter aux emojis récents
    addToRecentEmojis(emoji);

    // Fermer le picker
    document.getElementById('emoji-picker').classList.add('hidden');

    handleTextInput();
}

/**
 * Ajoute un emoji aux récents
 * @param {string} emoji - Emoji à ajouter
 */
function addToRecentEmojis(emoji) {
    // Retirer si déjà présent
    appState.recentEmojis = appState.recentEmojis.filter(e => e !== emoji);

    // Ajouter au début
    appState.recentEmojis.unshift(emoji);

    // Garder seulement les X derniers
    appState.recentEmojis = appState.recentEmojis.slice(0, POST_CONFIG.MAX_RECENT_EMOJIS);

    // Sauvegarder
    localStorage.setItem('recentEmojis', JSON.stringify(appState.recentEmojis));

    // Mettre à jour l'affichage
    loadRecentEmojis();
}

/**
 * Charge et affiche les emojis récents
 */
function loadRecentEmojis() {
    const container = document.querySelector('#recent-emojis .flex');
    if (!container) return;

    container.innerHTML = appState.recentEmojis.map(emoji => `
        <button type="button" onclick="insertEmoji('${emoji}')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all">
            ${emoji}
        </button>
    `).join('');
}

/**
 * ============================================
 * GESTION DES FICHIERS
 * ============================================
 */

/**
 * Gère l'upload d'images
 * @param {Event} event - Événement de changement de fichier
 */
function handleImageUpload(event) {
    const files = Array.from(event.target.files);
    const preview = document.getElementById('media-preview');

    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            appState.uploadedFiles.push(file);

            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" onclick="removeMedia(${appState.uploadedFiles.length - 1})" 
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                preview.appendChild(div);
                preview.classList.remove('hidden');

                // Mettre à jour le preview si ouvert
                if (!document.getElementById('preview-panel').classList.contains('hidden')) {
                    updatePreview();
                }
            };
            reader.readAsDataURL(file);

            // Simuler la compression
            simulateImageCompression(file);
        }
    });
}

/**
 * Gère l'upload de vidéos
 * @param {Event} event - Événement de changement de fichier
 */
function handleVideoUpload(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('video/')) {
        appState.uploadedFiles.push(file);

        const preview = document.getElementById('media-preview');
        const div = document.createElement('div');
        div.className = 'relative group col-span-3';
        div.innerHTML = `
            <video class="w-full h-32 object-cover rounded-lg" controls>
                <source src="${URL.createObjectURL(file)}" type="${file.type}">
            </video>
            <button type="button" onclick="removeMedia(${appState.uploadedFiles.length - 1})" 
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
        preview.appendChild(div);
        preview.classList.remove('hidden');

        // Mettre à jour le preview si ouvert
        if (!document.getElementById('preview-panel').classList.contains('hidden')) {
            updatePreview();
        }

        // Afficher la barre de progression
        showUploadProgress();
    }
}


/**
 * Supprime un média de la liste
 * @param {number} index - Index du fichier à supprimer
 */
function removeMedia(index) {
    appState.uploadedFiles.splice(index, 1);
    const preview = document.getElementById('media-preview');
    preview.children[index].remove();

    if (appState.uploadedFiles.length === 0) {
        preview.classList.add('hidden');
    }

    // Mettre à jour le preview si ouvert
    if (!document.getElementById('preview-panel').classList.contains('hidden')) {
        updatePreview();
    }
}

/**
 * Simule la compression d'une image
 * @param {File} file - Fichier image
 */
function simulateImageCompression(file) {
    showToast(`Compression de ${file.name}...`, 'info');

    setTimeout(() => {
        const originalSize = (file.size / 1024).toFixed(2);
        const compressedSize = (file.size * 0.6 / 1024).toFixed(2);
        showToast(`Image compressée: ${originalSize}KB → ${compressedSize}KB`, 'success');
    }, 1500);
}

/**
 * Affiche une barre de progression pour l'upload
 */
function showUploadProgress() {
    const progressContainer = document.getElementById('upload-progress');
    const progressBar = document.getElementById('upload-progress-bar');
    const percentage = document.getElementById('upload-percentage');

    progressContainer.classList.remove('hidden');

    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            setTimeout(() => {
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                showToast('Upload terminé ! 🎉', 'success');
            }, 500);
        }
        progressBar.style.width = `${progress}%`;
        percentage.textContent = `${Math.round(progress)}%`;
    }, 200);
}

/**
 * ============================================
 * DRAG & DROP
 * ============================================
 */

/**
 * Configure le drag & drop pour les fichiers
 */
function setupDragAndDrop() {
    const textarea = document.getElementById('post-textarea');
    const dropZone = document.getElementById('drop-zone');

    if (!textarea || !dropZone) return;

    // Afficher la zone de drop au drag
    ['dragenter', 'dragover'].forEach(eventName => {
        textarea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('hidden');
            dropZone.classList.add('drag-over');
        });
    });

    // Cacher la zone de drop
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('hidden');
            dropZone.classList.remove('drag-over');
        });
    });

    // Gérer le drop
    dropZone.addEventListener('drop', (e) => {
        const files = Array.from(e.dataTransfer.files);

        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const fakeEvent = { target: { files: [file] } };
                handleImageUpload(fakeEvent);
            } else if (file.type.startsWith('video/')) {
                const fakeEvent = { target: { files: [file] } };
                handleVideoUpload(fakeEvent);
            }
        });
    });
}

/**
 * ============================================
 * SONDAGES (POLLS)
 * ============================================
 */

/**
 * Toggle l'affichage du container de sondage
 */
function togglePoll() {
    const container = document.getElementById('poll-container');
    container.classList.toggle('hidden');

    if (!container.classList.contains('hidden')) {
        document.getElementById('poll-question').focus();
    }
}

/**
 * Ajoute une option au sondage
 */
function addPollOption() {
    const container = document.getElementById('poll-options');
    const optionCount = container.children.length + 1;

    if (optionCount > POST_CONFIG.MAX_POLL_OPTIONS) {
        showToast(`Maximum ${POST_CONFIG.MAX_POLL_OPTIONS} options`, 'warning');
        return;
    }

    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = `Option ${optionCount}`;
    input.className = 'w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all';

    container.appendChild(input);
    input.focus();
}

/**
 * Supprime le sondage
 */
function removePoll() {
    document.getElementById('poll-container').classList.add('hidden');
    document.getElementById('poll-question').value = '';

    const container = document.getElementById('poll-options');
    container.innerHTML = `
        <input type="text" placeholder="Option 1" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
        <input type="text" placeholder="Option 2" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
    `;
}

/**
 * ============================================
 * PLANIFICATION
 * ============================================
 */

/**
 * Toggle l'affichage du container de planification
 */
function toggleSchedule() {
    const container = document.getElementById('schedule-container');
    container.classList.toggle('hidden');

    if (!container.classList.contains('hidden')) {
        // Définir date/heure par défaut (maintenant + 1 heure)
        const now = new Date();
        now.setHours(now.getHours() + 1);

        const dateInput = document.getElementById('schedule-date');
        const timeInput = document.getElementById('schedule-time');

        dateInput.valueAsDate = now;
        timeInput.value = now.toTimeString().slice(0, 5);
    }
}

/**
 * Supprime la planification
 */
function removeSchedule() {
    document.getElementById('schedule-container').classList.add('hidden');
}

/**
 * ============================================
 * TEMPLATES
 * ============================================
 */

/**
 * Utilise un template prédéfini
 * @param {string} type - Type de template (analysis, trade, question, tip)
 */
function useTemplate(type) {
    const textarea = document.getElementById('post-textarea');
    textarea.value = POST_TEMPLATES[type];
    textarea.focus();
    toggleTemplates();
    handleTextInput();
    showToast('Template chargé ! 📋', 'success');
}

/**
 * ============================================
 * BROUILLONS
 * ============================================
 */

/**
 * Charge et affiche les brouillons sauvegardés
 */
function loadDrafts() {
    const container = document.getElementById('drafts-list');

    if (!container) return;

    if (appState.currentDrafts.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Aucun brouillon sauvegardé</p>';
        return;
    }

    container.innerHTML = appState.currentDrafts.map((draft, index) => `
        <div onclick="loadDraft(${index})" 
             class="flex items-start gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-all cursor-pointer">
            <i class="fas fa-file-alt text-gray-400 mt-1"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-900 line-clamp-2">${draft.text}</p>
                <p class="text-xs text-gray-500 mt-1">${formatDate(draft.date)}</p>
            </div>
            <button type="button" onclick="deleteDraft(${index}); event.stopPropagation();" 
                    class="text-red-500 hover:text-red-700 transition-colors">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
    `).join('');
}

/**
 * Sauvegarde automatique d'un brouillon
 */
function autoSaveDraft() {
    const textarea = document.getElementById('post-textarea');
    const text = textarea.value.trim();

    if (text.length < 10) return; // Pas de sauvegarde pour texte trop court

    // Vérifier si le brouillon existe déjà
    const existingIndex = appState.currentDrafts.findIndex(d => d.text === text);

    if (existingIndex === -1) {
        appState.currentDrafts.unshift({
            text: text,
            date: new Date().toISOString(),
            files: appState.uploadedFiles.map(f => f.name)
        });

        // Garder maximum X brouillons
        appState.currentDrafts = appState.currentDrafts.slice(0, POST_CONFIG.MAX_DRAFTS);

        localStorage.setItem('postDrafts', JSON.stringify(appState.currentDrafts));
        updateDraftCount();
    }
}

/**
 * Charge un brouillon dans le textarea
 * @param {number} index - Index du brouillon
 */
function loadDraft(index) {
    const draft = appState.currentDrafts[index];
    document.getElementById('post-textarea').value = draft.text;
    toggleDrafts();
    handleTextInput();
    showToast('Brouillon chargé ! 📝', 'success');
}

/**
 * Supprime un brouillon
 * @param {number} index - Index du brouillon à supprimer
 */
function deleteDraft(index) {
    appState.currentDrafts.splice(index, 1);
    localStorage.setItem('postDrafts', JSON.stringify(appState.currentDrafts));
    loadDrafts();
    updateDraftCount();
    showToast('Brouillon supprimé', 'info');
}

/**
 * Supprime tous les brouillons
 */
function clearAllDrafts() {
    if (confirm('Supprimer tous les brouillons ?')) {
        appState.currentDrafts = [];
        localStorage.setItem('postDrafts', JSON.stringify(appState.currentDrafts));
        loadDrafts();
        updateDraftCount();
        showToast('Tous les brouillons supprimés', 'info');
    }
}

/**
 * Met à jour le badge du nombre de brouillons
 */
function updateDraftCount() {
    const badge = document.getElementById('draft-count');
    if (!badge) return;

    if (appState.currentDrafts.length > 0) {
        badge.textContent = appState.currentDrafts.length;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

/**
 * ============================================
 * PREVIEW
 * ============================================
 */

/**
 * Met à jour le preview de la publication
 */
function updatePreview() {
    const textarea = document.getElementById('post-textarea');
    const content = document.getElementById('preview-content');
    const text = textarea.value;

    if (!text.trim() && appState.uploadedFiles.length === 0) {
        content.innerHTML = '<p class="text-gray-400 text-center py-8">Commencez à écrire ou ajoutez des médias pour voir l\'aperçu...</p>';
        return;
    }

    // Formatter le texte (mentions, hashtags, liens)
    let formattedText = text
        .replace(/@(\w+)/g, '<span class="text-indigo-600 font-semibold bg-indigo-50 px-1 py-0.5 rounded">@$1</span>')
        .replace(/#(\w+)/g, '<span class="text-purple-600 font-semibold">#$1</span>')
        .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" class="text-indigo-600 hover:underline" target="_blank">$1</a>')
        .replace(/\n/g, '<br>');

    // Générer le HTML des médias
    let mediaHTML = '';
    if (appState.uploadedFiles.length > 0) {
        const images = [];
        const videos = [];

        // Séparer images et vidéos
        appState.uploadedFiles.forEach(file => {
            if (file.type.startsWith('image/')) {
                images.push(file);
            } else if (file.type.startsWith('video/')) {
                videos.push(file);
            }
        });

        // Afficher les images
        if (images.length > 0) {
            const gridClass = images.length === 1 ? 'grid-cols-1' :
                images.length === 2 ? 'grid-cols-2' :
                    'grid-cols-2 md:grid-cols-3';

            mediaHTML += `
                <div class="grid ${gridClass} gap-2 mt-3 rounded-lg overflow-hidden">
                    ${images.map(img => {
                const url = URL.createObjectURL(img);
                return `<img src="${url}" class="w-full h-48 object-cover" alt="Image preview">`;
            }).join('')}
                </div>
            `;
        }

        // Afficher les vidéos
        if (videos.length > 0) {
            mediaHTML += videos.map(video => {
                const url = URL.createObjectURL(video);
                return `
                    <div class="mt-3 rounded-lg overflow-hidden">
                        <video class="w-full max-h-96 object-cover" controls>
                            <source src="${url}" type="${video.type}">
                        </video>
                    </div>
                `;
            }).join('');
        }
    }

    content.innerHTML = `
        <div class="flex items-start gap-3 mb-4">
            <img src="https://ui-avatars.com/api/?name=Abdoulaye" class="w-10 h-10 rounded-full ring-2 ring-gray-100">
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">Abdoulaye</span>
                    <i class="fas fa-check-circle text-indigo-600 text-xs"></i>
                    <span class="text-xs text-gray-500">À l'instant</span>
                </div>
            </div>
        </div>
        ${text ? `<div class="text-gray-900 leading-relaxed mb-2">${formattedText}</div>` : ''}
        ${mediaHTML}
        ${appState.uploadedFiles.length > 0 ? `
            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2 text-xs text-gray-500">
                <i class="fas fa-paperclip"></i>
                <span>${appState.uploadedFiles.length} fichier(s) • ${formatFileSize(appState.uploadedFiles.reduce((acc, f) => acc + f.size, 0))}</span>
            </div>
        ` : ''}
    `;
}

/**
 * Formate une taille de fichier en format lisible
 * @param {number} bytes - Taille en bytes
 * @returns {string} Taille formatée
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

/**
 * ============================================
 * FORMATAGE DE TEXTE
 * ============================================
 */

/**
 * Applique un formatage au texte sélectionné
 * @param {string} format - Type de formatage (bold, link)
 */
function formatText(format) {
    const textarea = document.getElementById('post-textarea');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    if (!selectedText) {
        showToast('Sélectionnez du texte d\'abord', 'warning');
        return;
    }

    let formattedText = '';

    switch (format) {
        case 'bold':
            formattedText = `**${selectedText}**`;
            break;
        case 'link':
            const url = prompt('Entrez l\'URL:');
            if (url) {
                formattedText = `[${selectedText}](${url})`;
            } else {
                return;
            }
            break;
    }

    const newText = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.value = newText;
    textarea.focus();
    textarea.setSelectionRange(start, start + formattedText.length);

    handleTextInput();
}

/**
 * ============================================
 * RACCOURCIS CLAVIER
 * ============================================
 */

/**
 * Configure les raccourcis clavier globaux
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function (e) {
        // Ctrl+Enter: Envoyer
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }

        // Ctrl+S: Sauvegarder brouillon
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            autoSaveDraft();
            showToast('Brouillon sauvegardé 💾', 'success');
        }

        // Ctrl+B: Gras
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            formatText('bold');
        }

        // Escape: Fermer les modals
        if (e.key === 'Escape') {
            const picker = document.getElementById('emoji-picker');
            if (picker) picker.classList.add('hidden');
            hideMentionSuggestions();
            hideHashtagSuggestions();
        }
    });
}

/**
 * ============================================
 * ENVOI DU MESSAGE
 * ============================================
 */

async function sendMessage(event) {
    if (event) event.preventDefault();

    const button = document.getElementById('send-button');
    const icon = document.getElementById('send-icon');
    const text = document.getElementById('send-text');
    const textarea = document.getElementById('post-textarea');
    const particles = document.getElementById('send-particles');
    const preview = document.getElementById('media-preview');
    const typingIndicator = document.getElementById('typing-indicator');
    const form = document.getElementById('post-form');

    /** ---------------- VALIDATION ---------------- */
    if (!textarea.value.trim() && (!appState.uploadedFiles || appState.uploadedFiles.length === 0)) {
        button.classList.add('shake-animation');
        setTimeout(() => button.classList.remove('shake-animation'), 500);
        showToast('Votre message est vide ✍️', 'warning');
        return;
    }

    /** ---------------- CONSTRUCTION DU FORMDATA ---------------- */
    const formData = new FormData();

    // CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Contenu
    formData.append('contents', textarea.value);

    // ✅ Poll - DIAGNOSTIC COMPLET
    const pollContainer = document.getElementById('poll-container');
    const pollQuestion = document.getElementById('poll-question');

    console.log('=== DIAGNOSTIC POLL ===');
    console.log('Poll Container exists:', !!pollContainer);
    console.log('Poll Container hidden:', pollContainer ? pollContainer.classList.contains('hidden') : 'N/A');
    console.log('Poll Question value:', pollQuestion ? pollQuestion.value : 'N/A');

    if (pollContainer && !pollContainer.classList.contains('hidden')) {
        const questionValue = pollQuestion ? pollQuestion.value.trim() : '';
        const pollOptionsInputs = document.querySelectorAll('#poll-options input');
        const pollOptions = Array.from(pollOptionsInputs)
            .map(input => input.value.trim())
            .filter(val => val !== '');

        console.log('Question:', questionValue);
        console.log('Options:', pollOptions);
        console.log('Options count:', pollOptions.length);

        if (questionValue && pollOptions.length >= 2) {
            const pollData = {
                question: questionValue,
                options: pollOptions
            };
            console.log('✅ POLL AJOUTÉ:', pollData);
            formData.append('poll', JSON.stringify(pollData));
        } else {
            console.log('❌ POLL NON AJOUTÉ - Question vide ou moins de 2 options');
        }
    } else {
        console.log('❌ POLL NON AJOUTÉ - Container caché ou inexistant');
    }

    // Fichiers
    if (appState.uploadedFiles && appState.uploadedFiles.length > 0) {
        appState.uploadedFiles.forEach(file => formData.append('media[]', file));
        console.log('📎 Files:', appState.uploadedFiles.length);
    }

    // Schedule
    const scheduleContainer = document.getElementById('schedule-container');
    if (scheduleContainer && !scheduleContainer.classList.contains('hidden')) {
        const scheduleDate = document.getElementById('schedule-date').value;
        const scheduleTime = document.getElementById('schedule-time').value;

        if (scheduleDate) formData.append('scheduled_at_date', scheduleDate);
        if (scheduleTime) formData.append('scheduled_at_time', scheduleTime);

        console.log('📅 Schedule:', scheduleDate, scheduleTime);
    }

    // ✅ AFFICHER LE FORMDATA COMPLET
    console.log('=== FORMDATA FINAL ===');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }
    console.log('=====================');

    /** ---------------- UI : DÉMARRAGE ---------------- */
    button.disabled = true;
    button.classList.add('sending');

    if (typingIndicator) typingIndicator.classList.remove('hidden');

    textarea.classList.add('aspiration-animation');

    if (preview.children.length > 0) {
        preview.classList.add('aspiration-animation');
    }

    setTimeout(() => {
        icon.classList.add('spin-and-fly');
        text.style.opacity = '0';
        particles.style.opacity = '1';
        particles.classList.add('particles-active');
    }, 300);

    setTimeout(() => {
        button.classList.add('propulsion-animation');
        if (typeof playSendSound === 'function') playSendSound();
    }, 800);

    /** ---------------- API CALL ---------------- */
    try {
        const res = await fetch('/vip', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();

        console.log('📥 Response status:', res.status);
        console.log('📥 Response data:', data);

        if (!res.ok) {
            throw new Error(data.message || 'Erreur lors de l\'envoi');
        }

        /** ---------------- SUCCÈS ---------------- */
        await new Promise(resolve => setTimeout(resolve, 500));

        showToast('Votre post a été publié 🎉', 'success');

        if (appState.isFirstPost) {
            if (typeof createConfetti === 'function') createConfetti();
            localStorage.setItem('hasPosted', 'true');
            appState.isFirstPost = false;
        }

        /** ---------------- RESET ---------------- */
        form.reset();
        appState.uploadedFiles = [];

        const pollCont = document.getElementById('poll-container');
        const scheduleCont = document.getElementById('schedule-container');
        const linkPrev = document.getElementById('link-preview');

        if (pollCont) pollCont.classList.add('hidden');
        if (scheduleCont) scheduleCont.classList.add('hidden');
        if (linkPrev) linkPrev.classList.add('hidden');

        preview.innerHTML = '';
        preview.classList.add('hidden');
        preview.classList.remove('aspiration-animation');

        if (typeof updateCharCounter === 'function') updateCharCounter(0);

        textarea.classList.remove('aspiration-animation');

        if (typingIndicator) typingIndicator.classList.add('hidden');

        button.classList.remove('sending', 'propulsion-animation');
        icon.classList.remove('spin-and-fly');
        text.style.opacity = '1';
        particles.style.opacity = '0';
        particles.classList.remove('particles-active');
        button.disabled = false;

    } catch (error) {
        console.error('❌ ERROR:', error);

        await new Promise(resolve => setTimeout(resolve, 500));

        showToast(error.message || 'Une erreur est survenue', 'error');

        textarea.classList.remove('aspiration-animation');

        if (preview.children.length > 0) {
            preview.classList.remove('aspiration-animation');
        }

        if (typingIndicator) typingIndicator.classList.add('hidden');

        button.classList.remove('sending', 'propulsion-animation');
        icon.classList.remove('spin-and-fly');
        text.style.opacity = '1';
        particles.style.opacity = '0';
        particles.classList.remove('particles-active');
        button.disabled = false;
    }
}

/**
 * Envoie le message avec toutes les animations
 */

/**
 * Joue un son lors de l'envoi
 */
function playSendSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(1200, audioContext.currentTime + 0.2);

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (error) {
        console.log('Audio not supported');
    }
}

/**
 * Crée une animation de confettis
 */
function createConfetti() {
    const container = document.getElementById('confetti-container');
    if (!container) return;

    container.style.opacity = '1';

    // Créer 20 confettis
    for (let i = 0; i < 20; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'post-confetti';
        confetti.style.left = `${Math.random() * 100}%`;
        confetti.style.animationDelay = `${Math.random() * 0.5}s`;
        confetti.style.animationDuration = `${2 + Math.random()}s`;
        container.appendChild(confetti);
    }

    // Nettoyer après l'animation
    setTimeout(() => {
        container.innerHTML = '';
        container.style.opacity = '0';
    }, 3000);
}

/**
 * ============================================
 * TOAST NOTIFICATIONS
 * ============================================
 */

/**
 * Affiche une notification toast
 * @param {string} message - Message à afficher
 * @param {string} type - Type de toast (success, error, warning, info)
 */

function showToast(message, type = 'info') {
    let container = document.getElementById('toast-container');

    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none';
        container.style.maxWidth = '420px';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    
    const config = {
        success: {
            icon: '✓',
            gradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            glowColor: 'rgba(102, 126, 234, 0.3)'
        },
        error: {
            icon: '✕',
            gradient: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            glowColor: 'rgba(245, 87, 108, 0.3)'
        },
        warning: {
            icon: '⚠',
            gradient: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            glowColor: 'rgba(254, 225, 64, 0.3)'
        },
        info: {
            icon: 'ℹ',
            gradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            glowColor: 'rgba(102, 126, 234, 0.3)'
        }
    };

    const style = config[type] || config.info;

    toast.className = `
        bg-white rounded-xl shadow-2xl 
        pointer-events-auto transform transition-all duration-300 ease-out
        flex items-start gap-3 p-4 min-w-[320px] max-w-[420px]
        backdrop-blur-sm relative overflow-hidden
    `.trim().replace(/\s+/g, ' ');

    toast.style.cssText = `
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 10px 40px ${style.glowColor}, 0 4px 12px rgba(0,0,0,0.1);
    `;

    toast.innerHTML = `
        <div class="absolute top-0 left-0 right-0 h-1" style="background: ${style.gradient};"></div>
        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-lg text-white shadow-lg" 
             style="background: ${style.gradient};">
            ${style.icon}
        </div>
        <div class="flex-1 pt-1">
            <p class="text-gray-800 font-medium text-sm leading-relaxed">${message}</p>
        </div>
        <button class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full hover:bg-gray-100" 
                onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    // Ajouter les animations CSS si elles n'existent pas
    if (!document.getElementById('toast-animations')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'toast-animations';
        styleEl.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0) scale(1);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px) scale(0.95);
                    opacity: 0;
                }
            }
            .toast-removing {
                animation: slideOut 0.3s ease-in forwards;
            }
        `;
        document.head.appendChild(styleEl);
    }

    container.appendChild(toast);

    // Animation de suppression après 4 secondes
    setTimeout(() => {
        toast.classList.add('toast-removing');
        setTimeout(() => {
            toast.remove();
            if (container.children.length === 0) {
                container.remove();
            }
        }, 300);
    }, 4000);
}
function showToasts(message, type = 'info') {
    let container = document.getElementById('toast-container');

    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    toast.className = `post-toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${icons[type]}"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    // Supprimer après 3 secondes
    setTimeout(() => {
        toast.classList.add('removing');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

/**
 * ============================================
 * UTILITAIRES
 * ============================================
 */

/**
 * Formate une date relative (il y a X minutes)
 * @param {string} dateString - Date ISO
 * @returns {string} Date formatée
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;

    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'À l\'instant';
    if (minutes < 60) return `Il y a ${minutes} min`;
    if (hours < 24) return `Il y a ${hours}h`;
    if (days < 7) return `Il y a ${days}j`;

    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}

/**
 * Configure les handlers de clic à l'extérieur
 */
function setupClickOutsideHandlers() {
    document.addEventListener('click', function (event) {
        // Fermer emoji picker
        const picker = document.getElementById('emoji-picker');
        const emojiButton = event.target.closest('[onclick*="toggleEmojiPicker"]');
        if (picker && !picker.contains(event.target) && !emojiButton) {
            picker.classList.add('hidden');
        }

        // Fermer suggestions
        if (!event.target.closest('#mention-suggestions') && !event.target.closest('#post-textarea')) {
            hideMentionSuggestions();
        }
        if (!event.target.closest('#hashtag-suggestions') && !event.target.closest('#post-textarea')) {
            hideHashtagSuggestions();
        }
    });
}

/**
 * Sauvegarde automatique avant de quitter la page
 */
window.addEventListener('beforeunload', function (e) {
    const textarea = document.getElementById('post-textarea');
    if (textarea && textarea.value.trim().length > 10) {
        autoSaveDraft();
        e.preventDefault();
        e.returnValue = '';
    }
});




