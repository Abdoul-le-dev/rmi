
// Toggle menu d'options
function toggleOptions(button) {
    const menu = button.nextElementSibling;
    const allMenus = document.querySelectorAll('.options-menu');

    // Fermer tous les autres menus
    allMenus.forEach(m => {
        if (m !== menu) m.classList.remove('active');
    });

    menu.classList.toggle('active');
}

// Fermer les menus au clic à l'extérieur
document.addEventListener('click', (e) => {
    if (!e.target.closest('.post-options')) {
        document.querySelectorAll('.options-menu').forEach(menu => {
            menu.classList.remove('active');
        });
    }
});

// Modifier un post
function editPost(button, id) {
    const post = button.closest('.post-card');

    // Détecter le type de post
    const hasMedia = post.querySelector('.media-grid');
    const hasPoll = post.querySelector('.poll-option');
    const hasText = post.querySelector('.p-4 > p:not(.text-xs)');

    // Fermer le menu
    button.closest('.options-menu').classList.remove('active');

    if (hasPoll) {
        openEditPollModal(post);
    } else if (hasMedia) {
        openEditMediaModal(post);
    } else {
        openEditTextModal(post);
    }
}

// Modal d'édition pour post texte
function openEditTextModal(post) {
    const contentText = post.querySelector('.p-4 > p:not(.text-xs)');
    const currentText = contentText.textContent.trim();

    const modalContent = document.getElementById('editModalContent');
    modalContent.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-base">Modifier le post</h3>
                    <button onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="edit-option-item">
                    <label class="edit-label">Contenu du post</label>
                    <textarea id="editTextContent" class="edit-textarea">${escapeHtml(currentText)}</textarea>
                </div>
                
                <div class="flex gap-2 mt-4">
                    <button onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Annuler
                    </button>
                    <button onclick="saveTextEdit({{ $post->id }})" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                        Enregistrer
                    </button>
                </div>
            `;

    // Stocker la référence du post
    window.currentEditingPost = post;

    // Ouvrir le modal
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';

    // Focus sur le textarea
    setTimeout(() => document.getElementById('editTextContent').focus(), 100);
}

// Modal d'édition pour post média
function openEditMediaModal(post) {
    const contentText = post.querySelector('.p-4 > p:not(.text-xs)');
    const mediaGrid = post.querySelector('.media-grid');
    const currentText = contentText.textContent.trim();

    // Compter le nombre d'images actuel
    const currentImages = mediaGrid.children.length;

    // Générer les champs d'upload pour chaque image
    let imagesUploadHtml = '';
    for (let i = 0; i < currentImages; i++) {
        imagesUploadHtml += `
                    <div class="edit-option-item">
                        <label class="edit-label">Image ${i + 1}</label>
                        <div class="relative">
                            <input type="file" id="imageUpload${i}" accept="image/*" class="hidden" onchange="previewImage(${i})">
                            <button type="button" onclick="document.getElementById('imageUpload${i}').click()" class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-400 transition-colors text-sm text-gray-600 hover:text-indigo-600 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span id="imageLabel${i}">Remplacer l'image</span>
                            </button>
                            <div id="imagePreview${i}" class="mt-2 hidden">
                                <img class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            </div>
                        </div>
                    </div>
                `;
    }

    const modalContent = document.getElementById('editModalContent');
    modalContent.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-base">Modifier le post média</h3>
                    <button onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="edit-option-item">
                    <label class="edit-label">Description</label>
                    <textarea id="editMediaText" class="edit-textarea">${escapeHtml(currentText)}</textarea>
                </div>
                
                <div class="edit-option-item">
                    <label class="edit-label">Nombre d'images</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="changeImageCount(1)" class="image-count-btn flex-1 px-4 py-2 border-2 ${currentImages === 1 ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-300 text-gray-700'} rounded-lg hover:border-indigo-400 transition-colors font-medium text-sm">
                            1
                        </button>
                        <button type="button" onclick="changeImageCount(2)" class="image-count-btn flex-1 px-4 py-2 border-2 ${currentImages === 2 ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-300 text-gray-700'} rounded-lg hover:border-indigo-400 transition-colors font-medium text-sm">
                            2
                        </button>
                        <button type="button" onclick="changeImageCount(3)" class="image-count-btn flex-1 px-4 py-2 border-2 ${currentImages === 3 ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-300 text-gray-700'} rounded-lg hover:border-indigo-400 transition-colors font-medium text-sm">
                            3
                        </button>
                        <button type="button" onclick="changeImageCount(4)" class="image-count-btn flex-1 px-4 py-2 border-2 ${currentImages === 4 ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-300 text-gray-700'} rounded-lg hover:border-indigo-400 transition-colors font-medium text-sm">
                            4
                        </button>
                    </div>
                </div>
                
                <div id="imagesUploadContainer">
                    ${imagesUploadHtml}
                </div>
                
                <div class="flex gap-2 mt-4">
                    <button onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Annuler
                    </button>
                    <button onclick="saveMediaEdit()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                        Enregistrer
                    </button>
                </div>
            `;

    window.currentEditingPost = post;
    window.selectedImageCount = currentImages;
    window.uploadedImages = new Array(currentImages).fill(null);

    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Modal d'édition pour sondage
function openEditPollModal(post) {
    const pollTitle = post.querySelector('h2');
    const pollDescription = post.querySelector('.p-4 > p:not(.text-xs)');
    const pollOptions = Array.from(post.querySelectorAll('.poll-option'));

    const optionsHtml = pollOptions.map((option, index) => {
        const optionText = option.querySelector('span.text-sm').textContent.trim();
        return `
                    <div class="edit-option-item">
                        <label class="edit-label">Option ${index + 1}</label>
                        <input type="text" id="pollOption${index}" class="edit-input" value="${escapeHtml(optionText)}">
                    </div>
                `;
    }).join('');

    const modalContent = document.getElementById('editModalContent');
    modalContent.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-base">Modifier le sondage</h3>
                    <button onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="edit-option-item">
                    <label class="edit-label">Question</label>
                    <input type="text" id="pollTitle" class="edit-input" value="${escapeHtml(pollTitle.textContent.trim())}">
                </div>
                
                <div class="edit-option-item">
                    <label class="edit-label">Description</label>
                    <textarea id="pollDescription" class="edit-textarea" style="min-height: 60px;">${escapeHtml(pollDescription.textContent.trim())}</textarea>
                </div>
                
                ${optionsHtml}
                
                <div class="flex gap-2 mt-4">
                    <button onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Annuler
                    </button>
                    <button onclick="savePollEdit()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                        Enregistrer
                    </button>
                </div>
            `;

    window.currentEditingPost = post;
    window.pollOptionsCount = pollOptions.length;

    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Sélectionner le nombre d'images
function selectImageCount(count) {
    window.selectedImageCount = count;

    // Mettre à jour les boutons
    document.querySelectorAll('.image-count-btn').forEach((btn, index) => {
        if (index + 1 === count) {
            btn.classList.remove('border-gray-300', 'text-gray-700');
            btn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
        } else {
            btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
            btn.classList.add('border-gray-300', 'text-gray-700');
        }
    });
}

// Sauvegarder l'édition du post texte
function saveTextEdit(id) {
    const newContent = document.getElementById('editTextContent').value.trim();
    if (newContent) {
        const contentText = window.currentEditingPost.querySelector('.p-4 > p:not(.text-xs)');
        contentText.textContent = newContent;
        closeEditModal();
        showEditSuccess();
    }
}

// Sauvegarder l'édition du post média
function saveMediaEdit() {
    const newText = document.getElementById('editMediaText').value.trim();
    const numImages = window.selectedImageCount;

    if (newText) {
        const contentText = window.currentEditingPost.querySelector('.p-4 > p:not(.text-xs)');
        contentText.textContent = newText;

        const mediaGrid = window.currentEditingPost.querySelector('.media-grid');
        updateMediaGridWithImages(mediaGrid, numImages, window.uploadedImages);

        closeEditModal();
        showEditSuccess();
    }
}

// Sauvegarder l'édition du sondage
function savePollEdit() {
    const newTitle = document.getElementById('pollTitle').value.trim();
    const newDesc = document.getElementById('pollDescription').value.trim();

    if (newTitle && newDesc) {
        const pollTitle = window.currentEditingPost.querySelector('h2');
        const pollDescription = window.currentEditingPost.querySelector('.p-4 > p:not(.text-xs)');

        pollTitle.textContent = newTitle;
        pollDescription.textContent = newDesc;

        // Mettre à jour les options
        const pollOptions = window.currentEditingPost.querySelectorAll('.poll-option');
        pollOptions.forEach((option, index) => {
            const newOptionText = document.getElementById(`pollOption${index}`).value.trim();
            if (newOptionText) {
                option.querySelector('span.text-sm').textContent = newOptionText;
            }
        });

        closeEditModal();
        showEditSuccess();
    }
}

// Fermer le modal d'édition
function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = '';
    window.currentEditingPost = null;
}

// Prévisualiser une image uploadée
function previewImage(index) {
    const input = document.getElementById(`imageUpload${index}`);
    const preview = document.getElementById(`imagePreview${index}`);
    const label = document.getElementById(`imageLabel${index}`);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            window.uploadedImages[index] = e.target.result;
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            label.textContent = '✓ Image sélectionnée';
            label.parentElement.classList.add('border-green-500', 'bg-green-50', 'text-green-600');
            label.parentElement.classList.remove('border-gray-300', 'text-gray-600');
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Changer le nombre d'images et mettre à jour les champs d'upload
function changeImageCount(count) {
    if (count === window.selectedImageCount) return;

    window.selectedImageCount = count;

    // Mettre à jour les boutons
    document.querySelectorAll('.image-count-btn').forEach((btn, index) => {
        if (index + 1 === count) {
            btn.classList.remove('border-gray-300', 'text-gray-700');
            btn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
        } else {
            btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
            btn.classList.add('border-gray-300', 'text-gray-700');
        }
    });

    // Régénérer les champs d'upload
    let imagesUploadHtml = '';
    for (let i = 0; i < count; i++) {
        const hasImage = window.uploadedImages[i];
        imagesUploadHtml += `
                    <div class="edit-option-item">
                        <label class="edit-label">Image ${i + 1}</label>
                        <div class="relative">
                            <input type="file" id="imageUpload${i}" accept="image/*" class="hidden" onchange="previewImage(${i})">
                            <button type="button" onclick="document.getElementById('imageUpload${i}').click()" class="w-full px-4 py-3 border-2 border-dashed ${hasImage ? 'border-green-500 bg-green-50 text-green-600' : 'border-gray-300 text-gray-600'} rounded-lg hover:border-indigo-400 transition-colors text-sm hover:text-indigo-600 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span id="imageLabel${i}">${hasImage ? '✓ Image sélectionnée' : 'Remplacer l\'image'}</span>
                            </button>
                            <div id="imagePreview${i}" class="mt-2 ${hasImage ? '' : 'hidden'}">
                                <img src="${hasImage || ''}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            </div>
                        </div>
                    </div>
                `;
    }

    document.getElementById('imagesUploadContainer').innerHTML = imagesUploadHtml;

    // Redimensionner le tableau uploadedImages
    const oldImages = window.uploadedImages;
    window.uploadedImages = new Array(count).fill(null);
    for (let i = 0; i < Math.min(count, oldImages.length); i++) {
        window.uploadedImages[i] = oldImages[i];
    }
}

// Fermer au clic sur le fond
document.getElementById('editModal').addEventListener('click', (e) => {
    if (e.target.id === 'editModal') {
        closeEditModal();
    }
});

// Mettre à jour la grille de médias
function updateMediaGrid(mediaGrid, numImages) {
    mediaGrid.className = 'media-grid';
    if (numImages === 1) mediaGrid.classList.add('single');
    else if (numImages === 2) mediaGrid.classList.add('double');
    else if (numImages === 3) mediaGrid.classList.add('triple');
    else mediaGrid.classList.add('quad');

    const colors = [
        'from-blue-400 to-purple-500',
        'from-pink-400 to-orange-500',
        'from-green-400 to-teal-500',
        'from-yellow-400 to-red-500'
    ];

    mediaGrid.innerHTML = '';
    for (let i = 0; i < numImages; i++) {
        const div = document.createElement('div');
        div.className = `aspect-square bg-gradient-to-br ${colors[i]} rounded-lg overflow-hidden`;
        div.innerHTML = `<div class="w-full h-full flex items-center justify-center text-white text-xs opacity-40">Photo ${i + 1}</div>`;
        mediaGrid.appendChild(div);
    }
}

// Mettre à jour la grille de médias avec les images uploadées
function updateMediaGridWithImages(mediaGrid, numImages, uploadedImages) {
    mediaGrid.className = 'media-grid';
    if (numImages === 1) mediaGrid.classList.add('single');
    else if (numImages === 2) mediaGrid.classList.add('double');
    else if (numImages === 3) mediaGrid.classList.add('triple');
    else mediaGrid.classList.add('quad');

    const colors = [
        'from-blue-400 to-purple-500',
        'from-pink-400 to-orange-500',
        'from-green-400 to-teal-500',
        'from-yellow-400 to-red-500'
    ];

    mediaGrid.innerHTML = '';
    for (let i = 0; i < numImages; i++) {
        const div = document.createElement('div');
        div.className = 'aspect-square rounded-lg overflow-hidden';

        // Si une image a été uploadée, l'afficher, sinon utiliser le gradient par défaut
        if (uploadedImages[i]) {
            div.innerHTML = `<img src="${uploadedImages[i]}" class="w-full h-full object-cover">`;
        } else {
            div.className += ` bg-gradient-to-br ${colors[i]}`;
            div.innerHTML = `<div class="w-full h-full flex items-center justify-center text-white text-xs opacity-40">Photo ${i + 1}</div>`;
        }

        mediaGrid.appendChild(div);
    }
}

// Afficher un message de succès
function showEditSuccess() {
    const successMsg = document.createElement('div');
    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm font-medium z-50';
    successMsg.style.animation = 'slideInRight 0.3s ease';
    successMsg.innerHTML = '✓ Modifications enregistrées';

    document.body.appendChild(successMsg);

    setTimeout(() => {
        successMsg.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => successMsg.remove(), 300);
    }, 2000);
}

// Supprimer un post
async function deletePost(button) {

    const post = button.closest('.post-card');
    post.style.opacity = '0';
    post.style.transform = 'scale(0.95)';
    if (confirm('Êtes-vous sûr de vouloir supprimer ce post ?')) {


        setTimeout(() => post.remove(), 200);
    }

    const postId = post.dataset.postId;



    try {
        const response = await fetch('/posts/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ post_id: postId }),
        });


        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();

    } catch (error) {
        console.error('Erreur enregistrement partage:', error);
    }

    // Fermer le menu
    button.closest('.options-menu').classList.remove('active');
}

// Ouvrir le modal de partage
function openShareModal(button, id) {
    const modal = document.getElementById('shareModal');
    const post = button.closest('.post-card');
    const postId = id;

    // Générer un lien unique pour chaque post
    document.getElementById('shareLink').value = `https://rmiclass.com/vip/${id}`;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Réinitialiser le message de succès
    document.getElementById('copySuccess').style.opacity = '0';

    // Enregistrer le partage en base de données
    saveShareToDatabases(id, '/posts/share');

    // Incrémenter le compteur de partages dans la vue
    const shareCount = post.querySelector('.flex.items-center.gap-4 span:nth-child(3) span');
    if (shareCount) {
        const currentCount = parseInt(shareCount.textContent.replace(/[^0-9]/g, ''));
        shareCount.textContent = formatNumber(currentCount + 1);
    }
}

// Fermer le modal de partage
function closeShareModal() {
    const modal = document.getElementById('shareModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// Copier le lien
function copyShareLink() {
    const linkInput = document.getElementById('shareLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // Pour mobile

    navigator.clipboard.writeText(linkInput.value).then(() => {
        const successMsg = document.getElementById('copySuccess');
        successMsg.style.opacity = '1';

        setTimeout(() => {
            successMsg.style.opacity = '0';
        }, 2000);
    });
}

// Fermer le modal au clic sur le fond
document.getElementById('shareModal').addEventListener('click', (e) => {
    if (e.target.id === 'shareModal') {
        closeShareModal();
    }
});
const $ = (s) => document.querySelector(s);
const csrf = () => $('meta[name="csrf-token"]').content;
// Like avec animation subtile
function handleLike(button, id) {
    const isLiked = button.classList.contains('liked');
    const post = button.closest('.post-card');
    const likeCount = post.querySelector('.flex.items-center.gap-4 span:first-child span');

    if (!isLiked) {
        button.classList.add('liked');
        if (likeCount) {
            const current = parseInt(likeCount.textContent.replace(/[^0-9]/g, ''));
            likeCount.textContent = formatNumber(current + 1);
        }
    } else {
        button.classList.remove('liked');
        if (likeCount) {
            const current = parseInt(likeCount.textContent.replace(/[^0-9]/g, ''));
            likeCount.textContent = formatNumber(current - 1);
        }
    }
}

// Toggle commentaires
function toggleComments(button) {
    const post = button.closest('.post-card');
    const commentSection = post.querySelector('.comment-section');
    commentSection.classList.toggle('active');
}

// Ajouter un commentaire

// Ajouter un commentaire
async function addComment(input) {
    const text = input.value.trim();
    if (!text) return;

    const post = input.closest('.post-card');
    const postId = post.getAttribute('data-post-id');
    const commentList = post.querySelector('.comment-section .space-y-2');
    const commentSection = post.querySelector('.comment-section');

    // Désactiver l'input pendant l'envoi
    input.disabled = true;

    // Envoyer en base de données
    const result = await saveCommentToDatabase(postId, text);

    // Réactiver l'input
    input.disabled = false;

    if (!result.success) {
        alert('Erreur lors de l\'envoi du commentaire. Veuillez réessayer.');
        return;
    }

    // Utiliser l'ID réel retourné par l'API
    const commentId = result.comment.id;

    if (!commentSection.classList.contains('active')) {
        commentSection.classList.add('active');
    }

    const comment = document.createElement('div');
    comment.className = 'comment-item flex gap-2 p-2.5 bg-gray-50 rounded-lg';
    comment.setAttribute('data-comment-id', commentId);
    comment.innerHTML = `
        <div class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex-shrink-0"></div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
                <span class="font-medium text-xs">Vous</span>
                <span class="text-xs text-gray-400">À l'instant</span>
                <div class="ml-auto flex gap-1">
                    <button onclick="editComment(${commentId})" class="text-gray-400 hover:text-indigo-600 transition-colors p-1" title="Modifier">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteComment(${commentId})" class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Supprimer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-700 comment-text">${escapeHtml(text)}</p>
        </div>
    `;

    // Insérer en premier
    commentList.insertBefore(comment, commentList.firstChild);

    // Gérer l'affichage des 10 derniers
    const allComments = commentList.querySelectorAll('.comment-item');
    allComments.forEach((c, index) => {
        if (index >= 10) {
            c.classList.add('hidden');
        }
    });

    updateShowMoreButton(post);

    input.value = '';

    const commentCount = post.querySelector('.flex.items-center.gap-4 span:nth-child(2) span');
    if (commentCount) {
        const current = parseInt(commentCount.textContent.replace(/[^0-9]/g, ''));
        commentCount.textContent = current + 1;
    }
}

// Afficher plus de commentaires
function showMoreComments(button) {
    const post = button.closest('.post-card');
    const commentList = post.querySelector('.comment-section .space-y-2');
    const hiddenComments = commentList.querySelectorAll('.comment-item.hidden');

    // Afficher les 10 prochains commentaires
    let count = 0;
    hiddenComments.forEach(comment => {
        if (count < 10) {
            comment.classList.remove('hidden');
            count++;
        }
    });

    // Mettre à jour le bouton
    updateShowMoreButton(post);
}

// Mettre à jour le bouton "Voir plus"
function updateShowMoreButton(post) {
    const commentList = post.querySelector('.comment-section .space-y-2');
    const hiddenComments = commentList.querySelectorAll('.comment-item.hidden');
    const existingButton = post.querySelector('.show-more-comments');

    if (hiddenComments.length > 0) {
        if (existingButton) {
            existingButton.querySelector('span').textContent = `Voir ${hiddenComments.length} commentaire${hiddenComments.length > 1 ? 's' : ''} de plus`;
        } else {
            const button = document.createElement('button');
            button.className = 'show-more-comments w-full text-center py-2 text-xs text-gray-600 hover:text-indigo-600 font-medium rounded-lg mb-3';
            button.innerHTML = `<span>Voir ${hiddenComments.length} commentaire${hiddenComments.length > 1 ? 's' : ''} de plus</span>`;
            button.onclick = function () { showMoreComments(this); };

            const commentSection = post.querySelector('.comment-section');
            const inputContainer = commentSection.querySelector('.flex.gap-2');
            commentSection.insertBefore(button, inputContainer);
        }
    } else {
        if (existingButton) {
            existingButton.remove();
        }
    }
}

// Initialiser l'affichage des commentaires (à appeler au chargement de chaque post)
function initializeCommentsDisplay(post) {
    const commentList = post.querySelector('.comment-section .space-y-2');
    const allComments = commentList.querySelectorAll('.comment-item');

    // Cacher tous les commentaires après le 10ème
    allComments.forEach((comment, index) => {
        if (index >= 10) {
            comment.classList.add('hidden');
        }
    });

    // Ajouter le bouton "Voir plus" si nécessaire
    updateShowMoreButton(post);
}

// Soumettre avec Enter
function handleCommentSubmit(event, input) {
    if (event.key === 'Enter') {
        addComment(input);
    }
}

// Modifier un commentaire
function editComment(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!comment) return;

    const commentText = comment.querySelector('.comment-text');
    const currentText = commentText.textContent.trim();

    // Créer un champ d'édition inline
    const editContainer = document.createElement('div');
    editContainer.className = 'flex gap-2 mt-1';
    editContainer.innerHTML = `
                <input type="text" 
                       class="flex-1 px-2 py-1 text-xs border border-indigo-400 rounded focus:outline-none focus:ring-1 focus:ring-indigo-400" 
                       value="${escapeHtml(currentText)}"
                       id="editInput${commentId}">
                <button onclick="saveCommentEdit(${commentId})" class="px-2 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 transition-colors">
                    ✓
                </button>
                <button onclick="cancelCommentEdit(${commentId})" class="px-2 py-1 bg-gray-300 text-gray-700 text-xs rounded hover:bg-gray-400 transition-colors">
                    ✕
                </button>
            `;

    // Sauvegarder le texte original
    comment.setAttribute('data-original-text', currentText);

    // Cacher le texte et afficher l'éditeur
    commentText.style.display = 'none';
    commentText.parentElement.appendChild(editContainer);

    // Focus sur l'input
    setTimeout(() => {
        const input = document.getElementById(`editInput${commentId}`);
        input.focus();
        input.select();
    }, 50);
}

// Sauvegarder l'édition du commentaire
async function saveCommentEdit(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!comment) return;

    const input = document.getElementById(`editInput${commentId}`);
    const newText = input.value.trim();
    if (!newText) return;

    const commentTextEl = comment.querySelector('.comment-text');
    const timeSpan = comment.querySelector('.text-xs.text-gray-400');
    const editor = input.closest('.flex.gap-2');

    // Option UX : désactiver l’input pendant l’envoi
    input.disabled = true;

    try {
        const response = await fetch('/comments/edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                comment_id: commentId,
                content: newText
            })
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error('Échec de la mise à jour');
        }

        // ✅ UI mise à jour UNIQUEMENT après succès serveur
        commentTextEl.textContent = data.comment.content;
        commentTextEl.style.display = '';
        editor.remove();

        if (!timeSpan.textContent.includes('modifié')) {
            timeSpan.textContent += ' · modifié';
        }

        showEditSuccess();

        return { success: true, comment: data.comment };

    } catch (error) {
        console.error('Erreur lors de la modification du commentaire:', error);

        // Réactiver l’input en cas d’erreur
        input.disabled = false;

        return { success: false, error: error.message };
    }
}


