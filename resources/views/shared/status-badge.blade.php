@php
$statusConfig = [
    'brouillon' => [
        'class' => 'bg-yellow-100 text-yellow-800',
        'icon' => 'fas fa-edit',
        'label' => 'Brouillon'
    ],
    'soumise_ig' => [
        'class' => 'bg-blue-100 text-blue-800',
        'icon' => 'fas fa-paper-plane',
        'label' => 'Soumise à l\'IG'
    ],
    'validee_ig' => [
        'class' => 'bg-green-100 text-green-800',
        'icon' => 'fas fa-check-circle',
        'label' => 'Validée par IG'
    ],
    'rejetee_ig' => [
        'class' => 'bg-red-100 text-red-800',
        'icon' => 'fas fa-times-circle',
        'label' => 'Rejetée par IG'
    ],
    'transmise_structure' => [
        'class' => 'bg-indigo-100 text-indigo-800',
        'icon' => 'fas fa-share',
        'label' => 'Transmise à la Structure'
    ],
    'point_focal_assigne' => [
        'class' => 'bg-purple-100 text-purple-800',
        'icon' => 'fas fa-user-tie',
        'label' => 'Point Focal assigné'
    ],
    'plan_en_redaction' => [
        'class' => 'bg-pink-100 text-pink-800',
        'icon' => 'fas fa-file-edit',
        'label' => 'Plan en rédaction'
    ],
    'plan_soumis_responsable' => [
        'class' => 'bg-orange-100 text-orange-800',
        'icon' => 'fas fa-file-upload',
        'label' => 'Plan soumis au Responsable'
    ],
    'plan_rejete_responsable' => [
        'class' => 'bg-yellow-100 text-yellow-900',
        'icon' => 'fas fa-exclamation-triangle',
        'label' => 'Plan rejeté par le Responsable'
    ],
    'plan_valide_responsable' => [
        'class' => 'bg-teal-100 text-teal-800',
        'icon' => 'fas fa-check',
        'label' => 'Plan validé par le Responsable'
    ],
    'plan_soumis_ig' => [
        'class' => 'bg-cyan-100 text-cyan-800',
        'icon' => 'fas fa-file-export',
        'label' => 'Plan soumis à l\'IG'
    ],
    'plan_valide_ig' => [
        'class' => 'bg-emerald-100 text-emerald-800',
        'icon' => 'fas fa-check-double',
        'label' => 'Plan validé par l\'IG'
    ],
    'plan_rejete_ig' => [
        'class' => 'bg-rose-100 text-rose-800',
        'icon' => 'fas fa-times',
        'label' => 'Plan rejeté par l\'IG'
    ],
    'en_execution' => [
        'class' => 'bg-sky-100 text-sky-800',
        'icon' => 'fas fa-play-circle',
        'label' => 'En cours d\'exécution'
    ],
    'execution_terminee' => [
        'class' => 'bg-lime-100 text-lime-800',
        'icon' => 'fas fa-flag-checkered',
        'label' => 'Exécution terminée'
    ],
    'demande_cloture' => [
        'class' => 'bg-amber-100 text-amber-800',
        'icon' => 'fas fa-flag',
        'label' => 'Demande de clôture'
    ],
    'cloturee' => [
        'class' => 'bg-gray-100 text-gray-800',
        'icon' => 'fas fa-check-double',
        'label' => 'Clôturée'
    ],
    'default' => [
        'class' => 'bg-gray-100 text-gray-800',
        'icon' => 'fas fa-question-circle',
        'label' => 'Statut inconnu'
    ]
];

$config = $statusConfig[$statut] ?? $statusConfig['default'];
@endphp

<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $config['class'] }}">
    <i class="{{ $config['icon'] }} mr-1.5"></i>
    {{ $config['label'] }}
</span>
