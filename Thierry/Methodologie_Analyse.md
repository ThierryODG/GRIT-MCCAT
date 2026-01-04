# Analyse Méthodologique de Projet : Two Track Unified Process (2TUP)

## 1. Introduction
Pour la réalisation de l'application **SIGR-ITS**, nous avons adopté une démarche structurée, à la fois rigoureuse sur le plan de la conception (UML) et flexible sur le plan du développement. En analysant notre parcours, la méthodologie qui correspond le mieux à notre manière de travailler est le **2TUP (Two Track Unified Process)**.

Le 2TUP appartient à la famille du **Processus Unifié (UP)**. Sa particularité est de séparer l'étude fonctionnelle de l'étude technique pour les faire converger lors de la conception.

## 2. Pourquoi 2TUP pour ce projet ?

Votre expérience au sein de l'institut d'accueil illustre parfaitement les deux "branches" (tracks) de cette méthode :

### A. La Branche Technique (Technical Track)
C'est le travail que vous avez effectué durant la première semaine. 
- **Adaptation technologique** : Apprentissage de Laravel 11 et PostgreSQL.
- **Objectif** : Maîtriser les outils et l'architecture avant de commencer le développement pur. Dans le modèle 2TUP, cela permet de choisir les "solutions génériques" (comment on gère la base de données, comment on structure les routes) indépendamment des besoins métier immédiats.

### B. La Branche Fonctionnelle (Functional Track)
C'est le travail d'analyse métier après l'obtention de votre thème.
- **Capture des besoins** : Entretiens, description des missions de l'ITS, du Responsable et de l'IG.
- **Modélisation** : Création des diagrammes de Cas d'Utilisation (Use Cases) pour identifier *qui fait quoi*.
- **Objectif** : Comprendre le "Quoi" avant le "Comment".

## 3. La Convergence : La Branche "Y"
Le 2TUP est souvent représenté sous la forme d'un **Y**. Une fois que les deux branches (Technique et Fonctionnelle) sont matures, elles se rejoignent pour la **Conception Objet** et le **Développement**.

C'est là que nous intervenons ensemble :
- **Conception** : Transformation des besoins fonctionnels en classes PHP (Modèles Eloquent) en respectant les contraintes techniques de Laravel.
- **Réalisation** : Développement itératif des fonctionnalités (Gestion des recommandations -> Assignation Point Focal -> Ordonnancement Intelligent -> Rapports).

## 4. Un Processus Itératif et Incrémental
Même si le 2TUP structure l'analyse, la réalisation reste **itérative**. Comme vous l'avez remarqué, "des éléments s'ajoutent au fur et à mesure". 

- **Itératif** : Nous revenons sur des parties déjà faites pour les améliorer (ex: affiner le diagramme de classes après avoir codé une relation complexe).
- **Incrémental** : Nous construisons l'application brique par brique. Chaque fin de discussion valide un incrément fonctionnel (un module qui marche).

## 5. Comment le présenter dans votre rapport de stage

Vous pouvez structurer votre chapitre "Méthodologie" ainsi :

1.  **Le Choix Méthodologique** : Justifiez l'utilisation du 2TUP par la nécessité de découpler l'apprentissage technique (Laravel/Postgres) des enjeux métiers complexes (gestion des audits).
2.  **La Branche Technique** : Décrivez votre semaine d'immersion et de formation autonome sur le framework.
3.  **La Branche Fonctionnelle** : Présentez vos diagrammes de Cas d'Utilisation comme le pivot de votre compréhension du système.
4.  **L'Agilité de Collaboration** : Expliquez comment l'utilisation d'un assistant IA a permis d'accélérer les cycles de "Conception -> Test -> Feedback" (Iteration), rendant le projet vivant et évolutif.

## 6. Conclusion
En résumé, vous ne travaillez pas "au hasard". Vous utilisez une méthode **semi-formelle** : formelle dans son organisation (Diagrammes, Processus Unifié) et agile dans son exécution quotidienne. C'est une approche très appréciée en entreprise car elle garantit une documentation solide tout en restant réactive aux changements.
