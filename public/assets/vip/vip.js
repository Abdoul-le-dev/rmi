/**
 * ============================================================================
 * TRADE HUB - SCRIPT JAVASCRIPT PRINCIPAL
 * ============================================================================
 * 
 * Description: Script principal pour gérer toutes les interactions de l'interface
 * Version: 1.0.0
 * Auteur: Trade Hub Team
 * 
 * Fonctionnalités principales:
 * - Gestion des panels coulissants (notifications, messages)
 * - Menu utilisateur (dropdown)
 * - Menu mobile responsive
 * - Système de notifications toast
 * - Gestion des conversations
 * - Animations et transitions
 * - Gestion du clavier (raccourcis)
 * - Performance et optimisation
 * 
 * ============================================================================
 */

'use strict';

// ============================================================================
// VARIABLES GLOBALES ET ÉLÉMENTS DOM
// ============================================================================

/**
 * Objet contenant toutes les références aux éléments DOM
 * Permet d'éviter les multiples querySelector et améliore les performances
 */
const DOM = {
    // Boutons d'ouverture des panels
    notificationsBtn: document.getElementById('notifications-btn'),
    messagesBtn: document.getElementById('messages-btn'),
    userMenuBtn: document.getElementById('user-menu-btn'),
    mobileMenuBtn: document.getElementById('mobile-menu-btn'),

    // Panels
    notificationsPanel: document.getElementById('notifications-panel'),
    messagesPanel: document.getElementById('messages-panel'),
    userDropdown: document.getElementById('user-dropdown'),
    mobileMenu: document.getElementById('mobile-menu'),

    // Boutons de fermeture
    closeNotifications: document.getElementById('close-notifications'),
    closeMessages: document.getElementById('close-messages'),
    closeMobileMenu: document.getElementById('close-mobile-menu'),

    // Overlays
    panelOverlay: document.getElementById('panel-overlay'),
    mobileMenuOverlay: document.getElementById('mobile-menu-overlay'),

    // Zone de conversation
    conversationsList: document.getElementById('conversations-list'),
    conversationDetail: document.getElementById('conversation-detail'),

    // Items de conversation
    conversationItems: document.querySelectorAll('.conversation-item')
};

// ============================================================================
// CONFIGURATION ET CONSTANTES
// ============================================================================

/**
 * Configuration de l'application
 */
const CONFIG = {
    // Durées des animations (en ms)
    ANIMATION_DURATION: 300,
    TOAST_DURATION: 3000,
    
    // Breakpoints responsive
    MOBILE_BREAKPOINT: 768,
    TABLET_BREAKPOINT: 1024,
    
    // Classes CSS
    CLASSES: {
        HIDDEN: 'hidden',
        ACTIVE: 'active',
        FADE_IN: 'fade-in',
        SCALE_IN: 'scale-in'
    }
};

// ============================================================================
// GESTION DES PANELS COULISSANTS
// ============================================================================

/**
 * Classe pour gérer les panels coulissants
 */
class PanelManager {
    constructor() {
        this.activePanel = null;
        this.init();
    }

    /**
     * Initialise les event listeners pour les panels
     */
    init() {
        // Panel notifications
        DOM.notificationsBtn?.addEventListener('click', () => this.openPanel('notifications'));
        DOM.closeNotifications?.addEventListener('click', () => this.closePanel('notifications'));

        // Panel messages
        DOM.messagesBtn?.addEventListener('click', () => this.openPanel('messages'));
        DOM.closeMessages?.addEventListener('click', () => this.closePanel('messages'));

        // Fermeture avec overlay
        DOM.panelOverlay?.addEventListener('click', () => this.closeAllPanels());
    }

    /**
     * Ouvre un panel spécifique
     * @param {string} panelType - Type de panel ('notifications' ou 'messages')
     */
    openPanel(panelType) {
        // Fermer tous les autres panels d'abord
        this.closeAllPanels();

        // Déterminer quel panel ouvrir
        const panel = panelType === 'notifications' ? DOM.notificationsPanel : DOM.messagesPanel;

        if (panel) {
            // Ajouter la classe active pour l'animation
            panel.classList.add(CONFIG.CLASSES.ACTIVE);
            
            // Afficher l'overlay
            DOM.panelOverlay?.classList.remove(CONFIG.CLASSES.HIDDEN);
            
            // Bloquer le scroll du body
            document.body.style.overflow = 'hidden';
            
            // Mémoriser le panel actif
            this.activePanel = panelType;

            // Log pour debug
            console.log(`✅ Panel ${panelType} ouvert`);
        }
    }

