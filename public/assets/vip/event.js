let currentDate = new Date(2026, 0, 25); // 25 janvier 2026 (dimanche)
let currentView = 'week';
let selectedEventType = null;
let eventPosterData = null;
let events = [
    { id: 1, title: 'Réunion Trading', type: 'live', date: '2026-01-19', time: '08:00', duration: 2.5, color: 'blue', poster: null },
    { id: 2, title: 'Workshop Crypto', type: 'presentiel', date: '2026-01-19', time: '14:00', duration: 2, color: 'purple', poster: null },
    { id: 3, title: 'Masterclass IA', type: 'live', date: '2026-01-21', time: '09:00', duration: 2.5, color: 'emerald', poster: null },
    { id: 4, title: 'Conférence VIP', type: 'presentiel', date: '2026-01-22', time: '19:00', duration: 2, color: 'orange', poster: null },
    { id: 5, title: 'Session Live', type: 'live', date: '2026-01-25', time: '15:00', duration: 2, color: 'red', isLive: true, poster: null }
];

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    updateCalendarDisplay();
});

// Modal événement
function openEventModal() {
    const modal = document.getElementById('eventModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'active');
    document.body.style.overflow = 'hidden';

    // Réinitialiser
    document.getElementById('eventTitle').value = '';
    document.getElementById('eventDate').value = '';
    document.getElementById('eventTime').value = '';
    selectedEventType = null;
    eventPosterData = null;

    document.querySelectorAll('.event-type-btn').forEach(btn => {
        btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
        btn.classList.add('border-gray-300');
    });

    // Réinitialiser l'affiche
    document.getElementById('posterPreview').classList.add('hidden');
    document.getElementById('posterLabel').textContent = 'Ajouter une affiche';
}

// Prévisualiser l'affiche uploadée
function previewPoster() {
    const input = document.getElementById('eventPoster');
    const preview = document.getElementById('posterPreview');
    const image = document.getElementById('posterImage');
    const label = document.getElementById('posterLabel');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            eventPosterData = e.target.result;
            image.src = e.target.result;
            preview.classList.remove('hidden');
            label.textContent = '✓ Affiche ajoutée';
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Supprimer l'affiche
function removePoster() {
    eventPosterData = null;
    document.getElementById('eventPoster').value = '';
    document.getElementById('posterPreview').classList.add('hidden');
    document.getElementById('posterLabel').textContent = 'Ajouter une affiche';
}

// Ouvrir le modal de visualisation d'affiche
function openPosterModal(event) {
    if (!event.poster) return;

    const modal = document.getElementById('posterModal');
    const image = document.getElementById('posterModalImage');
    const title = document.getElementById('posterModalTitle');
    const info = document.getElementById('posterModalInfo');

    image.src = event.poster;
    title.textContent = event.title;
    info.textContent = `${event.type === 'live' ? 'Live Class' : 'Présentiel'} • ${event.date} à ${event.time}`;

    modal.classList.remove('hidden');
    modal.classList.add('flex', 'active');
    document.body.style.overflow = 'hidden';
}

// Afficher l'affiche d'un événement au hover
function showEventPoster(eventId) {
    const event = events.find(e => e.id === eventId);
    if (!event) return;

    const tooltip = document.getElementById('posterPreviewTooltip');
    const image = document.getElementById('posterPreviewImage');
    const title = document.getElementById('posterPreviewTitle');
    const info = document.getElementById('posterPreviewInfo');

    // Si pas d'affiche, générer un placeholder
    if (!event.poster) {
        const canvas = document.createElement('canvas');
        canvas.width = 400;
        canvas.height = 500;
        const ctx = canvas.getContext('2d');

        // Gradient selon la couleur de l'événement
        const colorMap = {
            'blue': ['#3b82f6', '#1e40af'],
            'purple': ['#a855f7', '#7e22ce'],
            'emerald': ['#10b981', '#047857'],
            'orange': ['#f97316', '#c2410c'],
            'red': ['#ef4444', '#b91c1c']
        };

        const [color1, color2] = colorMap[event.color] || ['#3b82f6', '#1e40af'];
        const gradient = ctx.createLinearGradient(0, 0, 400, 500);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, 400, 500);

        // Logo RMClass
        ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
        ctx.font = 'bold 80px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('RM', 200, 150);

        // Titre
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 40px Inter, sans-serif';
        ctx.fillText(event.title, 200, 280);

        // Type
        ctx.font = '24px Inter, sans-serif';
        ctx.fillText(event.type === 'live' ? 'Live Class' : 'Présentiel', 200, 330);

        // Horaires
        ctx.font = '20px Inter, sans-serif';
        ctx.fillText(`${event.time} • ${event.duration}h`, 200, 380);

        image.src = canvas.toDataURL();
    } else {
        image.src = event.poster;
    }

    title.textContent = event.title;
    info.textContent = `${event.type === 'live' ? 'Live Class' : 'Présentiel'} • ${event.time}`;
}

// Positionner et afficher le tooltip au hover
function showPosterTooltip(button, eventId) {
    showEventPoster(eventId);
    const tooltip = document.getElementById('posterPreviewTooltip');
    const rect = button.getBoundingClientRect();

    // Positionner à droite du bouton
    tooltip.style.left = (rect.right + 15) + 'px';
    tooltip.style.top = (rect.top - 100) + 'px';

    // Vérifier si hors écran à droite
    const tooltipRect = tooltip.getBoundingClientRect();
    if (tooltipRect.right > window.innerWidth) {
        tooltip.style.left = (rect.left - 265) + 'px'; // Afficher à gauche
    }

    tooltip.classList.remove('hidden');
}

// Cacher le tooltip
function hidePosterTooltip() {
    const tooltip = document.getElementById('posterPreviewTooltip');
    tooltip.classList.add('hidden');
}

function closeEventModal() {
    const modal = document.getElementById('eventModal');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
}

function selectEventType(type) {
    selectedEventType = type;
    document.querySelectorAll('.event-type-btn').forEach(btn => {
        btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
        btn.classList.add('border-gray-300');
    });

    const selectedBtn = document.getElementById(`type${type.charAt(0).toUpperCase() + type.slice(1)}`);
    selectedBtn.classList.remove('border-gray-300');
    selectedBtn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
}

function createEvent() {
    const title = document.getElementById('eventTitle').value.trim();
    const date = document.getElementById('eventDate').value;
    const time = document.getElementById('eventTime').value;
    const duration = parseFloat(document.getElementById('eventDuration').value);

    if (!title || !selectedEventType || !date || !time) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }

    const colors = ['blue', 'purple', 'emerald', 'orange', 'pink', 'teal'];
    const newEvent = {
        id: Date.now(),
        title,
        type: selectedEventType,
        date,
        time,
        duration,
        color: colors[Math.floor(Math.random() * colors.length)],
        isLive: false,
        poster: eventPosterData
    };

    events.push(newEvent);
    closeEventModal();
    updateCalendarDisplay();

    // Notification succès
    showNotification(eventPosterData ? 'Événement créé avec affiche !' : 'Événement créé avec succès !');
}

