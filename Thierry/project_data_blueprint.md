# Project Technical Blueprint: GRIT (Migration & Data Layer)

This document provides a detailed representation of the **GRIT** application's data structure (entities, attributes, relationships, and business logic) to facilitate accurate diagram generation (UML, ERD).

---

## 1. Core Domain Entities

### 1.1 `User`
- **Table**: `users`
- **Attributes**:
    - `id`: BigInt (PK)
    - `name`: String
    - `email`: String (Unique)
    - `password`: String (Hashed)
    - `telephone`: String (regex sanitized)
    - `structure_id`: FK -> `structures.id`
    - `role_id`: FK -> `roles.id`
- **Relationships**:
    - `belongsTo` **Structure**
    - `belongsTo` **Role**
    - `hasMany` **Recommandation** (as `its_id`, `inspecteur_general_id`, `responsable_id`, `point_focal_id`)
    - `hasMany` **PlanAction** (as `point_focal_id`, `responsable_id`, `validateur_ig_id`)
- **Business Logic**: Roles include `admin`, `its`, `inspecteur_general`, `point_focal`, `responsable`, `cabinet_ministre`.

### 1.2 `Recommandation`
- **Table**: `recommandations`
- **Attributes**:
    - `id`: BigInt (PK)
    - `reference`: String (Unique, format: `REC-YYYY-NNNN`)
    - `titre`: String
    - `description`: Text
    - `priorite`: Enum (`basse`, `moyenne`, `haute`)
    - `statut`: Enum (See Workflow below)
    - `date_limite`: Date
    - `indicateurs`: Text
    - `incidence_financiere`: Enum (`faible`, `moyen`, `eleve`)
    - `delai_mois`: Integer
    - `date_debut_prevue`: Date
    - `date_fin_prevue`: Date
    - `its_id`: FK -> `users.id`
    - `inspecteur_general_id`: FK -> `users.id` (Nullable)
    - `responsable_id`: FK -> `users.id` (Nullable)
    - `point_focal_id`: FK -> `users.id` (Nullable)
    - `structure_id`: FK -> `structures.id`
- **Workflow Statuts**:
    - Creation: `brouillon`, `soumise_ig`, `validee_ig`, `rejetee_ig`
    - Assignment: `transmise_structure`, `point_focal_assigne`
    - Planning: `plan_en_redaction`, `plan_soumis_responsable`, `plan_valide_responsable`, `plan_rejete_responsable`, `plan_soumis_ig`, `plan_valide_ig`, `plan_rejete_ig`
    - Execution: `en_execution`, `execution_terminee`
    - Closure: `demande_cloture`, `cloturee`
- **Relationships**:
    - `belongsTo` **User** (ITS, IG, Responsable, Point Focal)
    - `belongsTo` **Structure**
    - `hasMany` **PlanAction**
    - `hasMany` **Commentaire**
    - `hasMany` **Notification**
    - `hasMany` **RecommandationDocument**

### 1.3 `PlanAction`
- **Table**: `plan_actions`
- **Attributes**:
    - `id`: BigInt (PK)
    - `action`: Text
    - `statut_execution`: Enum (`non_demarre`, `en_cours`, `termine`)
    - `pourcentage_avancement`: Integer (0-100)
    - `commentaire_avancement`: Text
    - `executant_type`: Enum (`moi_meme`, `autre`)
    - `executant_nom`: String
    - `executant_role`: String
    - `delai_mois`: Integer
    - `date_debut_prevue`: Date
    - `date_fin_prevue`: Date
    - `recommandation_id`: FK -> `recommandations.id`
    - `point_focal_id`: FK -> `users.id`
    - `responsable_id`: FK -> `users.id`
- **Relationships**:
    - `belongsTo` **Recommandation**
    - `belongsTo` **User** (Point Focal, Responsable)
    - `hasMany` **PreuveExecution**

---

## 2. Supporting Entities

### 2.1 `Structure`
- **Attributes**: `code`, `nom`, `sigle`, `description`, `active` (Boolean)
- **Relationships**: `hasMany` Users, `hasMany` Recommandations.

### 2.2 `Role` & `Permission`
- **Role Attributes**: `nom`
- **Permission Attributes**: `nom`
- **Relationship**: `belongsToMany` via `permission_role` junction table.

### 2.3 `Commentaire`
- **Attributes**: `user_id` (FK), `recommandation_id` (FK), `destinataire_role`, `contenu`, `type`.

### 2.4 `Notification`
- **Attributes**: `type`, `contenu`, `date_envoi`, `statut` (`lu`, `non_lu`), `user_id` (FK), `recommandation_id` (FK).

### 2.5 `PreuveExecution`
- **Attributes**: `plan_action_id` (FK), `file_path`, `file_name`, `description`.

### 2.6 `RecommandationDocument`
- **Attributes**: `recommandation_id` (FK), `file_path`, `file_name`, `description`.

### 2.7 `PointFocal` (Specific Mapping Entity)
- **Table**: `point_focals`
- **Attributes**: `its_id` (FK), `user_id` (FK), `structure_id` (FK), `designation_par` (FK), `date_designation`, `motivation`, `statut`.

---

## 3. Global Constants & Rules

### Global Settings Table
- **Keys**: `alert_deadline_1_days`, `alert_deadline_2_days`, `default_deadline_months`.

### Business Rules for Relationships
- **Point Focal**: Is assigned to a Structure and manages Plan Actions for Recommandations sent to that structure.
- **Responsable**: Validates Plan Actions and monitors execution within their structure.
- **ITS**: Creates Recommandations and verifies implementation.
- **Inspecteur Général (IG)**: Final authority for validating Recommandations and Actions Plans across all structures.
