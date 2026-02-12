<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous approuvé</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin-top: 0;
            color: #667eea;
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
            <div class="icon">✅</div>
            <h1>Votre rendez-vous a été approuvé !</h1>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $appointment->full_name }}</strong>,</p>
            
            <p>Excellente nouvelle ! Votre demande de rendez-vous a été approuvée par notre équipe.</p>
            
            <div class="info-box">
                <h3>📅 Détails du rendez-vous</h3>
                <p><strong>Sujet :</strong> {{ $appointment->subject }}</p>
                <p><strong>Date et heure :</strong> {{ $appointment->formatted_date }}</p>
                <p><strong>Durée :</strong> {{ $appointment->duration_minutes }} minutes</p>
                @if ($appointment->instructor)
                    <p><strong>Instructeur :</strong> {{ $appointment->instructor->full_name }}</p>
                @endif
                
            </div>
            
            @if($appointment->admin_notes)
            <div class="info-box">
                <h3>💬 Note de l'équipe</h3>
                <p>{{ $appointment->admin_notes }}</p>
            </div>
            @endif
            
            
            
            <div class="info-box">
                <h3>⚠️ Important</h3>
                <ul>
                    <li>Vous recevrez un rappel avec le lien de la visioconférence par email 30 minutes avant le début du rendez-vous</li>
                    <li>Assurez-vous d'avoir une connexion internet stable</li>
                    <li>Testez votre microphone et votre caméra avant de rejoindre</li>
                    <li>Préparez vos questions à l'avance pour optimiser le temps</li>
                </ul>
            </div>
            
            <p>Nous avons hâte de vous retrouver !</p>
            
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