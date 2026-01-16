<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Certificat de Réussite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('{{ public_path('certificat/fonts/Poppins-Regular.ttf') }}') format('truetype');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('{{ public_path('certificat/fonts/Poppins-ExtraBold.ttf') }}') format('truetype');
            font-weight: 900;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins';
        }

        /* .certificate-container {
            position: relative;
            width: 297mm;
            height: 210mm;
            background-image: url('{{ public_path('certificat/pdf_tem.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
        } */

        .certificate-container {
            width: 297mm;
            height: 210mm;
            background-image: url('{{ public_path('certificat/pdf_tem.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
        }

        .name {
            position: absolute;
            top: 52%;
            left: 8%;
            width: 100%;
            transform: translateY(-50%);
            text-align: center;
            font-size: 34pt;
            font-weight: 800;
            color: #4441C5;
            letter-spacing: 2px;
        }

        .date {
            position: absolute;
            bottom: 17mm;
            left: 30%;
            transform: translateX(-50%);
            font-size: 20pt;
            font-weight: 600;
            color: #4441C5;
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="name">
            {{ $user->full_name }}
        </div>

        <div class="date">
            {{ \Carbon\Carbon::createFromTimestamp($userCertificate->created_at)->format('d/m/Y') }}
        </div>
    </div>

</body>

</html>
