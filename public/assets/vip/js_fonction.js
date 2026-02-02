


function handleChannelSearch(event) {
    if (event.key === 'Enter') {
        const query = event.target.value.trim();
        if (query) {
            console.log('Recherche dans le canal:', query);
            // TODO: Implémenter la recherche réelle
            // fetch('/api/channels/current/search?q=' + encodeURIComponent(query))
            alert(`Recherche de: "${query}" (à connecter à votre API)`);
        }
    }
}







// ========== MESSAGERIE ==========
function sendMessage() {
    const input = document.getElementById('messageInput');
    const content = input.value.trim();

    if (!content) return;

    // TODO: Appel API pour envoyer le message
    console.log('Envoi message:', content);
    // fetch('/api/channels/messages', { method: 'POST', body: JSON.stringify({ content }) })

    input.value = '';
    // TODO: Ajouter le message à l'interface
}

function handleMessageSubmit(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function toggleEmojiPicker() {
    // TODO: Implémenter un picker d'emojis
    console.log('Toggle emoji picker');
}

function openAttachMenu() {
    const menu = document.getElementById('attachMenu');
    menu.classList.toggle('hidden');
}

function handleQuickMediaUpload(event) {
    const files = Array.from(event.target.files);
    console.log('Upload rapide:', files);
    // TODO: Uploader et afficher les médias
}



// ========== MODALS ==========
function openTextModal() {
    document.getElementById('textModal').classList.add('active');
    document.getElementById('attachMenu').classList.add('hidden');
}

function closeTextModal() {
    document.getElementById('textModal').classList.remove('active');
    document.getElementById('textContent').value = '';
}

function openMediaModal() {
    document.getElementById('mediaModal').classList.add('active');
    document.getElementById('attachMenu').classList.add('hidden');
}

function closeMediaModal() {
    document.getElementById('mediaModal').classList.remove('active');
    document.getElementById('mediaContent').value = '';
    document.getElementById('mediaPreview').innerHTML = '';
}

function openPollModal() {
    document.getElementById('pollModal').classList.add('active');
    document.getElementById('attachMenu').classList.add('hidden');
}

function closePollModal() {
    document.getElementById('pollModal').classList.remove('active');
    document.getElementById('pollQuestion').value = '';
    document.getElementById('pollDescription').value = '';
    const options = document.querySelectorAll('.poll-option-input');
    options.forEach((opt, i) => {
        if (i < 2) opt.value = '';
        else opt.remove();
    });
}

// ========== MÉDIA UPLOAD ==========
let selectedFiles = [];

function handleMediaUpload(event) {
    selectedFiles = Array.from(event.target.files);
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const isVideo = file.type.startsWith('video/');
            const mediaHTML = `
                    <div class="media-preview relative">
                        ${isVideo
                    ? `<video src="${e.target.result}" class="w-full h-32 object-cover rounded-lg"></video>`
                    : `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`
                }
                        <button class="remove-media" onclick="removeMedia(${index})">×</button>
                    </div>
                `;
            preview.innerHTML += mediaHTML;
        };
        reader.readAsDataURL(file);
    });
}

function removeMedia(index) {
    selectedFiles.splice(index, 1);
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('mediaInput').files = dt.files;
    handleMediaUpload({ target: { files: selectedFiles } });
}

// ========== POLL OPTIONS ==========
function addPollOption() {
    const container = document.getElementById('pollOptions');
    const optionCount = container.querySelectorAll('input').length + 1;
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg';
    input.placeholder = `Option ${optionCount}`;
    container.appendChild(input);
}

// ========== PUBLICATION DES POSTS ==========
function publishTextPost() {
    const content = document.getElementById('textContent').value;
    if (!content.trim()) {
        alert('Veuillez saisir un contenu');
        return;
    }

    // TODO: Appel API pour créer le post
    // fetch('/api/posts/text', { method: 'POST', body: JSON.stringify({ content, type: 'text' }) })

    console.log('Publication post texte:', content);
    closeTextModal();
    alert('Post publié ! (à connecter à votre API)');
}

function publishMediaPost() {
    const content = document.getElementById('mediaContent').value;
    if (selectedFiles.length === 0) {
        alert('Veuillez sélectionner au moins un fichier');
        return;
    }

    // TODO: Appel API pour uploader les médias et créer le post
    // const formData = new FormData();
    // formData.append('content', content);
    // selectedFiles.forEach(file => formData.append('media[]', file));
    // fetch('/api/posts/media', { method: 'POST', body: formData })

    console.log('Publication post média:', content, selectedFiles);
    closeMediaModal();
    alert('Post publié ! (à connecter à votre API)');
}

function publishPollPost() {
    const question = document.getElementById('pollQuestion').value;
    const description = document.getElementById('pollDescription').value;
    const options = Array.from(document.querySelectorAll('.poll-option-input'))
        .map(input => input.value.trim())
        .filter(val => val);

    if (!question.trim()) {
        alert('Veuillez saisir une question');
        return;
    }
    if (options.length < 2) {
        alert('Veuillez saisir au moins 2 options');
        return;
    }

    // TODO: Appel API pour créer le sondage
    // fetch('/api/posts/poll', { method: 'POST', body: JSON.stringify({ question, description, options, type: 'sondage' }) })

    console.log('Publication sondage:', { question, description, options });
    closePollModal();
    alert('Sondage publié ! (à connecter à votre API)');
}

// ========== INTERACTIONS (à connecter à vos fonctions existantes) ==========
function handleLike(button, postId) {
    // Appeler votre fonction existante pour gérer les likes
    console.log('Like post:', postId);
    // TODO: fetch('/api/posts/' + postId + '/like', { method: 'POST' })
}

function toggleComments(button) {
    const commentSection = button.closest('.px-4').querySelector('.comment-section');
    if (commentSection) {
        commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none';
    }
}

function addComment(input) {
    const content = input.value.trim();
    if (!content) return;

    const postCard = input.closest('[data-post-id]');
    const postId = postCard.dataset.postId;

    // TODO: Appel à votre API pour ajouter le commentaire
    console.log('Ajout commentaire:', { postId, content });
    // fetch('/api/posts/' + postId + '/comments', { method: 'POST', body: JSON.stringify({ content }) })

    input.value = '';
}

function handleCommentSubmit(event, input) {
    if (event.key === 'Enter') {
        addComment(input);
    }
}

function openShareModal(button, postId) {
    console.log('Partage post:', postId);
    // Implémenter votre modal de partage
}

function toggleOptions(button) {
    const menu = button.nextElementSibling;
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function editPost(element, postId) {
    console.log('Édition post:', postId);
    // Implémenter la modification
}

function deletePost(element, postId) {
    if (confirm('Voulez-vous vraiment supprimer ce post ?')) {
        console.log('Suppression post:', postId);
        // TODO: fetch('/api/posts/' + postId, { method: 'DELETE' })
    }
}

function votePoll(pollId, optionId, element) {
    console.log('Vote sondage:', { pollId, optionId });
    // TODO: fetch('/api/polls/' + pollId + '/vote', { method: 'POST', body: JSON.stringify({ option_id: optionId }) })
}

// Fermer les modals en cliquant à l'extérieur
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});
