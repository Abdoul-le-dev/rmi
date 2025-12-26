<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau de Notifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .navbar {
            background: #fff;
            padding: 12px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .notif-btn {
            background: #e4e6eb;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .notif-btn:hover {
            background: #d8dadf;
        }

        .notif-icon {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #e41e3f;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
        }

        .notif-panel {
            position: absolute;
            top: 60px;
            right: 20px;
            width: 360px;
            max-height: 500px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }

        .notif-panel.active {
            display: flex;
        }

        .notif-header {
            padding: 16px;
            border-bottom: 1px solid #e4e6eb;
        }

        .notif-header h3 {
            font-size: 24px;
            font-weight: 700;
        }

        .notif-list {
            overflow-y: auto;
            max-height: 400px;
        }

        .notif-list::-webkit-scrollbar {
            width: 8px;
        }

        .notif-list::-webkit-scrollbar-track {
            background: #f0f2f5;
        }

        .notif-list::-webkit-scrollbar-thumb {
            background: #bcc0c4;
            border-radius: 4px;
        }

        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid #e4e6eb;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            gap: 12px;
        }

        .notif-item:hover {
            background: #f2f3f5;
        }

        .notif-item.unread {
            background: #e7f3ff;
        }

        .notif-item.unread:hover {
            background: #d8ebff;
        }

        .notif-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #1877f2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .notif-content {
            flex: 1;
        }

        .notif-text {
            font-size: 14px;
            line-height: 1.4;
            color: #050505;
            margin-bottom: 4px;
        }

        .notif-time {
            font-size: 12px;
            color: #65676b;
        }

        .notif-dot {
            width: 8px;
            height: 8px;
            background: #1877f2;
            border-radius: 50%;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Mon Site</h2>
        <button class="notif-btn" onclick="toggleNotif()">
            <svg class="notif-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
            </svg>
            <span class="badge" id="badge">5</span>
        </button>
    </div>

    <div class="notif-panel" id="notifPanel">
        <div class="notif-header">
            <h3>Notifications</h3>
        </div>
        <div class="notif-list" id="notifList"></div>
    </div>

    <script>
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
            panel.classList.toggle('active');
        }

        function renderNotifications() {
            const list = document.getElementById('notifList');
            list.innerHTML = notifications.map(notif => `
                <div class="notif-item ${notif.unread ? 'unread' : ''}" onclick="markAsRead(${notif.id})">
                    <div class="notif-avatar">${notif.user}</div>
                    <div class="notif-content">
                        <div class="notif-text">${notif.text}</div>
                        <div class="notif-time">${notif.time}</div>
                    </div>
                    ${notif.unread ? '<div class="notif-dot"></div>' : ''}
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
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }

        // Fermer le panneau si on clique ailleurs
        document.addEventListener('click', (e) => {
            const panel = document.getElementById('notifPanel');
            const btn = document.querySelector('.notif-btn');
            if (!panel.contains(e.target) && !btn.contains(e.target)) {
                panel.classList.remove('active');
            }
        });

        renderNotifications();
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau de Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">

    <div class="bg-white p-3 shadow-md flex justify-between items-center relative">
        <h2 class="text-xl font-semibold">Mon Site</h2>
        <button class="bg-gray-200 hover:bg-gray-300 w-10 h-10 rounded-full relative flex items-center justify-center transition" onclick="toggleNotif()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full px-1.5 py-0.5 text-xs font-bold" id="badge">5</span>
        </button>
    </div>

    <div class="hidden absolute top-16 right-5 w-96 max-h-[500px] bg-white rounded-lg shadow-xl flex-col z-50" id="notifPanel">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-2xl font-bold">Notifications</h3>
        </div>
        <div class="overflow-y-auto max-h-96 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100" id="notifList"></div>
    </div>

    <script>
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

        renderNotifications();
    </script>

</body>
</html>