// Annuler l'édition du commentaire
function cancelCommentEdit(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!comment) return;

    const commentText = comment.querySelector('.comment-text');
    commentText.style.display = '';

    // Supprimer l'éditeur
    const editContainer = comment.querySelector('.flex.gap-2.mt-1');
    if (editContainer) {
        editContainer.remove();
    }
}

// Supprimer un commentaire
function deleteComment(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!comment) return;

    // Animation de disparition
    comment.style.opacity = '0';
    comment.style.transform = 'translateX(-10px)';
    comment.style.transition = 'all 0.2s ease';

    setTimeout(() => {
        const post = comment.closest('.post-card');
        comment.remove();

        // Mettre à jour le compteur
        const commentCount = post.querySelector('.flex.items-center.gap-4 span:nth-child(2) span');
        if (commentCount) {
            const current = parseInt(commentCount.textContent.replace(/[^0-9]/g, ''));
            commentCount.textContent = Math.max(0, current - 1);
        }
    }, 200);
}

// Sélection sondage
function selectPollOption(option) {
    const poll = option.closest('.space-y-2');
    const allOptions = poll.querySelectorAll('.poll-option');

    allOptions.forEach(opt => opt.classList.remove('selected'));
    option.classList.add('selected');
}

// Utilitaires
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatNumber(num) {
    if (num >= 1000) {
        return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
    }
    return num.toString();
}