// Navigation temporelle
function changeYear() {
    const year = parseInt(document.getElementById('yearSelector').value);
    currentDate.setFullYear(year);
    updateCalendarDisplay();
}

function previousPeriod() {
    if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() - 7);
    } else if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() - 1);
    } else if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    }
    updateCalendarDisplay();
}

function nextPeriod() {
    if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() + 7);
    } else if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() + 1);
    } else if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + 1);
    }
    updateCalendarDisplay();
}

function goToToday() {
    currentDate = new Date(2026, 0, 25);
    document.getElementById('yearSelector').value = '2026';
    updateCalendarDisplay();
}

function changeView() {
    currentView = document.getElementById('viewSelector').value;
    updateCalendarDisplay();
}

// Mise à jour de l'affichage
function updateCalendarDisplay() {
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const monthsShort = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

    if (currentView === 'week') {
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay() + 1);

        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);

        const periodText = `${startOfWeek.getDate()} - ${endOfWeek.getDate()} ${months[endOfWeek.getMonth()]} ${endOfWeek.getFullYear()}`;
        const periodTextMobile = `${startOfWeek.getDate()} - ${endOfWeek.getDate()} ${monthsShort[endOfWeek.getMonth()]} ${endOfWeek.getFullYear()}`;

        document.getElementById('currentPeriod').textContent = periodText;
        document.getElementById('currentPeriodMobile').textContent = periodTextMobile;
    }
}

// Notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'all 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Fermer modal au clic extérieur
document.getElementById('eventModal').addEventListener('click', (e) => {
    if (e.target.id === 'eventModal') {
        closeEventModal();
    }
});

// Escape pour fermer
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeEventModal();
    }
});

// Tooltip "Bientôt disponible" pour le bouton agenda
function setupAgendaTooltips() {
    document.querySelectorAll('[data-agenda-tooltip]').forEach(el => {
        el.addEventListener('mouseenter', (e) => {
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed bg-gray-900 text-white text-xs px-3 py-2 rounded-lg z-50 pointer-events-none';
            tooltip.textContent = 'Bientôt disponible';
            tooltip.id = 'agenda-tooltip';

            const rect = el.getBoundingClientRect();
            tooltip.style.top = (rect.top - 40) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2) + 'px';
            tooltip.style.transform = 'translateX(-50%)';

            document.body.appendChild(tooltip);
        });

        el.addEventListener('mouseleave', () => {
            const tooltip = document.getElementById('agenda-tooltip');
            if (tooltip) tooltip.remove();
        });
    });
}

// Initialiser tooltips après chargement
setTimeout(setupAgendaTooltips, 100);