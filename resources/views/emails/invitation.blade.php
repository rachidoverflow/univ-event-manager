<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation à une réunion</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #0f172a;
        }
        .intro {
            margin-bottom: 32px;
            color: #475569;
        }
        .meeting-box {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            border-left: 4px solid #4f46e5;
        }
        .meeting-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
        }
        .meeting-info {
            margin: 8px 0;
            display: flex;
            align-items: center;
            color: #334155;
            font-size: 15px;
        }
        .label {
            font-weight: 600;
            color: #64748b;
            width: 80px;
            display: inline-block;
        }
        .button-container {
            text-align: center;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .faculte {
            font-weight: 700;
            color: #475569;
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Convocation de Réunion</h1>
            </div>
            <div class="content">
                <div class="greeting">Bonjour {{ $participant->name }},</div>
                <p class="intro">Vous êtes convié(e) à participer à la réunion académique suivante au sein de notre établissement :</p>
                
                <div class="meeting-box">
                    <h2 class="meeting-title">{{ $reunion->titre }}</h2>
                    <div class="meeting-info">
                        <span class="label">Date :</span> 
                        <strong>{{ \Carbon\Carbon::parse($reunion->date)->format('d/m/Y à H:i') }}</strong>
                    </div>
                    <div class="meeting-info">
                        <span class="label">Lieu :</span> 
                        <strong>{{ $reunion->lieu ?? 'Non précisé' }}</strong>
                    </div>
                    @if($reunion->instance)
                    <div class="meeting-info">
                        <span class="label">Instance :</span> 
                        <strong>{{ $reunion->instance->nom }}</strong>
                    </div>
                    @endif
                </div>
                
                <p style="color: #475569; margin-bottom: 30px;">Nous vous prions de bien vouloir confirmer votre présence via votre espace personnel en cliquant sur le bouton ci-dessous :</p>
                
                <div class="button-container">
                    <a href="{{ url('/reunions/' . $reunion->id) }}" class="btn">Consulter et Répondre</a>
                </div>
            </div>
            <div class="footer">
                <span class="faculte">Faculté Polydisciplinaire de Sidi Bennour</span>
                Ceci est une notification automatique générée par Univ-Event Manager.<br>
                Merci de ne pas répondre à cet email.
            </div>
        </div>
    </div>
</body>
</html>