// Envoyer le commentaire en base de données
async function saveCommentToDatabase(postId, commentText) {
    try {
        const response = await fetch('/comments/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                post_id: postId,
                content: commentText
            })
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();

        return {
            success: true,
            comment: data.comment
        };

    } catch (error) {
        console.error('Erreur lors de l\'enregistrement du commentaire:', error);
        return {
            success: false,
            error: error.message
        };
    }
}

async function saveShareToDatabase(postId) {
    try {
        const response = await fetch('/posts/share', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                post_id: postId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();

    } catch (error) {
        console.error('Erreur enregistrement partage:', error);
    }
}
async function saveShareToDatabases(postId, url) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                post_id: postId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();

    } catch (error) {
        console.error('Erreur enregistrement partage:', error);
    }
}
function openImageFullscreen(imageSrc) {
    // Évite d’ouvrir plusieurs modals
    if (document.getElementById('image-fullscreen-modal')) return;

    const modal = document.createElement('div');
    modal.id = 'image-fullscreen-modal';
    modal.className =
        'fixed inset-0 z-50 bg-black bg-opacity-95 flex items-center justify-center p-4';

    // Sauvegarde l’état du scroll
    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';

    function closeModal() {
        if (!modal) return;

        document.body.style.overflow = previousOverflow;
        document.removeEventListener('keydown', onKeyDown);
        modal.remove();
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    }

    modal.addEventListener('click', closeModal);
    document.addEventListener('keydown', onKeyDown);

    modal.innerHTML = `
        <button 
            class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors"
            aria-label="Fermer"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <img 
            src="${imageSrc}"
            class="max-w-full max-h-full object-contain"
        />
    `;

    // Empêche la fermeture quand on clique sur l’image ou le bouton
    modal.querySelector('img').addEventListener('click', e => e.stopPropagation());
    modal.querySelector('button').addEventListener('click', e => {
        e.stopPropagation();
        closeModal();
    });

    document.body.appendChild(modal);
}


