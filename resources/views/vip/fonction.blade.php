<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .notifications-container {
            max-width: 540px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .notification {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification:hover {
            background-color: #f8f8f8;
        }

        .notification.featured {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            margin-bottom: 16px;
            position: relative;
        }

        .notification.featured::after {
            content: '›';
            position: absolute;
            right: 16px;
            font-size: 24px;
            font-weight: 300;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .notification.featured .avatar {
            background: rgba(255, 255, 255, 0.3);
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification.featured .notification-text {
            color: white;
        }

        .notification-text .name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .notification.featured .notification-text .name {
            color: white;
        }

        .notification-text .action {
            color: #666;
            font-weight: 400;
        }

        .notification.featured .notification-text .action {
            color: rgba(255, 255, 255, 0.95);
        }

        .notification-time {
            font-size: 12px;
            color: #999;
        }

        .notification.featured .notification-time {
            color: rgba(255, 255, 255, 0.8);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 12px;
        }

        .long-text {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    

    <script>
        // Ajouter l'interactivité pour les notifications
        document.querySelectorAll('.notification').forEach(notification => {
            notification.addEventListener('click', function() {
                console.log('Notification cliquée');
                // Vous pouvez ajouter votre logique ici
            });
        });

        // Animation au chargement
        window.addEventListener('load', () => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach((notif, index) => {
                notif.style.opacity = '0';
                notif.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    notif.style.transition = 'opacity 0.3s, transform 0.3s';
                    notif.style.opacity = '1';
                    notif.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>
</html>