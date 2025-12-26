const conversations = [
            { name: 'HOUESSOU Amour', message: 'Neque porro quisquam est qui dolorem ipsum quia...', time: '4:30 PM', avatar: 'HOUESSOU Amour', unread: 0, active: true, online: true },
            { name: 'AGLA Marinette', message: '📹 Vidéo', time: '4:30 PM', avatar: 'AGLA Marinette', unread: 1, active: false, online: true },
            { name: 'ZOSSOU Lionnel', message: 'Neque porro quisquam est qui dolorem ipsu...', time: '4:30 PM', avatar: 'ZOSSOU Lionnel', unread: 0, active: false, online: false },
            { name: 'Tranquillin AKOTEGNON', message: '🎵 Audio', time: '4:30 PM', avatar: 'Tranquillin AKOTEGNON', unread: 0, active: false, online: true },
            { name: 'Fadel ABOUDOU', message: '🎉 Félicitations pour la combustion', time: '4:30 PM', avatar: 'Fadel ABOUDOU', unread: 0, active: false, online: false },
            { name: 'Micheal Scott', message: '📞 Voice call', time: '4:30 PM', avatar: 'Micheal Scott', unread: 0, active: false, online: true },
            { name: 'Geoffroy AHIDOMONHAN', message: '✨ Sticker', time: '4:30 PM', avatar: 'Geoffroy AHIDOMONHAN', unread: 0, active: false, online: false },
            { name: 'SOSSA Gustave', message: 'Ce message a été supprimé', time: '4:30 PM', avatar: 'SOSSA Gustave', unread: 0, active: false, online: false },
            { name: 'HOUNDJI Gilbert', message: '🎬 GIF', time: '4:30 PM', avatar: 'HOUNDJI Gilbert', unread: 0, active: false, online: true },
            { name: 'SOSSOU Judith', message: '📄 Document de collaboration', time: '4:30 PM', avatar: 'SOSSOU Judith', unread: 0, active: false, online: false },
            { name: 'MENSAH Jules', message: '📍 Localisation en temps réel', time: '4:30 PM', avatar: 'MENSAH Jules', unread: 0, active: false, online: true },
            { name: 'Linda AHOSSOU', message: '📹 Appel Vidéo', time: '4:30 PM', avatar: 'Linda AHOSSOU', unread: 0, active: false, online: false },
            { name: 'Emily ZEDEMIN', message: '📎 Document', time: '4:30 PM', avatar: 'Emily ZEDEMIN', unread: 0, active: false, online: true },
            { name: 'Ghislain AHOTON', message: '👤 Contact', time: '4:30 PM', avatar: 'Ghislain AHOTON', unread: 0, active: false, online: false }
        ];

        function renderConversations() {
            const list = document.getElementById('conversationList');
            list.innerHTML = conversations.map((conv, index) => `
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition ${conv.active ? 'bg-indigo-50' : ''}" 
                    onclick="openChat(${index})">
                    <div class="relative flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(conv.avatar)}&background=random&size=56" 
                            class="w-14 h-14 rounded-full">
                        ${conv.online ? '<span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>' : ''}
                        ${conv.unread > 0 ? `<span class="absolute -top-1 -right-1 w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-bold">${conv.unread}</span>` : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h3 class="font-semibold text-sm truncate ${conv.unread > 0 ? 'text-gray-900' : 'text-gray-700'}">${conv.name}</h3>
                            <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${conv.time}</span>
                        </div>
                        <p class="text-xs ${conv.unread > 0 ? 'text-gray-700 font-medium' : 'text-gray-500'} truncate">${conv.message}</p>
                    </div>
                </div>
            `).join('');
        }

        function openChat(index) {
            // Mobile: masquer la liste et afficher le chat
            if (window.innerWidth < 768) {
                document.getElementById('sidebarConv').classList.add('mobile-hidden');
                document.getElementById('chatZone').classList.remove('mobile-hidden');
            }
            
            conversations.forEach((c, i) => c.active = i === index);
            renderConversations();
        }

        function backToList() {
            document.getElementById('sidebarConv').classList.remove('mobile-hidden');
            document.getElementById('chatZone').classList.add('mobile-hidden');
        }

        renderConversations();

        // Auto-scroll au bas des messages
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;