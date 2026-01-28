// Ouvrir une image en plein écran
function openImageFullscreen(imageSrc) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 bg-black bg-opacity-95 flex items-center justify-center p-4';
    modal.onclick = function() { this.remove(); };
    
    modal.innerHTML = `
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors" onclick="this.parentElement.remove()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img src="${imageSrc}" 
             class="max-w-full max-h-full object-contain" 
             onclick="event.stopPropagation()">
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
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
        const response = await fetch(`/api/polls/${pollId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ option_id: optionId })
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

// Correction fonction addComment avec postId
function addComment(input, postId) {
    const text = input.value.trim();
    if (!text) return;
    
    const post = input.closest('.post-card');
    const commentList = post.querySelector('.comment-section .space-y-2');
    const commentSection = post.querySelector('.comment-section');
    
    // Désactiver l'input
    input.disabled = true;
    
    // Envoyer en base
    saveCommentToDatabase(postId, text).then(result => {
        input.disabled = false;
        
        if (!result.success) {
            alert('Erreur lors de l\'envoi du commentaire.');
            return;
        }
        
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
        
        commentList.insertBefore(comment, commentList.firstChild);
        input.value = '';
        
        // Mettre à jour compteur
        const commentCount = post.querySelector('.flex.items-center.gap-4 span:nth-child(2) span');
        if (commentCount) {
            const current = parseInt(commentCount.textContent.replace(/[^0-9]/g, ''));
            commentCount.textContent = current + 1;
        }
        
        updateShowMoreButton(post);
    });
}