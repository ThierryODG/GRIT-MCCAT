<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rapport d'Exécution - {{ $recommandation->reference }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin-top: 4cm;
            margin-bottom: 3cm;
            margin-left: 2.5cm;
            margin-right: 2.5cm;
            font-size: 11pt;
            color: #000000;
            line-height: 1.5;
            text-align: justify;
        }

        /* ========== EN-TÊTE OFFICIEL ========== */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5cm;
            padding: 0.5cm 2cm;
            background-color: white;
            border-bottom: 3px solid #009e49;
        }

        .header-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .header-grid td {
            vertical-align: top;
            text-align: center;
            font-size: 8pt;
            line-height: 1.2;
            padding: 0 5px;
        }

        .header-left { 
            width: 35%; 
            text-align: left; 
        }
        
        .header-center { 
            width: 30%; 
        }
        
        .header-right { 
            width: 35%; 
            text-align: right; 
        }

        .ministry-name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            margin-bottom: 2px;
        }

        .separator-line {
            margin: 3px 0;
            font-weight: bold;
            font-size: 9pt;
        }

        .country-name {
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
        }

        .motto {
            font-style: italic;
            font-size: 8pt;
            margin-top: 3px;
            color: #333;
        }

        .logo-container {
            height: 70px;
            text-align: center;
        }

        .logo-container img {
            max-height: 70px;
            width: auto;
        }

        /* ========== PIED DE PAGE ========== */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2.5cm;
            border-top: 2px solid #009e49;
            padding: 0.3cm 2cm;
            background-color: white;
            font-size: 9pt;
            text-align: center;
        }

        .footer-line {
            margin: 3px 0;
        }

        /* ========== PAGE DE GARDE ========== */
        .cover-page {
            page-break-after: always;
            text-align: center;
            padding-top: 6cm;
        }

        .cover-title {
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #009e49;
            margin-bottom: 1cm;
            letter-spacing: 1px;
            border-top: 3px double #000;
            border-bottom: 3px double #000;
            padding: 12px 0;
        }

        .cover-subtitle {
            font-size: 14pt;
            font-weight: bold;
            margin: 0.8cm 0;
            color: #333;
        }

        .cover-ref {
            font-size: 13pt;
            font-weight: bold;
            margin: 1cm 0;
            color: #ef3340;
        }

        .cover-info-box {
            border: 2px solid #009e49;
            padding: 15px;
            margin: 1.5cm 2cm;
            background-color: #f9f9f9;
            text-align: left;
        }

        .cover-info-line {
            font-size: 11pt;
            margin: 8px 0;
        }

        .cover-footer {
            position: absolute;
            bottom: 2.5cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10pt;
        }

        /* ========== TITRES ========== */
        h1 {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #009e49;
            margin-top: 1cm;
            margin-bottom: 0.6cm;
            border-bottom: 2px solid #009e49;
            padding-bottom: 5px;
            page-break-after: avoid;
        }

        h2 {
            font-size: 13pt;
            font-weight: bold;
            color: #333;
            margin-top: 0.8cm;
            margin-bottom: 0.4cm;
            page-break-after: avoid;
        }

        h3 {
            font-size: 11pt;
            font-weight: bold;
            color: #555;
            margin-top: 0.6cm;
            margin-bottom: 0.3cm;
            text-decoration: underline;
        }

        /* ========== TABLEAUX ========== */
        table.official-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.4cm 0;
            font-size: 10pt;
        }

        table.official-table th,
        table.official-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        table.official-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: left;
        }

        table.no-border {
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        table.no-border td {
            border: none;
            padding: 4px 8px;
        }

        /* ========== PARAGRAPHES ========== */
        .intro-text {
            text-align: justify;
            text-indent: 1.2cm;
            margin: 0.6cm 0;
            line-height: 1.6;
        }

        .section-text {
            text-align: justify;
            margin: 0.4cm 0;
            line-height: 1.5;
        }

        .emphasized {
            font-weight: bold;
            color: #000;
        }

        .italic-note {
            font-style: italic;
            font-size: 9pt;
            color: #555;
            margin: 0.2cm 0;
        }

        /* ========== BADGES ET STATUTS ========== */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 9pt;
            color: white;
            text-transform: uppercase;
        }

        .priority-haute { background-color: #ef3340; }
        .priority-moyenne { background-color: #fcd116; color: #000; }
        .priority-basse { background-color: #009e49; }
        .status-termine { background-color: #009e49; }
        .status-encours { background-color: #3b82f6; }
        .status-nondemarre { background-color: #6b7280; }
        .status-retard { background-color: #ef3340; }

        /* ========== BARRE DE PROGRESSION ========== */
        .progress-container {
            width: 100%;
            height: 16px;
            background-color: #e8e8e8;
            border: 1px solid #999;
            margin: 4px 0;
        }

        .progress-bar {
            height: 100%;
            background-color: #009e49;
            text-align: center;
            line-height: 16px;
            color: white;
            font-weight: bold;
            font-size: 9pt;
        }

        /* ========== BOÎTES ========== */
        .info-box {
            border: 1px solid #009e49;
            background-color: #f0f9f0;
            padding: 12px;
            margin: 0.4cm 0;
        }

        .warning-box {
            border: 1px solid #ef3340;
            background-color: #fef2f2;
            padding: 12px;
            margin: 0.4cm 0;
        }

        /* ========== ACTIONS ========== */
        .action-block {
            border: 2px solid #ccc;
            margin: 0.6cm 0;
            page-break-inside: avoid;
        }

        .action-header {
            background-color: #009e49;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11pt;
        }

        .action-body {
            padding: 12px;
            background-color: white;
        }

        .action-field {
            margin: 8px 0;
        }

        .action-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .action-value {
            margin-left: 15px;
            color: #000;
        }

        .preuves-section {
            background-color: #f9f9f9;
            border: 1px dashed #999;
            padding: 8px;
            margin-top: 8px;
        }

        .preuves-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 6px;
            text-decoration: underline;
        }

        .preuves-list {
            list-style-type: disc;
            margin-left: 20px;
            font-size: 9pt;
        }

        /* ========== SIGNATURES ========== */
        .signature-section {
            margin-top: 1.5cm;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border: none;
            margin-top: 0.8cm;
        }

        .signature-table td {
            width: 50%;
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-label {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0.8cm;
        }

        .signature-space {
            height: 1.5cm;
        }

        .signature-name {
            margin-top: 0.3cm;
            font-weight: bold;
        }

        /* ========== UTILITAIRES ========== */
        .page-break {
            page-break-after: always;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-underline { text-decoration: underline; }
        .uppercase { text-transform: uppercase; }

        ul {
            margin-left: 1.5cm;
            line-height: 1.7;
        }

        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <!-- ========== EN-TÊTE FIXE ========== -->
    <header>
        <table class="header-grid">
            <tr>
                <td class="header-left">
                    <div class="ministry-name">Ministère de la Communication,</div>
                    <div class="ministry-name">de la Culture, des Arts</div>
                    <div class="ministry-name">et du Tourisme</div>
                    <div class="separator-line">━━━━━━</div>
                    <div class="ministry-name">Secrétariat Général</div>
                    <div class="separator-line">━━━━━━</div>
                    <div style="font-size: 7pt; margin-top: 3px;">Inspection Technique des Services</div>
                </td>
                
                <td class="header-center">
                    <div class="logo-container">
                        @if(file_exists($logo_path))
                            <img src="{{ $logo_path }}" alt="Armoiries">
                        @else
                            <div style="width: 70px; height: 70px; border: 2px solid #009e49; margin: 0 auto; font-size: 7pt; padding-top: 25px; color: #009e49;">ARMOIRIES<br>BURKINA FASO</div>
                        @endif
                    </div>
                </td>
                
                <td class="header-right">
                    <div class="country-name">Burkina Faso</div>
                    <div class="separator-line">━━━━━━</div>
                    <div class="motto">La Patrie ou la Mort, nous vaincrons</div>
                    <div class="separator-line">━━━━━━</div>
                </td>
            </tr>
        </table>
    </header>

    <!-- ========== PIED DE PAGE FIXE ========== -->
    <footer>
        <div class="footer-line"><strong>MCCAT - Inspection Technique des Services</strong></div>
        <div class="footer-line">01 BP 514 Ouagadougou 01 - Tél: +226 25 32 47 75</div>
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 9;
                $font = $fontMetrics->getFont("DejaVu Sans");
                $width = $fontMetrics->get_text_width($text, $font, $size);
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 20;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </footer>

    <!-- ==================== PAGE DE GARDE ==================== -->
    <div class="cover-page">
        <div class="cover-title">
            Rapport d'Exécution<br>de Recommandation
        </div>

        <div class="cover-ref">
            Référence : {{ $recommandation->reference }}
        </div>

        <div class="cover-subtitle">
            {{ $recommandation->titre }}
        </div>

        <div class="cover-info-box">
            <div class="cover-info-line">
                <span class="text-bold">Structure auditée :</span> 
                {{ $recommandation->structure->nom ?? 'Non définie' }}
            </div>
            <div class="cover-info-line">
                <span class="text-bold">Date de création :</span> 
                {{ $recommandation->created_at->format('d/m/Y') }}
            </div>
            <div class="cover-info-line">
                <span class="text-bold">Priorité :</span>
                @php
                    $priorityClass = match ($recommandation->priorite) {
                        'haute' => 'priority-haute',
                        'moyenne' => 'priority-moyenne',
                        'basse' => 'priority-basse',
                        default => 'status-nondemarre'
                    };
                @endphp
                <span class="status-badge {{ $priorityClass }}">{{ strtoupper($recommandation->priorite) }}</span>
            </div>
            <div class="cover-info-line">
                <span class="text-bold">Statut global :</span> 
                {{ number_format($recommandation->taux_avancement ?? 0, 0) }}% d'avancement
            </div>
        </div>

        <div class="cover-footer">
            <div style="font-size: 12pt; margin-bottom: 10px;">
                <strong>{{ mb_strtoupper(\Carbon\Carbon::parse($date_generation)->locale('fr')->isoFormat('MMMM YYYY')) }}</strong>
            </div>
            <div style="font-size: 10pt; font-style: italic; color: #555;">
                Document généré par : {{ $auteur_generation }}
            </div>
        </div>
    </div>

    <!-- ==================== INTRODUCTION ==================== -->
    <h1>Introduction</h1>

    <p class="intro-text">
        Le présent rapport constitue le document officiel d'exécution relatif à la recommandation d'audit 
        référencée <span class="emphasized">{{ $recommandation->reference }}</span>, émise dans le cadre 
        des missions de l'Inspection Technique des Services (ITS) du Ministère de la Communication, 
        de la Culture, des Arts et du Tourisme.
    </p>

    <p class="section-text">
        Ce rapport détaille l'ensemble des actions entreprises par les responsables désignés pour répondre 
        aux constats formulés lors de l'audit. Il comprend :
    </p>

    <ul style="margin-left: 2cm; line-height: 1.8;">
        <li>Une fiche synthétique de la recommandation</li>
        <li>L'identification des acteurs et de leurs responsabilités</li>
        <li>Le détail du plan d'action avec les preuves d'exécution</li>
        <li>L'historique des échanges et validations</li>
        <li>Les conclusions et perspectives</li>
    </ul>

    <p class="section-text">
        Le suivi rigoureux de cette recommandation s'inscrit dans la démarche d'amélioration continue 
        de la gouvernance administrative et de l'efficacité des services publics du ministère.
    </p>

    <!-- ==================== I. FICHE SYNTHÉTIQUE ==================== -->
    <div class="page-break"></div>
    <h1>I. Fiche Synthétique de la Recommandation</h1>

    <h2>1.1. Informations générales</h2>

    <table class="official-table">
        <tr>
            <th>Référence</th>
            <td><strong>{{ $recommandation->reference }}</strong></td>
        </tr>
        <tr>
            <th>Date de création</th>
            <td>{{ $recommandation->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Niveau de priorité</th>
            <td>
                <span class="status-badge {{ $priorityClass }}">{{ strtoupper($recommandation->priorite) }}</span>
            </td>
        </tr>
        <tr>
            <th>Date limite d'exécution</th>
            <td>
                @if($recommandation->date_limite)
                    {{ $recommandation->date_limite->format('d/m/Y') }}
                    @if($recommandation->estEnRetard())
                        <span class="status-badge status-retard" style="margin-left: 10px;">EN RETARD</span>
                    @endif
                @else
                    Non définie
                @endif
            </td>
        </tr>
        <tr>
            <th>Structure concernée</th>
            <td>{{ $recommandation->structure->nom ?? 'Non définie' }}</td>
        </tr>
    </table>

    <h2>1.2. Taux d'avancement global</h2>

    <div style="margin: 0.5cm 0;">
        <div style="font-size: 14pt; font-weight: bold; margin-bottom: 10px;">
            Progression : {{ number_format($recommandation->taux_avancement ?? 0, 0) }}%
        </div>
        <div class="progress-container">
            <div class="progress-bar" style="width: {{ $recommandation->taux_avancement ?? 0 }}%;">
                {{ number_format($recommandation->taux_avancement ?? 0, 0) }}%
            </div>
        </div>
    </div>

    <h2>1.3. Constat et recommandation</h2>

    <div class="info-box">
        <h3 style="margin-top: 0;">Constat d'audit :</h3>
        <div class="section-text">
            {!! nl2br(e($recommandation->description)) !!}
        </div>
    </div>

    <!-- ==================== II. ACTEURS ET RESPONSABILITÉS ==================== -->
    <h1>II. Acteurs et Responsabilités</h1>

    <p class="section-text">
        La mise en œuvre de cette recommandation implique plusieurs acteurs institutionnels dont les rôles 
        et responsabilités sont définis ci-après :
    </p>

    <table class="official-table">
        <thead>
            <tr style="background-color: #d0d0d0;">
                <th style="width: 30%;">Fonction</th>
                <th style="width: 35%;">Identité</th>
                <th style="width: 35%;">Responsabilités</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Inspecteur Technique (ITS)</strong></td>
                <td>{{ $recommandation->its->name ?? 'Non assigné' }}</td>
                <td>Formulation de la recommandation et suivi initial</td>
            </tr>
            <tr>
                <td><strong>Inspecteur Général (IG)</strong></td>
                <td>{{ $recommandation->inspecteurGeneral->name ?? 'En attente de validation' }}</td>
                <td>Validation finale et autorisation de clôture</td>
            </tr>
            <tr>
                <td><strong>Responsable de Structure</strong></td>
                <td>{{ $recommandation->responsable->name ?? 'Non assigné' }}</td>
                <td>Validation du plan d'action et supervision</td>
            </tr>
            <tr>
                <td><strong>Point Focal</strong></td>
                <td>{{ $recommandation->pointFocal->name ?? 'Non assigné' }}</td>
                <td>Coordination opérationnelle et mise en œuvre</td>
            </tr>
        </tbody>
    </table>

    <!-- ==================== III. PLAN D'ACTION ET EXÉCUTION ==================== -->
    <div class="page-break"></div>
    <h1>III. Plan d'Action et État d'Exécution</h1>

    <p class="section-text">
        Le plan d'action élaboré pour répondre à cette recommandation comprend 
        <strong>{{ $recommandation->plansAction->count() }} action(s)</strong> distincte(s), 
        dont le détail et l'état d'avancement sont présentés ci-après.
    </p>

    @foreach($recommandation->plansAction as $index => $action)
        <div class="action-block">
            <div class="action-header">
                ACTION N° {{ $index + 1 }} / {{ $recommandation->plansAction->count() }}
                <span style="float: right;">
                    @php
                        $statusInfo = match ($action->statut_execution) {
                            'termine' => ['label' => 'TERMINÉ', 'class' => 'status-termine'],
                            'en_cours' => ['label' => 'EN COURS', 'class' => 'status-encours'],
                            default => ['label' => 'NON DÉMARRÉ', 'class' => 'status-nondemarre']
                        };
                    @endphp
                    <span class="status-badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                </span>
            </div>

            <div class="action-body">
                <div class="action-field">
                    <div class="action-label">Intitulé de l'action :</div>
                    <div class="action-value">{{ $action->action }}</div>
                </div>

                <table class="official-table" style="margin-top: 0.5cm;">
                    <tr>
                        <th>Responsable d'exécution</th>
                        <td>
                            @if($action->responsable_type === 'point_focal')
                                Point Focal : {{ $recommandation->pointFocal->name ?? 'Non assigné' }}
                            @elseif($action->executant_nom)
                                {{ $action->executant_nom }}
                                @if($action->executant_role)
                                    <br><span class="italic-note">({{ $action->executant_role }})</span>
                                @endif
                            @else
                                Non spécifié
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date de début prévue</th>
                        <td>{{ $action->date_debut_prevue ? \Carbon\Carbon::parse($action->date_debut_prevue)->format('d/m/Y') : 'Non définie' }}</td>
                    </tr>
                    <tr>
                        <th>Date de fin prévue</th>
                        <td>{{ $action->date_fin_prevue ? \Carbon\Carbon::parse($action->date_fin_prevue)->format('d/m/Y') : 'Non définie' }}</td>
                    </tr>
                    <tr>
                        <th>Taux de réalisation</th>
                        <td>
                            <strong>{{ $action->pourcentage_realisation ?? 0 }}%</strong>
                            <div class="progress-container" style="margin-top: 5px; height: 15px;">
                                <div class="progress-bar" style="width: {{ $action->pourcentage_realisation ?? 0 }}%; height: 15px; line-height: 15px; font-size: 9pt;">
                                    {{ $action->pourcentage_realisation ?? 0 }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="action-field" style="margin-top: 0.5cm;">
                    <div class="action-label">Commentaire sur l'avancement :</div>
                    <div class="info-box" style="margin-top: 10px;">
                        <em>{{ $action->commentaire_avancement ?? 'Aucun commentaire enregistré à ce jour.' }}</em>
                    </div>
                </div>

                @if($action->preuvesExecution->count() > 0)
                    <div class="preuves-section">
                        <div class="preuves-title">
                            PIÈCES JUSTIFICATIVES JOINTES ({{ $action->preuvesExecution->count() }}) :
                        </div>
                        <ul class="preuves-list">
                            @foreach($action->preuvesExecution as $preuve)
                                <li>
                                    <strong>{{ $preuve->file_name }}</strong>
                                    <span class="italic-note">
                                        - Type: {{ strtoupper(pathinfo($preuve->file_name, PATHINFO_EXTENSION)) }}
                                        - Ajouté le {{ $preuve->created_at->format('d/m/Y à H:i') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="warning-box" style="margin-top: 0.5cm;">
                        <em>⚠ Aucune pièce justificative n'a encore été jointe pour cette action.</em>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <!-- ==================== IV. HISTORIQUE DES ÉCHANGES ==================== -->
    @if($recommandation->commentaires->isNotEmpty())
        <div class="page-break"></div>
        <h1>IV. Historique des Échanges et Validations</h1>

        <p class="section-text">
            Cette section retrace l'ensemble des communications, observations et validations 
            effectuées dans le cadre du suivi de cette recommandation.
        </p>

        <table class="official-table">
            <thead>
                <tr style="background-color: #d0d0d0;">
                    <th style="width: 20%;">Date et heure</th>
                    <th style="width: 25%;">Auteur</th>
                    <th style="width: 55%;">Contenu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recommandation->commentaires->sortBy('created_at') as $comm)
                    <tr>
                        <td>{{ $comm->created_at->format('d/m/Y à H:i') }}</td>
                        <td>{{ $comm->auteur->name ?? 'Système automatique' }}</td>
                        <td>{{ $comm->contenu }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- ==================== V. CONCLUSION ==================== -->
    <div class="page-break"></div>
    <h1>V. Conclusion et Perspectives</h1>

    <h2>5.1. Synthèse de l'état d'exécution</h2>

    <p class="section-text">
        Au terme de cet état des lieux, la recommandation référencée 
        <strong>{{ $recommandation->reference }}</strong> présente un taux d'avancement global de 
        <strong>{{ number_format($recommandation->taux_avancement ?? 0, 0) }}%</strong>.
    </p>

    @php
        $actionsTerminees = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $actionsEnCours = $recommandation->plansAction->where('statut_execution', 'en_cours')->count();
        $actionsNonDemarrees = $recommandation->plansAction->whereNotIn('statut_execution', ['termine', 'en_cours'])->count();
        $totalActions = $recommandation->plansAction->count();
    @endphp

    <div class="info-box">
        <table class="no-border">
            <tr>
                <td style="width: 50%;"><strong>Actions terminées :</strong></td>
                <td><strong>{{ $actionsTerminees }} / {{ $totalActions }}</strong></td>
            </tr>
            <tr>
                <td><strong>Actions en cours :</strong></td>
                <td><strong>{{ $actionsEnCours }} / {{ $totalActions }}</strong></td>
            </tr>
            <tr>
                <td><strong>Actions non démarrées :</strong></td>
                <td><strong>{{ $actionsNonDemarrees }} / {{ $totalActions }}</strong></td>
            </tr>
        </table>
    </div>

    <h2>5.2. Observations et recommandations</h2>

    @if($recommandation->taux_avancement >= 80)
        <p class="section-text">
            L'état d'avancement satisfaisant témoigne de l'engagement des acteurs impliqués. 
            Il convient de finaliser les dernières actions en cours pour permettre la clôture 
            définitive de cette recommandation.
        </p>
    @elseif($recommandation->taux_avancement >= 50)
        <p class="section-text">
            L'exécution est en bonne voie. Il est recommandé de maintenir la dynamique actuelle 
            et d'accorder une attention particulière aux actions présentant des retards éventuels.
        </p>
    @else
        <div class="warning-box">
            <p><strong>⚠ ATTENTION :</strong> Le taux d'avancement demeure insuffisant. 
            Une mobilisation renforcée des acteurs concernés est nécessaire pour accélérer 
            la mise en œuvre du plan d'action.</p>
        </div>
    @endif

    @if($recommandation->estEnRetard())
        <div class="warning-box">
            <p><strong>⚠ DÉPASSEMENT DE DÉLAI :</strong> La date limite d'exécution 
            ({{ $recommandation->date_limite->format('d/m/Y') }}) est dépassée. 
            Une réunion de cadrage avec l'ensemble des parties prenantes est recommandée.</p>
        </div>
    @endif

    <h2>5.3. Prochaines étapes</h2>

    <ul style="margin-left: 2cm; line-height: 1.8;">
        <li>Finalisation des actions en cours</li>
        <li>Collecte et transmission des pièces justificatives manquantes</li>
        <li>Validation par l'Inspecteur Général pour clôture définitive</li>
        <li>Archivage du dossier dans le système de gestion des recommandations</li>
    </ul>

    <!-- ==================== SIGNATURES ==================== -->
    <div class="signature-section">
        <h2>Visa et Signatures</h2>

        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-label">Le Point Focal</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">
                        {{ $recommandation->pointFocal->name ?? '________________________' }}
                    </div>
                </td>
                <td>
                    <div class="signature-label">Le Responsable de Structure</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">
                        {{ $recommandation->responsable->name ?? '________________________' }}
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top: 1.5cm; text-align: center;">
            <div class="signature-label">Pour validation finale</div>
            <div style="margin-top: 0.4cm;"><strong>L'Inspecteur Général</strong></div>
            <div style="height: 2cm;"></div>
            <div class="signature-name">
                {{ $recommandation->inspecteurGeneral->name ?? '________________________' }}
            </div>
        </div>
    </div>

    <!-- ==================== FIN DU DOCUMENT ==================== -->
    <div style="margin-top: 3cm; text-align: center; font-style: italic; color: #555;">
        *** FIN DU RAPPORT ***
    </div>

</body>
</html>