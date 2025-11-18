# Code Changes Reference

## 1. IG Controller - Remove Direction Column

### File: `app/Http/Controllers/InspecteurGeneral/PlanActionController.php`

#### Change 1: index() method (Line 21)
```php
// BEFORE:
'recommandation.its:id,name,direction',

// AFTER:
'recommandation.its:id,name',
```

#### Change 2: show() method (Line 50)
```php
// BEFORE:
'recommandation.its:id,name,direction',

// AFTER:
'recommandation.its:id,name,telephone',
```

**Why:** Users table has no 'direction' column. Only 'id', 'name', 'telephone' available.

---

## 2. Point Focal Controller - Add Motif Clearing

### File: `app/Http/Controllers/PointFocal/RecommandationController.php`

#### Change: update() method (Lines 121-123)
```php
// ADD THIS AFTER $recommandation->update($validated):

$recommandation->plansAction()->update([
    'motif_rejet_responsable' => null,
    'motif_rejet_ig' => null
]);
```

**Full Context:**
```php
public function update(Request $request, Recommandation $recommandation)
{
    // ... validation ...
    
    $validated = $request->validated();
    $recommandation->update($validated);
    
    // NEW: Clear rejection motifs when Point Focal updates
    $recommandation->plansAction()->update([
        'motif_rejet_responsable' => null,
        'motif_rejet_ig' => null
    ]);
    
    return redirect()->route('point_focal.recommandations.show', $recommandation)
                    ->with('success', 'Recommandation mise à jour. Les motifs de rejet ont été effacés.');
}
```

**Why:** Ensures old rejection feedback doesn't persist after Point Focal resubmits.

---

## 3. Responsable Dossier View - Recommendation-Level Messaging

### File: `resources/views/responsable/validation_plans/dossier.blade.php`

#### Change 1: Add rejection banner after grid opening (Line 32-38)
```blade
<!-- NEW: Add this after <div class="grid grid-cols-1 gap-6 lg:grid-cols-3"> -->

@if($isRejectedResponsable)
<div class="flex items-start col-span-1 gap-3 p-4 text-red-700 border-l-4 border-red-500 rounded lg:col-span-3 bg-red-50">
    <i class="mt-0.5 fas fa-exclamation-circle flex-shrink-0"></i>
    <div>
        <h3 class="mb-1 font-semibold">Recommandation rejetée par le Responsable</h3>
        <p class="text-sm">Cette recommandation a été rejetée. Le Point Focal doit corriger les points soulevés et resubmettre l'ensemble de la contribution (planification et plans d'action).</p>
    </div>
</div>
@endif
```

#### Change 2: Remove red styling from plans (Line 127)
```blade
// BEFORE:
<div class="p-4 border-2 rounded-lg {{ $plan->statut_validation == 'rejete_responsable' ? 'border-red-300 bg-red-50' : 'border-gray-200 bg-blue-50' }}">

// AFTER:
<div class="p-4 border-2 border-gray-200 rounded-lg bg-gray-50">
```

Add context note (Line 115):
```blade
<p class="mb-4 text-sm italic text-gray-600">La validation porte sur l'ensemble de la recommandation (planification + tous les plans). Lorsque rejetée, la recommandation complète doit être revue par le Point Focal.</p>
```

#### Change 3: Update validation section heading (Line 168)
```blade
// BEFORE:
<h2 class="mb-4 text-lg font-semibold text-gray-900">Validation</h2>

// AFTER:
<h2 class="mb-4 text-lg font-semibold text-gray-900">Validation de la recommandation</h2>
```

#### Change 4: Enhance motif display (Line 171-177)
```blade
// BEFORE:
@if($globalMotif)
<div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded bg-red-50">
    <strong>Motif du rejet (Responsable) :</strong>
    <p class="mt-1 whitespace-pre-line">{{ $globalMotif }}</p>
</div>
@endif

// AFTER:
@if($globalMotif)
<div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
    <div class="flex gap-2 mb-2">
        <i class="mt-0.5 fas fa-times-circle text-red-600 flex-shrink-0"></i>
        <strong class="text-red-700">Recommandation rejetée</strong>
    </div>
    <p class="p-2 text-sm text-red-700 whitespace-pre-line bg-white border border-red-100 rounded">{{ $globalMotif }}</p>
    <p class="mt-2 text-xs italic text-red-600">Veuillez corriger l'ensemble de votre contribution et resubmettre.</p>
</div>
@endif
```

