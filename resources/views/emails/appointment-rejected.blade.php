<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de rendez-vous</title>
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
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: #6c757d;
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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            margin: 20px 0;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">📧</div>
            <h1>Mise à jour sur votre demande</h1>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $appointment->full_name }}</strong>,</p>
            
            <p>Nous vous remercions pour votre demande de rendez-vous concernant "{{ $appointment->subject }}".</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #6c757d;">📅 Détails de votre demande</h3>
                <p><strong>Date souhaitée :</strong> {{ $appointment->formatted_date }}</p>
                <p><strong>Durée :</strong> {{ $appointment->duration_minutes }} minutes</p>
            </div>
            
            <p>Malheureusement, nous ne pouvons pas donner suite à votre demande pour le moment.</p>
            
            @if($appointment->admin_notes)
            <div class="reason-box">
                <h3 style="margin-top: 0; color: #856404;">💬 Raison</h3>
                <p>{{ $appointment->admin_notes }}</p>
            </div>
            @endif
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #667eea;">💡 Que faire ensuite ?</h3>
                <ul>
                    <li>Vous pouvez soumettre une nouvelle demande avec d'autres créneaux horaires</li>
                    <li>Consultez notre FAQ pour des réponses rapides : <a href="https://rmiclass.net/faq">FAQ RMI Class</a></li>
                    <li>Contactez notre support pour plus d'informations : <a href="mailto:support@rmiclass.net">support@rmiclass.net</a></li>
                    <li>Explorez nos formations en ligne disponibles immédiatement</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://rmiclass.net/rendez-vous" class="button">
                    📅 Faire une nouvelle demande
                </a>
            </div>
            
            <p>Nous restons à votre disposition et espérons pouvoir vous accompagner prochainement dans votre parcours de trading.</p>
            
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