    /**
     * Ferme un panel spécifique
     * @param {string} panelType - Type de panel à fermer
     */
    closePanel(panelType) {
        const panel = panelType === 'notifications' ? DOM.notificationsPanel : DOM.messagesPanel;

        if (panel) {
            // Retirer la classe active
            panel.classList.remove(CONFIG.CLASSES.ACTIVE);
            
            // Cacher l'overlay
            DOM.panelOverlay?.classList.add(CONFIG.CLASSES.HIDDEN);
            
            // Restaurer le scroll du body
            document.body.style.overflow = 'auto';
            
            // Réinitialiser le panel actif
            this.activePanel = null;

            console.log(`✅ Panel ${panelType} fermé`);
        }
    }

    /**
     * Ferme tous les panels ouverts
     */
    closeAllPanels() {
        // Fermer le panel notifications
        DOM.notificationsPanel?.classList.remove(CONFIG.CLASSES.ACTIVE);
        
        // Fermer le panel messages
        DOM.messagesPanel?.classList.remove(CONFIG.CLASSES.ACTIVE);
        
        // Cacher l'overlay
        DOM.panelOverlay?.classList.add(CONFIG.CLASSES.HIDDEN);
        
        // Restaurer le scroll
        document.body.style.overflow = 'auto';
        
        // Réinitialiser
        this.activePanel = null;
    }

    /**
     * Vérifie si un panel est ouvert
     * @returns {boolean}
     */
    isPanelOpen() {
        return this.activePanel !== null;
    }
}

// ============================================================================
// GESTION DU MENU UTILISATEUR (DROPDOWN)
// ============================================================================

/**
 * Classe pour gérer le dropdown du menu utilisateur
 */
class UserMenuManager {
    constructor() {
        this.isOpen = false;
        this.init();
    }

    /**
     * Initialise les event listeners
     */
    init() {
        // Toggle du dropdown au clic sur le bouton
        DOM.userMenuBtn?.addEventListener('click', (e) => this.toggle(e));

        // Fermer le dropdown en cliquant ailleurs
        document.addEventListener('click', (e) => this.handleOutsideClick(e));
    }

    /**
     * Toggle l'état du dropdown
     * @param {Event} e - Event object
     */
    toggle(e) {
        e.stopPropagation();
        
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    /**
     * Ouvre le dropdown
     */
    open() {
        DOM.userDropdown?.classList.remove(CONFIG.CLASSES.HIDDEN);
        DOM.userDropdown?.classList.add(CONFIG.CLASSES.SCALE_IN);
        this.isOpen = true;
        console.log('✅ Menu utilisateur ouvert');
    }

    /**
     * Ferme le dropdown
     */
    close() {
        DOM.userDropdown?.classList.add(CONFIG.CLASSES.HIDDEN);
        this.isOpen = false;
        console.log('✅ Menu utilisateur fermé');
    }

    /**
     * Gère les clics en dehors du dropdown
     * @param {Event} e - Event object
     */
    handleOutsideClick(e) {
        if (this.isOpen && 
            !DOM.userMenuBtn?.contains(e.target) && 
            !DOM.userDropdown?.contains(e.target)) {
            this.close();
        }
    }
}

// ============================================================================
// GESTION DU MENU MOBILE
// ============================================================================

/**
 * Classe pour gérer le menu mobile
 */
class MobileMenuManager {
    constructor() {
        this.isOpen = false;
        this.init();
    }

    /**
     * Initialise les event listeners
     */
    init() {
        // Ouvrir le menu
        DOM.mobileMenuBtn?.addEventListener('click', () => this.open());

        // Fermer le menu
        DOM.closeMobileMenu?.addEventListener('click', () => this.close());
        DOM.mobileMenuOverlay?.addEventListener('click', () => this.close());
    }

    /**
     * Ouvre le menu mobile
     */
    open() {
        DOM.mobileMenu?.classList.remove(CONFIG.CLASSES.HIDDEN);
        document.body.style.overflow = 'hidden';
        this.isOpen = true;
        console.log('✅ Menu mobile ouvert');
    }