// Ouvrir une vidéo en plein écran
function openVideoFullscreen(container) {
    const video = container.querySelector('video');
    const videoSrc = video.querySelector('source').src;

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 bg-black flex items-center justify-center';

    modal.innerHTML = `
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10" 
                onclick="closeVideoFullscreen(this)">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <video class="w-full h-full" controls autoplay>
            <source src="${videoSrc}" type="video/mp4">
        </video>
    `;

    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeVideoFullscreen(button) {
    const modal = button.closest('.fixed');
    const video = modal.querySelector('video');
    video.pause();
    modal.remove();
    document.body.style.overflow = '';
}

// Voter sur un sondage
async function votePoll(pollId, optionId, optionElement) {
    try {
        const response = await fetch(`/polls/vote/`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                poll_id: pollId,
                option_id: optionId
            })
        });

        const data = await response.json();

        if (data.success) {
            // Mettre à jour les pourcentages
            const poll = optionElement.closest('.space-y-2');
            const options = poll.querySelectorAll('.poll-option');

            data.poll.options.forEach((opt, index) => {
                const progress = options[index].querySelector('.poll-progress');
                const percentage = options[index].querySelector('.poll-percentage');
                const percent = data.poll.total_votes > 0
                    ? Math.round((opt.votes_count / data.poll.total_votes) * 100)
                    : 0;

                progress.style.width = percent + '%';
                percentage.textContent = percent + '%';
            });

            // Marquer comme sélectionné
            options.forEach(opt => opt.classList.remove('selected'));
            optionElement.classList.add('selected');

            // Mettre à jour le total
            const totalVotes = poll.nextElementSibling.querySelector('.font-semibold');
            totalVotes.textContent = data.poll.total_votes.toLocaleString();
        }
    } catch (error) {
        console.error('Erreur vote sondage:', error);
        alert('Erreur lors du vote. Veuillez réessayer.');
    }
}


