# PROPOSITION DE RÉDACTION - PARTIE 1 : GESTION DE PROJET ET ACTEURS

## II.1.3. Gestion de projet

Cette section détaille l'organisation méthodologique et temporelle mise en place pour la réalisation de l'application SIGR-ITS.

### II.1.3.1. Organisation du travail

Pour mener à bien ce projet, nous avons adopté le processus unifié **2TUP (Two Track Unified Process)**. Ce choix s'est imposé par la nécessité de concilier l'apprentissage de nouvelles technologies (Laravel, PostgreSQL) avec la complexité des règles métiers de l'Inspection Technique des Services (ITS).

La méthodologie 2TUP nous a permis de séparer le projet en deux branches distinctes avant leur convergence :

1.  **La branche technique** : Consacrée à la mise en place de l'environnement de développement, à l'architecture logicielle et à la validation des choix technologiques (Framework Laravel 11, Base de données PostgreSQL). Cette phase a permis de lever les risques techniques très tôt.
2.  **La branche fonctionnelle** : Focalisée sur l'analyse des besoins métiers à travers des entretiens avec les différents acteurs (Inspecteurs, Points Focaux) et la modélisation des processus (Diagrammes de Cas d'Utilisation UML).

La phase de réalisation s'est ensuite déroulée de manière **itérative et incrémentale**. Chaque module (Authentification, Gestion des missions, Suivi des plans d'action) a été développé, testé et validé indépendamment avant d'être intégré à l'application globale.

**Outils de collaboration et de développement :**
*   **Gestion de version** : Git (pour le suivi des modifications).
*   **Environnement de développement** : Visual Studio Code, Laragon.
*   **Modélisation** : PlantUML (pour la création des diagrammes UML).
*   **Framework** : Laravel 11 (Back-end) et Blade/TailwindCSS (Front-end).

### II.1.3.2. Planning prévisionnel

Le projet s'est étalé sur une période définie, structurée selon les phases suivantes :

| Phase | Activités Principales | Période (Estimative) |
| :--- | :--- | :--- |
| **Phase 1 : Immersion et Étude préalable** | Prise de contact, compréhension du contexte de l'ITS, étude de l'existant. | Semaine 1 |
| **Phase 2 : Analyse et Auto-formation** | Apprentissage du Framework Laravel, mise en place de l'environnement, analyse des besoins (Branch Tech & Fonct). | Semaine 2 |
| **Phase 3 : Conception et Prototypage** | Modélisation UML (Cas d'utilisation, Classes), création de la base de données, maquettes des interfaces. | Semaine 3 |
| **Phase 4 : Développement (Itération 1)** | Développement du module d'authentification (rôles/permissions) et gestion des structures. | Semaine 4 |
| **Phase 5 : Développement (Itération 2)** | Module de gestion des Recommandations et Plans d'action. | Semaine 5 |
| **Phase 6 : Développement (Itération 3)** | Fonctionnalités avancées (Ordonnancement intelligent, Notifications, Tableaux de bord). | Semaine 6 |
| **Phase 7 : Tests et Déploiement** | Tests unitaires, correction de bugs, rédaction du rapport et manuel d'utilisation. | Semaine 7 |

---

## II.2. ANALYSE DES BESOINS

L'analyse des besoins vise à traduire les attentes des utilisateurs en fonctionnalités concrètes. Elle débute par l'identification précise des acteurs interagissant avec le système.

### II.2.1. Identification des acteurs

L'application SIGR-ITS est conçue pour être utilisée par plusieurs types d'utilisateurs, chacun ayant des responsabilités et des droits d'accès spécifiques. Ces acteurs ont été identifiés et matérialisés dans le système via le module de gestion des rôles (`RoleSeeder`).

#### II.2.1.1. L'Inspecteur (ITS)
C'est un acteur central du système, membre de l'Inspection Technique des Services.
*   **Rôle** : Il est chargé de la création des missions d'audit et de la saisie initiale des recommandations issues des rapports.
*   **Responsabilités** : Il initie le processus en enregistrant les recommandations dans la base de données et assure le suivi de premier niveau des réponses fournies par les structures auditées.

#### II.2.1.2. L'Inspecteur Général (IG)
Il s'agit du responsable hiérarchique de l'ITS.
*   **Rôle** : Il valide les étapes clés du processus.
*   **Responsabilités** : Il valide les recommandations saisies avant leur envoi aux structures. Il valide également les plans d'actions proposés par les audités. Son action déclenche souvent le calcul automatique des échéances (ordonnancement intelligent). Il dispose d'une vue globale sur toutes les statistiques.

#### II.2.1.3. Le Responsable de Structure
Il représente l'entité auditée (Direction, Service, etc.).
*   **Rôle** : Il est le garant de la mise en œuvre des recommandations au sein de sa structure.
*   **Responsabilités** : Il reçoit les notifications de nouvelles recommandations et supervise le travail du Point Focal. Il est informé de l'avancement mais délègue souvent la saisie opérationnelle.

#### II.2.1.4. Le Point Focal
C'est l'interlocuteur opérationnel désigné au sein de la structure auditée.
*   **Rôle** : Il est chargé de la mise à jour quotidienne des dossiers.
*   **Responsabilités** : Il se connecte pour proposer des plans d'actions (actions, responsables, échéances) et mettre à jour le statut des actions (ex: passer de "Non commencé" à "Terminé"). Il doit fournir les preuves de réalisation.

#### II.2.1.5. Le Cabinet du Ministre
C'est un acteur de haut niveau (N+1 ou autorité de tutelle).
*   **Rôle** : Observateur stratégique.
*   **Responsabilités** : Il ne participe pas au traitement opérationnel mais consulte les tableaux de bord et les rapports de synthèse pour évaluer la performance globale des services et le taux de mise en œuvre des recommandations.

#### II.2.1.6. L'Administrateur Système (Admin)
Bien que non mentionné explicitement dans le métier, cet acteur technique est essentiel pour la maintenance.
*   **Responsabilités** : Gestion des comptes utilisateurs, configuration des paramètres globaux de l'application et maintenance technique.