#### Change 5: Update button labels (Line 182, 198)
```blade
// BEFORE:
<button type="submit" class="...">
    <i class="mr-2 fas fa-check"></i>
    Valider
</button>

// AFTER:
<button type="submit" class="...">
    <i class="mr-2 fas fa-check"></i>
    Valider la recommandation
</button>
```

Same for Reject button: "Rejeter" → "Rejeter la recommandation"

#### Change 6: Update help text (Line 226-230)
```blade
// BEFORE:
<li>✓ <strong>Valider :</strong> Si les plans d'action sont acceptables</li>
<li>✗ <strong>Rejeter :</strong> Si des corrections sont nécessaires</li>
<li>→ <strong>Transmettre :</strong> Une fois tous les plans validés</li>

// AFTER:
<li>✓ <strong>Valider :</strong> La recommandation complète (planification + plans) est acceptable</li>
<li>✗ <strong>Rejeter :</strong> Des corrections sont nécessaires dans la planification ou les plans. Le Point Focal doit revoir l'ensemble.</li>
<li>→ <strong>Transmettre :</strong> Une fois validée, envoyer à l'Inspecteur Général</li>
<li><em>Note :</em> Validation et rejet s'appliquent à la recommandation entière, pas à des plans individuels.</li>
```

---

## 4. Point Focal Show View - Enhanced Messaging

### File: `resources/views/point_focal/recommandations/show.blade.php`

#### Change: Update motif display sections (Lines 287-306)
```blade
// BEFORE:
@if($globalMotifResponsable)
<div class="p-6 border border-yellow-200 rounded-lg shadow-sm bg-yellow-50">
    <h2 class="mb-4 text-lg font-semibold text-yellow-900">Demande de modification (Responsable)</h2>
    <p class="text-yellow-800 whitespace-pre-line">{{ $globalMotifResponsable }}</p>
</div>
@endif

@if($globalMotifIG)
<div class="p-6 border border-red-200 rounded-lg shadow-sm bg-red-50">
    <h2 class="mb-4 text-lg font-semibold text-red-900">Demande de modification (Inspecteur Général)</h2>
    <p class="text-red-800 whitespace-pre-line">{{ $globalMotifIG }}</p>
</div>
@endif

// AFTER:
@if($globalMotifResponsable)
<div class="p-6 border-l-4 border-yellow-400 rounded-lg shadow-sm bg-yellow-50">
    <h2 class="flex items-center gap-2 mb-2 text-lg font-semibold text-yellow-900">
        <i class="fas fa-exclamation-triangle"></i>
        Modifications demandées (Responsable)
    </h2>
    <p class="mb-3 text-sm italic text-yellow-800">La recommandation a été rejetée. Veuillez corriger l'ensemble de votre contribution (planification et/ou plans d'action) selon les indications ci-dessous :</p>
    <p class="p-3 text-yellow-800 whitespace-pre-line bg-white border border-yellow-200 rounded">{{ $globalMotifResponsable }}</p>
</div>
@endif

@if($globalMotifIG)
<div class="p-6 border-l-4 border-red-400 rounded-lg shadow-sm bg-red-50">
    <h2 class="flex items-center gap-2 mb-2 text-lg font-semibold text-red-900">
        <i class="fas fa-times-circle"></i>
        Modifications demandées (Inspecteur Général)
    </h2>
    <p class="mb-3 text-sm italic text-red-800">L'Inspecteur Général a demandé des modifications. Veuillez corriger votre contribution selon les indications suivantes :</p>
    <p class="p-3 text-red-800 whitespace-pre-line bg-white border border-red-200 rounded">{{ $globalMotifIG }}</p>
</div>
@endif
```

---

## Summary of Logic

### Flow After These Changes

1. **Point Focal Submits**
   - Creates/updates recommendation with planning fields + plans
   - Submits to Responsable

2. **Responsable Reviews**
   - Sees recommendation with neutral plan styling
   - Rejects with motif → motif_rejet_responsable is set on all PlanActions

3. **Point Focal Sees Rejection**
   - Views recommendation show page
   - Sees yellow banner with ⚠️ icon
   - Sees rejection motif in highlighted box
   - Understands rejection is about entire contribution

4. **Point Focal Updates**
   - Clicks Edit and updates any planning field
   - Saves changes
   - **Automatic:** All motif_rejet_responsable/motif_rejet_ig are set to NULL
   - Point Focal views show page again
   - **Rejection banner and motif are GONE**
   - Clean state ready to resubmit

5. **Responsable Reviews Clean State**
   - Sees recommendation without rejection history
   - Can now validate or reject again if needed

6. **IG Views Plans**
   - Opens IG interface
   - No DB errors ("direction" column removed)
   - Plans load successfully
