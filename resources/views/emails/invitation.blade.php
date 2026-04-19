<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; }
        .header { background-color: #1a365d; color: #ffffff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 0.8rem; color: #718096; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #d4af37; color: #1a365d; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invitation à une réunion</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $participant->name }},</p>
            <p>Vous avez été invité à participer à la réunion suivante à la faculté :</p>
            <h2 style="color: #1a365d;">{{ $reunion->titre }}</h2>
            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($reunion->date)->format('d/m/Y') }}</p>
            <p><strong>Lieu :</strong> {{ $reunion->lieu ?? 'Non spécifié' }}</p>
            
            <p>Merci de vous connecter pour confirmer votre présence.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/reunions/' . $reunion->id) }}" class="btn">Voir les détails</a>
            </div>
        </div>
        <div class="footer">
            Ceci est un message automatique de l'Université Event Manager.
        </div>
    </div>
</body>
</html>