    /**
     * Ferme le menu mobile
     */
    close() {
        DOM.mobileMenu?.classList.add(CONFIG.CLASSES.HIDDEN);
        document.body.style.overflow = 'auto';
        this.isOpen = false;
        console.log('✅ Menu mobile fermé');
    }
}

// ============================================================================
// GESTION DES CONVERSATIONS
// ============================================================================

/**
 * Classe pour gérer les conversations
 */
class ConversationManager {
    constructor() {
        this.activeConversation = null;
        this.init();
    }

    /**
     * Initialise les event listeners pour les conversations
     */
    init() {
        DOM.conversationItems.forEach(item => {
            item.addEventListener('click', () => this.selectConversation(item));
        });
    }

    /**
     * Sélectionne une conversation
     * @param {HTMLElement} item - Élément de conversation cliqué
     */
    selectConversation(item) {
        // Retirer la classe active de tous les items
        DOM.conversationItems.forEach(i => {
            i.classList.remove(CONFIG.CLASSES.ACTIVE);
        });

        // Ajouter la classe active à l'item sélectionné
        item.classList.add(CONFIG.CLASSES.ACTIVE);

        // Mémoriser la conversation active
        this.activeConversation = item.dataset.conversation;

        // Sur mobile, afficher la zone de conversation
        if (window.innerWidth < CONFIG.TABLET_BREAKPOINT) {
            this.showConversationDetail();
        }

        console.log(`✅ Conversation ${this.activeConversation} sélectionnée`);
    }

    /**
     * Affiche la zone de détail de conversation (mobile)
     */
    showConversationDetail() {
        DOM.conversationsList?.classList.add(CONFIG.CLASSES.HIDDEN);
        DOM.conversationDetail?.classList.remove(CONFIG.CLASSES.HIDDEN);
        DOM.conversationDetail?.classList.add('flex');
    }

    /**
     * Affiche la liste des conversations (mobile)
     */
    showConversationsList() {
        DOM.conversationsList?.classList.remove(CONFIG.CLASSES.HIDDEN);
        DOM.conversationDetail?.classList.add(CONFIG.CLASSES.HIDDEN);
        DOM.conversationDetail?.classList.remove('flex');
    }
}

// ============================================================================
// SYSTÈME DE NOTIFICATIONS TOAST
// ============================================================================

/**
 * Classe pour gérer les notifications toast
 */
class ToastManager {
    /**
     * Affiche une notification toast
     * @param {string} message - Message à afficher
     * @param {string} type - Type de toast ('success', 'error', 'info', 'warning')
     * @param {number} duration - Durée d'affichage en ms
     */
    static show(message, type = 'info', duration = CONFIG.TOAST_DURATION) {
        // Créer l'élément toast
        const toast = document.createElement('div');
        
        // Déterminer les couleurs selon le type
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-indigo-500',
            warning: 'bg-yellow-500'
        };

        // Déterminer l'icône selon le type
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };

        // Appliquer les classes et le contenu
        toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 ${CONFIG.CLASSES.FADE_IN} ${colors[type] || colors.info}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas ${icons[type] || icons.info}"></i>
                <span>${message}</span>
            </div>
        `;

        // Ajouter au DOM
        document.body.appendChild(toast);

        // Retirer après la durée spécifiée
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), CONFIG.ANIMATION_DURATION);
        }, duration);

        console.log(`📢 Toast affiché: ${message} (${type})`);
    }
}

// ============================================================================
// GESTION DU CLAVIER (RACCOURCIS)
// ============================================================================

/**
 * Classe pour gérer les raccourcis clavier
 */
class KeyboardManager {
    constructor() {
        this.init();
    }

    /**
     * Initialise les event listeners pour le clavier
     */
    init() {
        document.addEventListener('keydown', (e) => this.handleKeyPress(e));
    }

    /**
     * Gère les touches du clavier
     * @param {KeyboardEvent} e - Event object
     */
    handleKeyPress(e) {
        // Touche Escape - Ferme tous les panels et menus
        if (e.key === 'Escape') {
            panelManager.closeAllPanels();
            userMenuManager.close();
            mobileMenuManager.close();
            console.log('⌨️ ESC pressé - Tout fermé');
        }

        // Ctrl + K ou Cmd + K - Focus sur la recherche
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="text"]');
            searchInput?.focus();
            console.log('⌨️ Recherche activée');
        }

        // Ctrl + N ou Cmd + N - Ouvrir notifications
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            panelManager.openPanel('notifications');
            console.log('⌨️ Notifications ouvertes');
        }

        // Ctrl + M ou Cmd + M - Ouvrir messages
        if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
            e.preventDefault();
            panelManager.openPanel('messages');
            console.log('⌨️ Messages ouverts');
        }
    }
}

// ============================================================================
// GESTION RESPONSIVE
// ============================================================================

/**
 * Classe pour gérer le comportement responsive
 */
class ResponsiveManager {
    constructor() {
        this.init();
    }

    /**
     * Initialise les event listeners pour le resize
     */
    init() {
        window.addEventListener('resize', () => this.handleResize());
        // Appeler au chargement initial
        this.handleResize();
    }

    /**
     * Gère le redimensionnement de la fenêtre
     */
    handleResize() {
        const width = window.innerWidth;

        // Desktop - Afficher les deux colonnes du panel messages
        if (width >= CONFIG.TABLET_BREAKPOINT) {
            DOM.conversationsList?.classList.remove(CONFIG.CLASSES.HIDDEN);
            DOM.conversationDetail?.classList.remove(CONFIG.CLASSES.HIDDEN);
            DOM.conversationDetail?.classList.add('flex');
        } else {
            // Mobile - Afficher uniquement la liste par défaut
            if (!conversationManager.activeConversation) {
                DOM.conversationsList?.classList.remove(CONFIG.CLASSES.HIDDEN);
                DOM.conversationDetail?.classList.add(CONFIG.CLASSES.HIDDEN);
            }
        }
    }
}

// ============================================================================
// ANIMATIONS ET OBSERVERS
// ============================================================================

/**
 * Classe pour gérer les animations au scroll
 */
class AnimationManager {
    constructor() {
        this.init();
    }

    /**
     * Initialise l'Intersection Observer pour les animations
     */
    init() {
        // Configuration de l'observer
        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        // Créer l'observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add(CONFIG.CLASSES.FADE_IN);
                }
            });
        }, options);

        // Observer tous les éléments à animer
        document.querySelectorAll('.card-hover, article, .bg-white').forEach(el => {
            observer.observe(el);
        });

        console.log('✅ Animation Observer initialisé');
    }
}

// ============================================================================
// LAZY LOADING DES IMAGES
// ============================================================================

/**
 * Classe pour gérer le lazy loading des images
 */
class LazyLoadManager {
    constructor() {
        this.init();
    }

    /**
     * Initialise le lazy loading
     */
    init() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src || img.src;
                        img.src = src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            // Observer toutes les images avec data-src
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });

            console.log('✅ Lazy Loading initialisé');
        }
    }
}

// ============================================================================
// GESTION DES INTERACTIONS UTILISATEUR
// ============================================================================

/**
 * Classe pour gérer les interactions diverses
 */
class InteractionManager {
    constructor() {
        this.init();
    }

    /**
     * Initialise les interactions
     */
    init() {
        this.initLikes();
        this.initFollowButtons();
        this.initNotificationRead();
    }

    /**
     * Initialise les boutons like
     */
    initLikes() {
        document.querySelectorAll('.fa-heart').forEach(heart => {
            heart.parentElement?.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('.fa-heart');
                
                if (icon?.classList.contains('far')) {
                    // Liker
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500');
                    
                    // Animation de pulsation
                    icon.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 200);
                } else {
                    // Unliker
                    icon?.classList.remove('fas', 'text-red-500');
                    icon?.classList.add('far');
                }
            });
        });
    }

    /**
     * Initialise les boutons suivre
     */
    initFollowButtons() {
        document.querySelectorAll('button').forEach(btn => {
            if (btn.textContent.trim() === 'Suivre') {
                btn.addEventListener('click', function() {
                    if (this.textContent.trim() === 'Suivre') {
                        this.textContent = 'Suivi';
                        this.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                        this.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                        ToastManager.show('Vous suivez maintenant cet utilisateur', 'success');
                    } else {
                        this.textContent = 'Suivre';
                        this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                        this.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
                        ToastManager.show('Vous ne suivez plus cet utilisateur', 'info');
                    }
                });
            }
        });
    }

    /**
     * Initialise le marquage des notifications comme lues
     */
    initNotificationRead() {
        document.querySelectorAll('#notifications-panel .bg-white').forEach(notification => {
            notification.addEventListener('click', function() {
                this.style.opacity = '0.6';
                ToastManager.show('Notification marquée comme lue', 'success', 2000);
            });
        });
    }
}

// ============================================================================
// MONITORING DE PERFORMANCE
// ============================================================================

/**
 * Classe pour monitorer les performances
 */
class PerformanceMonitor {
    /**
     * Log les performances de chargement de la page
     */
    static logPageLoad() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                const perfData = window.performance.timing;
                const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                console.log(`⚡ Page chargée en ${pageLoadTime}ms`);
                
                // Afficher d'autres métriques
                const domContentLoaded = perfData.domContentLoadedEventEnd - perfData.navigationStart;
                console.log(`📄 DOM chargé en ${domContentLoaded}ms`);
            });
        }
    }
}

// ============================================================================
// FONCTIONS UTILITAIRES
// ============================================================================

/**
 * Classe avec des fonctions utilitaires
 */
class Utils {
    /**
     * Debounce une fonction
     * @param {Function} func - Fonction à debounce
     * @param {number} wait - Temps d'attente en ms
     * @returns {Function}
     */
    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Throttle une fonction
     * @param {Function} func - Fonction à throttle
     * @param {number} limit - Limite de temps en ms
     * @returns {Function}
     */
    static throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Formate une date de manière relative
     * @param {Date} date - Date à formater
     * @returns {string}
     */
    static formatRelativeTime(date) {
        const now = new Date();
        const diff = now - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 0) return `il y a ${days} jour${days > 1 ? 's' : ''}`;
        if (hours > 0) return `il y a ${hours} heure${hours > 1 ? 's' : ''}`;
        if (minutes > 0) return `il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
        return 'à l\'instant';
    }
}

