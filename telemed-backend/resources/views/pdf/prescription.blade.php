<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ordonnance - TeleMed</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .header { border-bottom: 2px solid #1E3A8A; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { color: #1E3A8A; font-size: 24px; font-weight: bold; }
        .row { width: 100%; display: table; }
        .col { display: table-cell; width: 50%; }
        .text-right { text-align: right; }
        .doc-info { font-weight: bold; font-size: 16px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #777; }
        .signature { margin-top: 30px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header row">
        <div class="col logo">TeleMed</div>
        <div class="col text-right">Date: {{ $prescription->issued_at->format('d/m/Y') }}</div>
    </div>

    <div class="row" style="margin-bottom: 30px;">
        <div class="col">
            <div class="doc-info">{{ $prescription->doctor->name }}</div>
            <div>Spécialité: {{ $prescription->doctor->doctorProfile->specialty ?? 'Généraliste' }}</div>
            <div>{{ $prescription->doctor->email }}</div>
        </div>
        <div class="col text-right">
            <div><strong>Patient:</strong> {{ $prescription->patient->name }}</div>
            @if($prescription->patient->patientProfile)
                <div>Age: {{ $prescription->patient->patientProfile->age }} ans</div>
            @endif
        </div>
    </div>

    <h3 style="text-transform: uppercase; text-align: center; color: #1E3A8A; margin-bottom: 20px;">Ordonnance Médicale</h3>

    @if(isset($prescription->medications) && count($prescription->medications) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Médicament</th>
                <th>Dosage</th>
                <th>Fréquence</th>
                <th>Durée</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->medications as $med)
            <tr>
                <td><strong>{{ $med['name'] ?? '' }}</strong></td>
                <td>{{ $med['dosage'] ?? '' }}</td>
                <td>{{ $med['frequency'] ?? '' }}</td>
                <td>{{ $med['duration'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($prescription->recommendations)
    <div style="margin-top: 30px;">
        <strong>Recommandations particulières:</strong>
        <p>{{ $prescription->recommendations }}</p>
    </div>
    @endif

    <div class="signature">
        <p>Signé électroniquement le {{ $prescription->issued_at->format('d/m/Y') }} par <strong>{{ $prescription->doctor->name }}</strong></p>
    </div>

    <div class="footer">
        <p>Cette ordonnance a été générée numériquement via la plateforme TeleMed.</p>
    </div>
</body>
</html>
