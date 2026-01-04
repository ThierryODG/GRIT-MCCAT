# PROPOSITION DE RÉDACTION - PARTIE 2 : ANALYSE DES BESOINS

## II.2.2. Besoins fonctionnels

L'application a été découpée en plusieurs modules fonctionnels interconnectés pour répondre aux exigences de chaque acteur.

### II.2.2.1. Module d'authentification et gestion des utilisateurs
Ce module transversal sécurise l'accès à l'application.
*   **Fonctionnalités** : Connexion sécurisée, gestion du profil utilisateur (modification de mot de passe), et redirection automatique vers le tableau de bord approprié selon le rôle (ITS, IG, Point Focal, etc.).
*   **Spécificité technique** : Utilisation du package **Laravel Breeze** pour une gestion robuste des sessions et de la sécurité.

### II.2.2.2. Module de gestion des recommandations
Cœur du système à destination de l'ITS et de l'Inspecteur Général.
*   **Saisie** : L'ITS peut enregistrer les recommandations issues des rapports d'audit.
*   **Validation** : L'Inspecteur Général valide les recommandations avant qu'elles ne soient visibles par les structures auditées.
*   **Consultation** : Les Points Focaux peuvent consulter la liste des recommandations qui concernent leur structure.

### II.2.2.3. Module d'assignation des points focaux
Ce module permet de faire le lien entre une structure auditée et une personne physique responsable.
*   **Gestion** : L'Administrateur peut créer des comptes pour les Points Focaux et les Responsables de Structure, et les associer à une entité spécifique. Cela garantit que chaque acteur ne voit strictement que les données de son périmètre.

### II.2.2.4. Module de gestion des plans d'action
Dédié principalement aux Points Focaux pour organiser la réponse aux recommandations.
*   **Élaboration** : Pour chaque recommandation, le Point Focal définit des actions concrètes (`PlanAction`).
*   **Planification** : Saisie des dates de début et de fin prévues pour chaque action.
*   **Validation** : Soumission du plan d'action à l'ITS/IG pour validation.

### II.2.2.5. Module de suivi de l'avancement
Permet de suivre la vie d'une recommandation dans le temps.
*   **Mise à jour (Point Focal)** : Le Point Focal met à jour le statut des actions ("Non commencé", "En cours", "Terminé") et joint des preuves de réalisation (fichiers, rapports).
*   **Contrôle (ITS)** : L'ITS reçoit les mises à jour et peut valider ou rejeter les preuves fournies.

### II.2.2.6. Module de clôture
Gère la finalisation des processus.
*   **Clôture d'action** : Lorsqu'une action est jugée satisfaisante par l'ITS.
*   **Clôture de recommandation** : Lorsque toutes les actions d'une recommandation sont réalisées.

### II.2.2.7. Module de notifications
Assure la fluidité de la communication entre les acteurs sans nécessiter d'emails externes.
*   **Alertes temps réel** : Notification lors de l'arrivée d'une nouvelle recommandation, d'un retard sur une échéance, ou du rejet d'une preuve.
*   **Gestion** : Possibilité de marquer les notifications comme "lues".

### II.2.2.8. Module de tableaux de bord et rapports
Outil d'aide à la décision pour le Cabinet et l'IG.
*   **Statistiques** : Affichage graphique du taux de mise en œuvre des recommandations par structure.
*   **Rapports** : Génération de documents PDF récapitulatifs sur l'état des lieux des audits.

---

## II.2.3. Besoins non fonctionnels

Outre les fonctionnalités, le système doit respecter des critères de qualité essentiels pour son acceptation organisationnelle.

### II.2.3.1. Performance
*   Le système doit être réactif et supporter la charge lors des périodes de bilan annuel.
*   Optimisation des requêtes base de données (PostgreSQL) pour la génération rapide des rapports statistiques.

### II.2.3.2. Sécurité
*   **Authentification et Autorisation** : Strict contrôle des accès via des *Middlewares* et des *Policies* Laravel. Un Point Focal ne doit jamais accéder aux données d'une autre structure.
*   **Intégrité** : Protection contre les attaques CSRF (Cross-Site Request Forgery) et injection SQL grâce à l'ORM Eloquent.

### II.2.3.3. Ergonomie et accessibilité
*   Interface utilisateur intuitive, épurée et responsive ("Mobile First"), développée avec **Tailwind CSS**.
*   Facilité de navigation pour des utilisateurs non-techniciens (Inspecteurs, Ministres).

### II.2.3.4. Maintenabilité
*   Code structuré selon le pattern **MVC (Modèle-Vue-Contrôleur)** imposé par Laravel, facilitant les évolutions futures.
*   Respect des standards de code PSR (PHP Standards Recommendations).

### II.2.3.5. Compatibilité
*   L'application doit fonctionner sur les principaux navigateurs modernes (Chrome, Firefox, Edge) utilisés au sein de l'administration.