// ============================================================================
// INITIALISATION DE L'APPLICATION
// ============================================================================

/**
 * Classe principale de l'application
 */
class TradeHubApp {
    constructor() {
        console.log('🚀 Initialisation de Trade Hub...');
        this.init();
    }

    /**
     * Initialise toutes les fonctionnalités
     */
    init() {
        // Initialiser tous les managers
        window.panelManager = new PanelManager();
        window.userMenuManager = new UserMenuManager();
        window.mobileMenuManager = new MobileMenuManager();
        window.conversationManager = new ConversationManager();
        window.keyboardManager = new KeyboardManager();
        window.responsiveManager = new ResponsiveManager();
        window.animationManager = new AnimationManager();
        window.lazyLoadManager = new LazyLoadManager();
        window.interactionManager = new InteractionManager();

        // Monitoring de performance
        PerformanceMonitor.logPageLoad();

        // Afficher un message de bienvenue
        window.addEventListener('load', () => {
            setTimeout(() => {
                ToastManager.show('Bienvenue sur Trade Hub ! 🚀', 'success');
            }, 1000);
        });

        console.log('✅ Trade Hub initialisé avec succès !');
        this.logKeyboardShortcuts();
    }

    /**
     * Log les raccourcis clavier disponibles
     */
    logKeyboardShortcuts() {
        console.log(`
╔════════════════════════════════════════════════════════════╗
║           RACCOURCIS CLAVIER DISPONIBLES                   ║
╠════════════════════════════════════════════════════════════╣
║  ESC                  → Fermer tous les panels             ║
║  Ctrl/Cmd + K         → Focus sur la recherche             ║
║  Ctrl/Cmd + N         → Ouvrir les notifications           ║
║  Ctrl/Cmd + M         → Ouvrir les messages                ║
╚════════════════════════════════════════════════════════════╝
        `);
    }
}

// ============================================================================
// DÉMARRAGE DE L'APPLICATION
// ============================================================================

/**
 * Démarrer l'application quand le DOM est prêt
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new TradeHubApp();
    });
} else {
    new TradeHubApp();
}

// ============================================================================
// EXPORTS (pour utilisation dans d'autres fichiers si nécessaire)
// ============================================================================

// Rendre certaines classes disponibles globalement si nécessaire
window.TradeHub = {
    ToastManager,
    Utils,
    CONFIG
};

console.log('📦 Script Trade Hub chargé');