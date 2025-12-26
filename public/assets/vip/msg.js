const conversations = [
    { name: 'HOUESSOU Amour', message: 'Neque porro quisquam est qui dolorem ipsum quia...', time: '4:30 PM', avatar: 'HA', unread: false, active: true },
    { name: 'AGLA Marinette', message: 'Vidéo', time: '4:30 PM', avatar: 'AM', unread: true, active: false, badge: true },
    { name: 'ZOSSOU Lionnel', message: 'Neque porro quisquam est qui dolorem ipsu...', time: '4:30 PM', avatar: 'ZL', unread: false, active: false },
    { name: 'Tranquillin AKOTEGNON', message: 'Audio', time: '4:30 PM', avatar: 'TA', unread: false, active: false },
    { name: 'Fadel ABOUDOU', message: 'Félicitations pour la combustion', time: '4:30 PM', avatar: 'FA', unread: false, active: false },
    { name: 'Micheal Scott', message: 'Voice call', time: '4:30 PM', avatar: 'MS', unread: false, active: false },
    { name: 'Geoffroy AHIDOMONHAN', message: 'Sticker', time: '4:30 PM', avatar: 'GA', unread: false, active: false },
    { name: 'SOSSA Gustave', message: 'Ce message a été supprimé', time: '4:30 PM', avatar: 'SG', unread: false, active: false },
    { name: 'HOUNDJI Gilbert', message: 'GIF', time: '4:30 PM', avatar: 'HG', unread: false, active: false },
    { name: 'SOSSOU Judith', message: 'Document de collaboration', time: '4:30 PM', avatar: 'SJ', unread: false, active: false },
    { name: 'MENSAH Jules', message: 'Localisation en temps réel', time: '4:30 PM', avatar: 'MJ', unread: false, active: false },
    { name: 'Linda AHOSSOU', message: 'Appel Vidéo', time: '4:30 PM', avatar: 'LA', unread: false, active: false },
    { name: 'Emily ZEDEMIN', message: 'Document', time: '4:30 PM', avatar: 'EZ', unread: false, active: false },
    { name: 'Ghislain AHOTON', message: 'Contact', time: '4:30 PM', avatar: 'GA', unread: false, active: false }
];

function renderConversations() {
    const list = document.getElementById('conversationList');
    list.innerHTML = conversations.map(conv => `
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer ${conv.active ? 'bg-gray-50' : ''}">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            ${conv.avatar}
                        </div>
                        ${conv.badge ? '<div class="absolute -top-1 -right-1 w-5 h-5 bg-blue-600 text-white rounded-full text-xs flex items-center justify-center">1</div>' : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="font-semibold text-sm truncate">${conv.name}</h3>
                            <span class="text-xs text-gray-500 ml-2">${conv.time}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">${conv.message}</p>
                    </div>
                </div>
            `).join('');
}

renderConversations();