// ============================================
// INITIALISATION GLOBALE
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});



// ============================================
// FONCTIONS D'INITIALISATION
// ============================================

// 1. Pagination des commentaires
function initializeCommentsPagination() {
    document.querySelectorAll('.post-card').forEach(post => {
        const commentList = post.querySelector('.comment-section .space-y-2');
        if (!commentList) return;

        const allComments = commentList.querySelectorAll('.comment-item');
        allComments.forEach((comment, index) => {
            if (index >= 10) {
                comment.classList.add('hidden');
            }
        });

        updateShowMoreButton(post);
    });
}

// 2. Clic extérieur pour fermer les menus
function initializeClickOutside() {
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.post-options')) {
            document.querySelectorAll('.options-menu.active').forEach(menu => {
                menu.classList.remove('active');
            });
        }
    });
}

// 3. Touche Escape pour fermer les modals
function initializeEscapeKey() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Fermer modal de partage
            const shareModal = document.getElementById('shareModal');
            if (shareModal?.classList.contains('active')) {
                closeShareModal();
            }

            // Fermer modal d'édition
            const editModal = document.getElementById('editModal');
            if (editModal?.classList.contains('active')) {
                closeEditModal();
            }

            // Fermer fullscreen image/video
            const fullscreen = document.querySelector('.fixed.inset-0.z-50');
            if (fullscreen) {
                fullscreen.remove();
                document.body.style.overflow = '';
            }
        }
    });
}

// 4. Lazy loading des vidéos (performance)
function initializeVideoLazyLoading() {
    const videos = document.querySelectorAll('video[data-video-src]');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const video = entry.target;
                    if (!video.src && video.dataset.videoSrc) {
                        video.src = video.dataset.videoSrc;
                        observer.unobserve(video);
                    }
                }
            });
        });

        videos.forEach(video => observer.observe(video));
    } else {
        // Fallback pour anciens navigateurs
        videos.forEach(video => {
            if (video.dataset.videoSrc) {
                video.src = video.dataset.videoSrc;
            }
        });
    }
}
function initializeApp() {
    console.log('🚀 Initialisation du feed...');

    // 1. Initialiser la pagination des commentaires
    initializeCommentsPagination();

    // 2. Fermer les menus au clic extérieur
    initializeClickOutside();

    // 3. Fermer les modals avec Escape
    initializeEscapeKey();

    // 4. Lazy loading des vidéos
    initializeVideoLazyLoading();

    console.log('✅ Initialisation terminée');
}

initializeApp();