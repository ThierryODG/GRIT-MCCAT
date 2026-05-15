<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $rapport_titre }}</title>
    <style>
        @page {
            margin: 0cm;
            size: A4 portrait;
        }

        body {
            margin-top: 3.5cm;
            margin-bottom: 2.5cm;
            margin-left: 2cm;
            margin-right: 2cm;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }

        /* HEADER */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3cm;
            background: white;
            border-bottom: 3px solid #b45309;
            padding: 0.5cm 2cm;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-cell {
            vertical-align: top;
            text-align: center;
            font-size: 8pt;
        }

        .ministry {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
        }

        .country {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .motto {
            font-style: italic;
            font-size: 7.5pt;
            margin-top: 2px;
        }

        .sep {
            margin: 2px 0;
            font-weight: bold;
            color: #000;
        }

        /* FOOTER */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2cm;
            background: white;
            border-top: 1px solid #ccc;
            padding: 0.5cm 2cm;
            text-align: center;
            font-size: 8pt;
            color: #555;
        }

        /* COVER PAGE */
        .cover-page {
            text-align: center;
            padding-top: 5cm;
            page-break-after: always;
        }

        .cover-title {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #b45309;
            margin-bottom: 0.5cm;
            border-top: 4px double #000;
            border-bottom: 4px double #000;
            padding: 20px 0;
        }

        .cover-subtitle {
            font-size: 14pt;
            margin-bottom: 2cm;
            font-weight: bold;
            color: #333;
        }

        .cover-box {
            border: 2px solid #b45309;
            padding: 20px;
            background: #fffbf0;
            margin: 0 1cm;
            text-align: left;
        }

        .cover-label {
            font-weight: bold;
            color: #b45309;
            width: 140px;
            display: inline-block;
        }

        /* GENERAL */
        h1 {
            font-size: 16pt;
            text-transform: uppercase;
            color: #b45309;
            border-bottom: 2px solid #b45309;
            padding-bottom: 5px;
            margin-top: 1cm;
            margin-bottom: 0.5cm;
            page-break-after: avoid;
        }

        h2 {
            font-size: 13pt;
            color: #1e293b;
            margin-top: 0.8cm;
            margin-bottom: 0.3cm;
            border-left: 5px solid #b45309;
            padding-left: 10px;
            page-break-after: avoid;
        }

        h3 {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 0.5cm;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px 8px;
            vertical-align: top;
        }

        th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: left;
            color: #0f172a;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        .bg-green {
            background-color: #10b981;
        }

        .bg-red {
            background-color: #ef4444;
        }

        .bg-blue {
            background-color: #3b82f6;
        }

        .bg-amber {
            background-color: #f59e0b;
            color: #000;
        }

        .bg-gray {
            background-color: #94a3b8;
        }

        .stat-card {
            display: inline-block;
            width: 45%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #fafafa;
            vertical-align: top;
        }

        .stat-val {
            font-size: 18pt;
            font-weight: bold;
            color: #b45309;
        }

        .stat-label {
            font-size: 9pt;
            text-transform: uppercase;
            color: #555;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* ITEM BLOCK */
        .item-block {
            border: 1px solid #ccc;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .item-header {
            background: #f8fafc;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .item-body {
            padding: 8px;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header>
        <table class="header-table">
            <tr>
                <td style="width: 35%; text-align: left; border: none;">
                    <div class="ministry">Ministère de la Communication,<br>de la Culture, des Arts<br>et du Tourisme
                    </div>
                    <div class="sep">-----</div>
                    <div style="font-size: 7pt;">CABINET DU MINISTRE</div>
                </td>
                <td style="width: 30%; border: none;">
                    @if(isset($logo_path) && file_exists($logo_path))
                        <img src="{{ $logo_path }}" style="height: 60px;">
                    @else
                        <div style="font-weight:bold; border:2px solid #000; padding:5px;">ARMOIRIES</div>
                    @endif
                </td>
                <td style="width: 35%; text-align: right; border: none;">
                    <div class="country">Burkina Faso</div>
                    <div class="sep">-----</div>
                    <div class="motto">La Patrie ou la Mort, nous vaincrons</div>
                </td>
            </tr>
        </table>
    </header>

    <!-- FOOTER -->
    <footer>
        <p>Rapport généré le {{ \Carbon\Carbon::parse($date_generation)->format('d/m/Y') }} par {{ $auteur_generation }}
        </p>
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans", "bold");
                $pdf->page_text(270, 820, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
            }
        </script>
    </footer>

    <!-- COVER PAGE -->
    <div class="cover-page">
        <div style="font-size: 12pt; text-transform: uppercase; margin-bottom: 2cm; letter-spacing: 2px;">Confidentiel
        </div>
        <div class="cover-title">{{ $rapport_titre }}</div>
        <div class="cover-subtitle">
            Période du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au
            {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
        </div>

        <div class="cover-box">
            <p><span class="cover-label">Objet :</span> Synthèse stratégique de l'exécution des recommandations</p>
            <p><span class="cover-label">Destinataire :</span> M. le Ministre</p>
            <p><span class="cover-label">Date :</span> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            <p><span class="cover-label">Statut Global :</span>
                @if($taux_global >= 80)
                    <span class="badge bg-green">EXCELLENT ({{ $taux_global }}%)</span>
                @elseif($taux_global >= 50)
                    <span class="badge bg-amber">MOYEN ({{ $taux_global }}%)</span>
                @else
                    <span class="badge bg-red">CRITIQUE ({{ $taux_global }}%)</span>
                @endif
            </p>
        </div>
    </div>

    <!-- EXECUTIVE SUMMARY -->
    <div class="page-break"></div>
    <h1>I. Synthèse Exécutive</h1>

    <p>
        Ce rapport présente l'état de mise en œuvre des recommandations d'audit pour la période sélectionnée.
        Il met en évidence les performances des structures sous tutelle et identifie les points d'attention
        prioritaires.
    </p>

    <h2>1.1 Indicateurs Clés de Performance</h2>
    <div style="text-align: center; margin: 20px 0;">
        <div class="stat-card">
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-label">Recommandations Totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color: #10b981;">{{ $stats['cloturees'] }}</div>
            <div class="stat-label">Clôturées / Terminées</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color: #f59e0b;">{{ $stats['en_cours'] }}</div>
            <div class="stat-label">En Cours d'Exécution</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color: #ef4444;">{{ $stats['en_retard'] }}</div>
            <div class="stat-label">En Retard Critique</div>
        </div>
    </div>

    <h2>1.2 Répartition par Structure</h2>
    <table>
        <thead>
            <tr>
                <th>Structure / Service</th>
                <th class="text-center">Volume</th>
                <th class="text-center">Performance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parStructure as $structure => $count)
                <tr>
                    <td>{{ $structure }}</td>
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-center">
                        {{-- Calcul factice pour l'exemple si pas dispo, sinon injecter le vrai taux --}}
                        -
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- DETAILS SECTION -->
    <div class="page-break"></div>
    <h1>II. Détail des Recommandations Prioritaires</h1>
    <p class="text-gray-500 italic mb-4">Focus sur les recommandations critiques et en retard.</p>

    @foreach($recommandations as $rec)
        <div class="item-block">
            <div class="item-header">
                <span
                    class="badge {{ $rec->priorite === 'haute' ? 'bg-red' : ($rec->priorite === 'moyenne' ? 'bg-amber' : 'bg-green') }}">
                    {{ strtoupper($rec->priorite) }}
                </span>
                <span style="font-family: monospace; margin-left:10px;">{{ $rec->reference }}</span>
                <span style="float: right;">
                    @if($rec->statut === 'cloturee') <span class="badge bg-green">CLÔTURÉE</span>
                    @elseif($rec->estEnRetard()) <span class="badge bg-red">RETARD</span>
                    @else <span class="badge bg-blue">{{ strtoupper(str_replace('_', ' ', $rec->statut)) }}</span>
                    @endif
                </span>
            </div>
            <div class="item-body">
                <div style="font-weight: bold; margin-bottom: 5px;">{{ $rec->titre }}</div>
                <div style="font-size: 9pt; color: #444; margin-bottom: 10px; font-style: italic;">
                    Structure : {{ $rec->structure->nom ?? 'N/A' }} | Échéance :
                    {{ $rec->date_limite ? $rec->date_limite->format('d/m/Y') : 'Non définie' }}
                </div>
                <div style="font-size: 9pt; text-align: justify;">
                    {{ Str::limit($rec->description, 300) }}
                </div>
                <div style="margin-top: 8px; border-top: 1px dashed #eee; padding-top: 5px; font-size: 8.5pt;">
                    <strong>Avancement :</strong> {{ $rec->taux_avancement ?? 0 }}%
                    @if($rec->plansAction->count() > 0)
                        |
                        {{ $rec->plansAction->where('statut_execution', 'termine')->count() }}/{{ $rec->plansAction->count() }}
                        actions terminées
                    @endif
                </div>
            </div>
        </div>
    @endforeach

</body>

</html>