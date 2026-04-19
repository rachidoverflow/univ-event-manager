<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PV - {{ $reunion->titre }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', Times, serif; color: #000; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #000; padding-bottom: 1rem; }
        .univ-name { font-size: 1.2rem; font-weight: bold; text-transform: uppercase; }
        .fac-name { font-size: 1rem; margin-top: 0.5rem; }
        .pv-title { font-size: 1.5rem; font-weight: bold; margin: 2rem 0; text-align: center; text-decoration: underline; }
        
        .meta-info { margin-bottom: 2rem; }
        .meta-row { display: flex; margin-bottom: 0.5rem; }
        .meta-label { font-weight: bold; width: 150px; }
        
        .section-title { font-size: 1.2rem; font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid #ccc; }
        
        .agenda-point { margin-bottom: 1.5rem; }
        .point-title { font-weight: bold; }
        .point-decision { margin-top: 0.5rem; padding-left: 1rem; border-left: 3px solid #eee; font-style: italic; }
        
        .participants-list { display: flex; flex-wrap: wrap; gap: 1rem; }
        .participant-name { font-size: 0.9rem; }

        .footer { margin-top: 5rem; display: flex; justify-content: space-between; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #000; margin-top: 3rem; }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 0; left: 0; right: 0; background: #f8f9fa; padding: 1rem; border-bottom: 1px solid #ddd; text-align: center;">
        <button onclick="window.print()" style="padding: 0.5rem 2rem; cursor: pointer; background: #000; color: #fff; border: none; border-radius: 4px;">Imprimer le PV / Sauvegarder en PDF</button>
        <button onclick="window.history.back()" style="padding: 0.5rem 2rem; cursor: pointer; margin-left: 1rem;">Retour</button>
    </div>

    <div style="margin-top: 60px;">
        <div class="header">
            <div class="univ-name">Université Chouaïb Doukkali</div>
            <div class="fac-name">Faculté des Sciences et Techniques - El Jadida</div>
            @if($reunion->instance)
                <div style="margin-top: 0.5rem; font-weight: bold;">{{ $reunion->instance->nom }}</div>
            @endif
        </div>

        <div class="pv-title">Procès-Verbal de la Réunion</div>

        <div class="meta-info">
            <div class="meta-row">
                <div class="meta-label">Objet :</div>
                <div>{{ $reunion->titre }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Date :</div>
                <div>{{ $reunion->date }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Lieu :</div>
                <div>{{ $reunion->lieu }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Type :</div>
                <div>{{ $reunion->type == 'standard' ? 'Standard' : 'Élargie' }}</div>
            </div>
        </div>

        <div class="section-title">Présences</div>
        <div class="participants-list">
            @foreach($reunion->participants as $participant)
                <div class="participant-name">• {{ $participant->name }} ({{ $participant->role }})</div>
            @endforeach
        </div>

        <div class="section-title">Ordre du Jour et Décisions</div>
        @foreach($reunion->agendas as $agenda)
            <div class="agenda-point">
                <div class="point-title">{{ $loop->iteration }}. {{ $agenda->titre }}</div>
                @if($agenda->description)
                    <div style="font-size: 0.9rem; color: #444;">{{ $agenda->description }}</div>
                @endif
                <div class="point-decision">
                    <strong>Décision :</strong><br>
                    {!! nl2br(e($agenda->decision ?? 'Aucune décision saisie.')) !!}
                </div>
            </div>
        @endforeach

        <div class="footer">
            <div class="signature-box">
                <p>Le Président / Responsable</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Le Secrétaire de séance</p>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
</body>
</html>
