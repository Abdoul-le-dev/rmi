<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .alert-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .alert-box .time {
            font-size: 36px;
            font-weight: bold;
            color: #ff6b6b;
            margin: 10px 0;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #ff6b6b;
            padding: 15px;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 18px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .checklist {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }

        .checklist ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .checklist li {
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="icon">⏰</div>
            <h1>Rappel : Votre rendez-vous approche !</h1>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $appointment->full_name }}</strong>,</p>

            <div class="alert-box">
                <p style="margin: 0; font-size: 18px;">Votre rendez-vous commence dans</p>
                <div class="time">30 minutes</div>
                <p style="margin: 0;">⏰ {{ $appointment->formatted_date }}</p>
            </div>

            <p style="text-align: center; font-size: 18px; color: #ff6b6b; font-weight: bold;">
                Il est temps de vous préparer !
            </p>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #ff6b6b;">📋 Rappel des détails</h3>
                <p><strong>Sujet :</strong> {{ $appointment->subject }}</p>
                <p><strong>Durée :</strong> {{ $appointment->duration_minutes }} minutes</p>
                <p><strong>Instructeur :</strong> {{ $appointment->instructor->name }}</p>
            </div>

            <div class="checklist">
                <h3 style="margin-top: 0; color: #2196F3;">✓ Dernières vérifications</h3>
                <ul>
                    <li>✅ Vérifiez votre connexion internet</li>
                    <li>✅ Testez votre microphone et caméra</li>
                    <li>✅ Préparez vos questions et documents</li>
                    <li>✅ Installez-vous dans un endroit calme</li>
                    <li>✅ Ayez de quoi prendre des notes</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $appointment->participant_meeting_url }}" class="button">
                    🎥 REJOINDRE MAINTENANT
                </a>
            </div>

            <p style="text-align: center; font-size: 14px; color: #666;">
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
            </p>

            <p style="text-align: center; word-break: break-all;">
                <a href="{{ $appointment->participant_meeting_url }}" style="color: #1a73e8;">
                    {{ $appointment->participant_meeting_url }}
                </a>
            </p>


            <div
                style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #155724;">
                    <strong>💡 Conseil :</strong> Nous vous recommandons de rejoindre la salle 5 minutes en avance pour
                    vous assurer que tout fonctionne correctement.
                </p>
            </div>

            <p>À tout de suite !</p>

            <p>Cordialement,<br><strong>L'équipe RMI Class</strong></p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} RMI Class - Royal Markets Invest</p>
            <p>Afrique, Bénin, Cotonou, Q Sedjro St Michel, C557</p>
            <p><a href="mailto:support@rmiclass.net">support@rmiclass.net</a> | +229 99009193</p>
        </div>
    </div>
</body>

</html>
