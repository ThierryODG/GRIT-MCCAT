<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $contenu
 * @property \Illuminate\Support\Carbon $date_envoi
 * @property string $statut
 * @property int $user_id
 * @property int|null $recommandation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Recommandation|null $recommandation
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification nonLues()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDateEnvoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereRecommandationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property string|null $categorie
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $action
 * @property string $statut_validation
 * @property string $statut_execution
 * @property int $pourcentage_avancement
 * @property string|null $commentaire_avancement
 * @property int|null $validateur_responsable_id
 * @property \Illuminate\Support\Carbon|null $date_validation_responsable
 * @property string|null $commentaire_validation_responsable
 * @property string|null $motif_rejet_responsable
 * @property int|null $validateur_ig_id
 * @property \Illuminate\Support\Carbon|null $date_validation_ig
 * @property string|null $commentaire_validation_ig
 * @property string|null $motif_rejet_ig
 * @property int $recommandation_id
 * @property int|null $point_focal_id
 * @property int|null $responsable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $statut_execution_label
 * @property-read mixed $statut_validation_label
 * @property-read \App\Models\User|null $pointFocal
 * @property-read \App\Models\Recommandation $recommandation
 * @property-read \App\Models\User|null $responsable
 * @property-read \App\Models\User|null $validateurIG
 * @property-read \App\Models\User|null $validateurResponsable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction enAttenteValidationIG()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction enAttenteValidationResponsable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction enExecution()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction valides()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereCommentaireAvancement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereCommentaireValidationIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereCommentaireValidationResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereDateValidationIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereDateValidationResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereMotifRejetIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereMotifRejetResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction wherePointFocalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction wherePourcentageAvancement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereRecommandationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereStatutExecution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereStatutValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereValidateurIgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanAction whereValidateurResponsableId($value)
 */
	class PlanAction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $designateur
 * @property-read \App\Models\User|null $its
 * @property-read \App\Models\Recommandation|null $recommandation
 * @property-read \App\Models\Structure|null $structure
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PointFocal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PointFocal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PointFocal parStructure($structureId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PointFocal pourITS($itsId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PointFocal query()
 */
	class PointFocal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string|null $contenu
 * @property \Illuminate\Support\Carbon $date_generation
 * @property int $utilisateur_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PlanAction|null $planAction
 * @property-read \App\Models\User $utilisateur
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereDateGeneration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapport whereUtilisateurId($value)
 */
	class Rapport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $reference
 * @property string $titre
 * @property string $description
 * @property string $priorite
 * @property \Illuminate\Support\Carbon $date_limite
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $date_validation_ig
 * @property \Illuminate\Support\Carbon|null $date_cloture
 * @property string|null $commentaire_ig
 * @property string|null $motif_rejet_ig
 * @property string|null $commentaire_demande_cloture
 * @property string|null $documents_justificatifs
 * @property string|null $motif_rejet_cloture
 * @property string|null $commentaire_cloture
 * @property int $its_id
 * @property int|null $inspecteur_general_id
 * @property int|null $responsable_id
 * @property int|null $point_focal_id
 * @property int $structure_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $date_assignation_pf
 * @property string|null $indicateurs
 * @property string|null $incidence_financiere
 * @property int|null $delai_mois
 * @property \Illuminate\Support\Carbon|null $date_debut_prevue
 * @property \Illuminate\Support\Carbon|null $date_fin_prevue
 * @property-read mixed $priorite_color
 * @property-read mixed $statut_label
 * @property-read \App\Models\User|null $inspecteurGeneral
 * @property-read \App\Models\User $its
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanAction> $plansAction
 * @property-read int|null $plans_action_count
 * @property-read \App\Models\User|null $pointFocal
 * @property-read \App\Models\User|null $responsable
 * @property-read \App\Models\Structure $structure
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation enRetard()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation parPriorite($priorite)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation parStatut($statut)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereCommentaireCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereCommentaireDemandeCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereCommentaireIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateAssignationPf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateDebutPrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateFinPrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateLimite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDateValidationIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDelaiMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereDocumentsJustificatifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereIncidenceFinanciere($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereIndicateurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereInspecteurGeneralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereItsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereMotifRejetCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereMotifRejetIg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation wherePointFocalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation wherePriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereStructureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommandation whereUpdatedAt($value)
 */
	class Recommandation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $utilisateurs
 * @property-read int|null $utilisateurs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $nom
 * @property string|null $sigle
 * @property string|null $description
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommandation> $recommandations
 * @property-read int|null $recommandations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereSigle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Structure whereUpdatedAt($value)
 */
	class Structure extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $telephone
 * @property int|null $structure_id
 * @property int $role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanAction> $plansActionPointFocal
 * @property-read int|null $plans_action_point_focal_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanAction> $plansActionResponsable
 * @property-read int|null $plans_action_responsable_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanAction> $plansActionValides
 * @property-read int|null $plans_action_valides_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommandation> $recommandationsAssignees
 * @property-read int|null $recommandations_assignees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommandation> $recommandationsCreees
 * @property-read int|null $recommandations_creees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommandation> $recommandationsResponsable
 * @property-read int|null $recommandations_responsable_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommandation> $recommandationsValidees
 * @property-read int|null $recommandations_validees_count
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\Structure|null $structure
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStructureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

