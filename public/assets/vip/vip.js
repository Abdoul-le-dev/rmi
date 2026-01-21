
// ========================================
// GESTION DU MENU MOBILE
// ========================================
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const closeMobileMenu = document.getElementById('close-mobile-menu');
const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

// Ouvrir le menu mobile
mobileMenuBtn?.addEventListener('click', () => {
    mobileMenu.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

// Fermer le menu mobile
const closeMobileMenuFn = () => {
    mobileMenu.classList.add('hidden');
    document.body.style.overflow = 'auto';
};

closeMobileMenu?.addEventListener('click', closeMobileMenuFn);
mobileMenuOverlay?.addEventListener('click', closeMobileMenuFn);

// ========================================
// GESTION DU DROPDOWN UTILISATEUR
// ========================================
const userMenuBtn = document.getElementById('user-menu-btn');
const userDropdown = document.getElementById('user-dropdown');
const notification_button = document.getElementById('notification');
const menu_menu = document.getElementById('menu_menu');



// ========================================
// ANIMATIONS AU SCROLL
// ========================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
        }
    });
}, observerOptions);

// Observer tous les posts
document.querySelectorAll('article').forEach(article => {
    observer.observe(article);
});

// ========================================
// GESTION DES LIKES
// ========================================
document.querySelectorAll('.fa-heart').forEach(heart => {
    heart.parentElement.addEventListener('click', function (e) {
        e.preventDefault();
        const icon = this.querySelector('.fa-heart');

        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-red-500');

            // Animation de pulsation
            icon.style.transform = 'scale(1.3)';
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 200);
        } else {
            icon.classList.remove('fas', 'text-red-500');
            icon.classList.add('far');
        }
    });
});

// ========================================
// SMOOTH SCROLL
// ========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========================================
// LAZY LOADING DES IMAGES
// ========================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// ========================================
// NOTIFICATION TOAST (Exemple)
// ========================================
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 fade-in ${type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
            'bg-indigo-500'
        }`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Exemple d'utilisation
// showNotification('Bienvenue sur Trade Hub !', 'success');

// ========================================
// GESTION DU BOUTON "SUIVRE"
// ========================================
/*document.querySelectorAll('button:contains("Suivre")').forEach(btn => {
    btn.addEventListener('click', function () {
        if (this.textContent.trim() === 'Suivre') {
            this.textContent = 'Suivi';
            this.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            this.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            showNotification('Vous suivez maintenant cet utilisateur', 'success');
        } else {
            this.textContent = 'Suivre';
            this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            this.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
        }
    });
});
*/
// ========================================
// PRÉCHARGEMENT DES IMAGES
// ========================================
function preloadImages() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        const src = img.dataset.src || img.src;
        if (src) {
            const preloadImg = new Image();
            preloadImg.src = src;
        }
    });
}

//message vue

function message_view() {
    window.location.href = "vip/message"
}


// Précharger au chargement de la page
window.addEventListener('load', preloadImages);

// ========================================
// PERFORMANCE MONITORING
// ========================================
if ('performance' in window) {
    window.addEventListener('load', () => {
        const perfData = window.performance.timing;
        const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
        console.log(`Page chargée en ${pageLoadTime}ms`);
    });
}

// Fonction pour déplacer le bouton cliqué en haut
function moveToTop(clickedElement) {
    const container = document.getElementById('sidebar-buttons');
    const firstElement = container.firstElementChild;

    // Si l'élément cliqué n'est pas déjà le premier
    if (clickedElement !== firstElement) {
        // Insérer l'élément cliqué avant le premier élément
        container.insertBefore(clickedElement, firstElement);
    }
}

// Tableau de fonctions pour générer différents sons de clic
const clickSounds = [
    // Son 1: Clic moderne et élégant
    function (audioContext) {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(900, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(450, audioContext.currentTime + 0.08);

        gainNode.gain.setValueAtTime(0.25, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.08);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.08);
    },

    // Son 2: Clic doux et raffiné
    function (audioContext) {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'triangle';
        oscillator.frequency.setValueAtTime(1200, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAt(600, audioContext.currentTime + 0.06);

        gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAt(0.01, audioContext.currentTime + 0.06);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.06);
    },

    // Son 3: Clic tech et précis
    function (audioContext) {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'square';
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAt(400, audioContext.currentTime + 0.05);

        gainNode.gain.setValueAtTime(0.15, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAt(0.01, audioContext.currentTime + 0.05);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.05);
    },

    // Son 4: Clic subtil et premium
    function (audioContext) {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(1000, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAt(500, audioContext.currentTime + 0.07);

        gainNode.gain.setValueAtTime(0.22, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAt(0.01, audioContext.currentTime + 0.07);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.07);
    },

    // Son 5: Clic vif et énergique
    function (audioContext) {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(1100, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAt(550, audioContext.currentTime + 0.09);

        gainNode.gain.setValueAtTime(0.28, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAt(0.01, audioContext.currentTime + 0.09);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.09);
    }
];

// Fonction pour jouer un son aléatoire parmi les 5
function playRandomClickSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();

    // Choisir un son aléatoire parmi les 5
    const randomIndex = Math.floor(Math.random() * clickSounds.length);
    const selectedSound = clickSounds[randomIndex];

    // Jouer le son sélectionné
    selectedSound(audioContext);
}