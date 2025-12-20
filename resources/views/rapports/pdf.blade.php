<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $titre }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .country-header {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .motto {
            font-style: italic;
            font-size: 10px;
        }
        .ministry {
            margin-top: 10px;
            font-weight: bold;
            font-size: 12px;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 30px 0;
            color: #1a56db;
        }
        .meta-info {
            margin-bottom: 30px;
        }
        .meta-item {
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #444;
        }
        .content {
            text-align: justify;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="country-header">Burkina Faso</div>
        <div class="motto">Unité - Progrès - Justice</div>
        <div class="ministry">Ministère de la Culture, des Arts et du Tourisme</div>
        <!-- Logo placeholder if available -->
        <!-- <img src="{{ public_path('images/logo-mccat-300x300.jpg') }}" class="logo"> -->
    </div>

    <div class="report-title">
        {{ $titre }}
    </div>

    <div class="meta-info">
        <div class="meta-item"><strong>Date :</strong> {{ $date }}</div>
        <div class="meta-item"><strong>Généré par :</strong> {{ $user->name }} ({{ $user->role }})</div>
        <div class="meta-item"><strong>Type :</strong> {{ ucfirst($type) }}</div>
    </div>

    @if(isset($recommandation))
        <div class="section">
            <div class="section-title">Détails de la Recommandation</div>
            <div class="content">
                <p><strong>Référence :</strong> #{{ $recommandation->id }}</p>
                <p><strong>Intitulé :</strong> {{ $recommandation->recommandation }}</p>
                <p><strong>Statut actuel :</strong> {{ ucfirst(str_replace('_', ' ', $recommandation->statut)) }}</p>
                <p><strong>Date d'échéance :</strong> {{ $recommandation->date_echeance ? \Carbon\Carbon::parse($recommandation->date_echeance)->format('d/m/Y') : 'Non définie' }}</p>
            </div>
        </div>

        @if($recommandation->plansAction->count() > 0)
            <div class="section">
                <div class="section-title">État d'avancement des actions</div>
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Responsable</th>
                            <th>Échéance</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recommandation->plansAction as $action)
                            <tr>
                                <td>{{ $action->libelle }}</td>
                                <td>{{ $action->responsable }}</td>
                                <td>{{ $action->date_echeance ? \Carbon\Carbon::parse($action->date_echeance)->format('d/m/Y') : '-' }}</td>
                                <td>{{ ucfirst($action->statut) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    @if(isset($recommandations))
        <div class="section">
            <div class="section-title">Liste des Recommandations</div>
            <table>
                <thead>
                    <tr>
                        <th>Réf</th>
                        <th>Recommandation</th>
                        <th>Statut</th>
                        <th>Échéance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recommandations as $rec)
                        <tr>
                            <td>#{{ $rec->id }}</td>
                            <td>{{ Str::limit($rec->recommandation, 50) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $rec->statut)) }}</td>
                            <td>{{ $rec->date_echeance ? \Carbon\Carbon::parse($rec->date_echeance)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($description)
        <div class="section">
            <div class="section-title">Observations / Description</div>
            <div class="content">
                <p>{{ nl2br(e($description)) }}</p>
            </div>
        </div>
    @endif

    <div class="footer">
        Document généré automatiquement par la plateforme GRIT - MCCAT
    </div>
</body>
</html>
