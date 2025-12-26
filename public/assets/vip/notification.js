const notifications = [
    { id: 1, user: 'JD', text: 'Jean Dupont a aimé votre publication', time: 'Il y a 5 min', unread: true },
    { id: 2, user: 'ML', text: 'Marie Lambert a commenté votre photo', time: 'Il y a 1h', unread: true },
    { id: 3, user: 'PB', text: 'Pierre Bernard vous a envoyé un message', time: 'Il y a 2h', unread: true },
    { id: 4, user: 'SD', text: 'Sophie Durand a partagé votre article', time: 'Il y a 3h', unread: true },
    { id: 5, user: 'AM', text: 'Alexandre Martin a réagi à votre commentaire', time: 'Il y a 5h', unread: true },
    { id: 6, user: 'CL', text: 'Camille Leroy vous suit maintenant', time: 'Hier', unread: false },
    { id: 7, user: 'TB', text: 'Thomas Blanc a mentionné votre nom', time: 'Hier', unread: false },
    { id: 8, user: 'EM', text: 'Emma Moreau a aimé votre commentaire', time: 'Il y a 2 jours', unread: false }
];

function toggleNotif() {
    const panel = document.getElementById('notifPanel');
    panel.classList.toggle('hidden');
    panel.classList.toggle('flex');
}

function renderNotifications() {
    const list = document.getElementById('notifList');
    list.innerHTML = notifications.map(notif => `
                <div class="p-3 border-b border-gray-200 cursor-pointer hover:bg-gray-100 transition flex gap-3 ${notif.unread ? 'bg-blue-50 hover:bg-blue-100' : ''}" onclick="markAsRead(${notif.id})">
                    <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                        ${notif.user}
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-900 leading-5 mb-1">${notif.text}</div>
                        <div class="text-xs text-gray-600">${notif.time}</div>
                    </div>
                    ${notif.unread ? '<div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>' : ''}
                </div>
            `).join('');
    updateBadge();
}

function markAsRead(id) {
    const notif = notifications.find(n => n.id === id);
    if (notif) notif.unread = false;
    renderNotifications();
}

function updateBadge() {
    const count = notifications.filter(n => n.unread).length;
    const badge = document.getElementById('badge');
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// Fermer le panneau si on clique ailleurs
document.addEventListener('click', (e) => {
    const panel = document.getElementById('notifPanel');
    const btn = e.target.closest('button');
    if (!panel.contains(e.target) && !btn) {
        panel.classList.add('hidden');
        panel.classList.remove('flex');
    }
});

window.addEventListener('load', () => {

    renderNotifications();
       
});

