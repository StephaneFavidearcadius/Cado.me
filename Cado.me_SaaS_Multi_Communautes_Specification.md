# CADO.ME --- SPÉCIFICATION MAÎTRE DU SAAS MULTI-COMMUNAUTÉS

> **Document de référence obligatoire pour l'agent IA chargé de
> concevoir et développer Cado.me.**
>
> Cette version transforme Cado.me en **SaaS multi-communautés** :
> plusieurs créateurs/administrateurs peuvent créer et gérer leur propre
> communauté sur une même plateforme.
>
> La règle principale est désormais : **une plateforme Cado.me,
> plusieurs communautés isolées les unes des autres**.

------------------------------------------------------------------------

# 1. IDENTITÉ DU PRODUIT

## Nom

**Cado.me**

## Nature

Cado.me est une plateforme SaaS permettant à plusieurs créateurs,
experts, coachs, formateurs, entrepreneurs ou marques de créer leur
propre communauté privée dans un espace centralisé.

Le produit reprend les grands concepts fonctionnels d'une plateforme
communautaire moderne :

-   Feed ;
-   publications ;
-   images ;
-   vidéos ;
-   fichiers ;
-   commentaires ;
-   likes ;
-   membres ;
-   formations ;
-   ressources ;
-   événements ;
-   notifications ;
-   messagerie ;
-   administration.

Mais contrairement à la version mono-communauté, **Cado.me héberge
plusieurs communautés indépendantes sur la même application**.

------------------------------------------------------------------------

# 2. MODÈLE SAAS

Architecture globale :

``` text
Cado.me
│
├── Plateforme
│
├── Communauté A
│   ├── Propriétaire
│   ├── Administrateurs
│   ├── Membres
│   ├── Feed
│   ├── Formations
│   ├── Ressources
│   └── Événements
│
├── Communauté B
│   ├── Propriétaire
│   ├── Administrateurs
│   ├── Membres
│   ├── Feed
│   ├── Formations
│   ├── Ressources
│   └── Événements
│
└── Communauté C
    └── ...
```

## Règle absolue

**Aucune donnée d'une communauté ne doit être accessible à une autre
communauté sans autorisation explicite.**

Chaque donnée métier appartenant à une communauté doit être reliée à :

``` text
communaute_id
```

------------------------------------------------------------------------

# 3. MULTI-TENANCY

Le multi-tenancy est le cœur architectural du SaaS.

## Modèle retenu

Utiliser :

``` text
Base MySQL partagée
+
Tables partagées
+
communaute_id
```

Exemple :

``` text
publications
├── id
├── communaute_id
├── utilisateur_id
└── contenu
```

Ainsi :

``` text
Communauté A → communaute_id = 1
Communauté B → communaute_id = 2
```

## Ne pas utiliser pour la V1

-   une base MySQL par communauté ;
-   un serveur par communauté ;
-   un microservice par communauté.

Ces architectures peuvent être envisagées à très grande échelle, mais
elles sont inutiles pour commencer.

------------------------------------------------------------------------

# 4. CONCEPTS PRINCIPAUX

Il existe trois niveaux :

``` text
PLATEFORME
    ↓
COMMUNAUTÉ
    ↓
UTILISATEURS / CONTENUS
```

## Plateforme

Gérée par les administrateurs globaux de Cado.me.

## Communauté

Espace appartenant à un créateur.

## Utilisateur

Une même personne peut éventuellement appartenir à plusieurs
communautés.

------------------------------------------------------------------------

# 5. RÔLES

## Niveau plateforme

``` text
super_administrateur
support
```

## Niveau communauté

``` text
proprietaire
administrateur
moderateur
membre
```

Un rôle doit toujours être évalué dans son contexte.

Exemple :

``` text
utilisateur_id = 15
communaute_id = 3
role = administrateur
```

La même personne peut être :

``` text
membre
```

dans une autre communauté.

------------------------------------------------------------------------

# 6. STACK OFFICIELLE

## Backend

``` text
PHP 8.4+
PHP Native
Composer
PDO
MySQL 8+
Architecture MVC maison
Sessions PHP
Middleware
Services
Repositories
Validators
```

## Frontend

``` text
HTML5
Tailwind CSS
Alpine.js
JavaScript Vanilla
Lucide Icons
```

## Packages PHP

Utiliser uniquement les packages nécessaires.

Packages recommandés :

``` text
vlucas/phpdotenv
symfony/mailer
symfony/validator
aws/aws-sdk-php
intervention/image
monolog/monolog
```

## Tests / qualité

``` text
PHPUnit ou Pest
PHPStan
PHP-CS-Fixer
```

------------------------------------------------------------------------

# 7. INTERDIT

Ne pas transformer le projet en :

``` text
Laravel
Symfony Framework
Next.js
React
Vue
Angular
WordPress
Bootstrap
Microservices
Kubernetes
```

Le backend reste :

``` text
PHP Native MVC
```

------------------------------------------------------------------------

# 8. ARCHITECTURE TECHNIQUE

``` text
Navigateur
    ↓
public/index.php
    ↓
Router
    ↓
Résolution du domaine / slug
    ↓
Middleware
    ↓
Controller
    ↓
Service
    ↓
Repository
    ↓
PDO
    ↓
MySQL
```

Pour les fichiers :

``` text
Controller
↓
Service
↓
StorageService
↓
Stockage local / S3
```

------------------------------------------------------------------------

# 9. IDENTIFICATION DE LA COMMUNAUTÉ

Cado.me doit déterminer la communauté courante avant toute opération
métier.

Exemples d'URL possibles :

``` text
/app/{slug_communaute}
```

ou :

``` text
{slug}.cado.me
```

ou :

``` text
cado.me/c/{slug}
```

## Recommandation V1

Utiliser :

``` text
cado.me/c/{slug}
```

Cette solution simplifie le développement local.

Exemple :

``` text
cado.me/c/marketing-pro
```

La plateforme doit résoudre :

``` text
slug = marketing-pro
```

puis :

``` text
communaute_id = 12
```

Toutes les requêtes suivantes utilisent ce contexte.

------------------------------------------------------------------------

# 10. SOUS-DOMAINES

Architecture future :

``` text
marketing.cado.me
business.cado.me
formation.cado.me
```

Cette fonctionnalité doit être considérée comme compatible avec
l'architecture, mais **elle n'est pas obligatoire pour la V1**.

Le code doit isoler la résolution du contexte dans un service :

``` text
ContexteCommunauteService
```

------------------------------------------------------------------------

# 11. STRUCTURE DES DOSSIERS

``` text
cado-me/
│
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AccueilController.php
│   │   ├── CommunauteController.php
│   │   ├── FeedController.php
│   │   ├── PublicationController.php
│   │   ├── CommentaireController.php
│   │   ├── MembreController.php
│   │   ├── FormationController.php
│   │   ├── LeconController.php
│   │   ├── RessourceController.php
│   │   ├── EvenementController.php
│   │   ├── NotificationController.php
│   │   ├── MessageController.php
│   │   ├── ProfilController.php
│   │   ├── TableauDeBordController.php
│   │   └── PlateformeController.php
│   │
│   ├── Models/
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── CommunauteService.php
│   │   ├── ContexteCommunauteService.php
│   │   ├── MembreCommunauteService.php
│   │   ├── PublicationService.php
│   │   ├── CommentaireService.php
│   │   ├── FormationService.php
│   │   ├── EvenementService.php
│   │   ├── NotificationService.php
│   │   ├── MessageService.php
│   │   ├── StorageService.php
│   │   └── EmailService.php
│   │
│   ├── Repositories/
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── CommunauteMiddleware.php
│   │   ├── ProprietaireMiddleware.php
│   │   ├── AdministrateurMiddleware.php
│   │   ├── SuperAdministrateurMiddleware.php
│   │   └── CsrfMiddleware.php
│   │
│   ├── Validators/
│   │
│   ├── Helpers/
│   │
│   └── Core/
│       ├── Router.php
│       ├── Database.php
│       ├── Request.php
│       ├── Response.php
│       ├── Session.php
│       ├── View.php
│       └── Pagination.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── mail.php
│   ├── storage.php
│   └── abonnement.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   ├── index.php
│   └── assets/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── composants/
│   │   ├── public/
│   │   ├── auth/
│   │   ├── communaute/
│   │   ├── feed/
│   │   ├── membres/
│   │   ├── formations/
│   │   ├── ressources/
│   │   ├── evenements/
│   │   ├── notifications/
│   │   ├── messages/
│   │   ├── profil/
│   │   ├── createur/
│   │   └── plateforme/
│   │
│   └── emails/
│
├── routes/
│   ├── web.php
│   └── admin.php
│
├── storage/
│   ├── logs/
│   ├── cache/
│   └── sessions/
│
├── uploads/
│   ├── communautes/
│   ├── avatars/
│   ├── publications/
│   ├── formations/
│   └── ressources/
│
├── tests/
├── vendor/
│
├── .env
├── .env.example
├── .gitignore
└── composer.json
```

------------------------------------------------------------------------

# 12. BASE DE DONNÉES

Toutes les tables et colonnes métier doivent rester **en français**.

Le champ central du multi-tenant est :

``` text
communaute_id
```

Il doit être ajouté à toutes les tables qui contiennent des données
propres à une communauté.

------------------------------------------------------------------------

# 13. TABLE `utilisateurs`

``` text
utilisateurs
├── id
├── prenom
├── nom
├── identifiant
├── email
├── mot_de_passe
├── avatar
├── biographie
├── role_plateforme
├── statut
├── email_verifie
├── date_creation
└── date_modification
```

`role_plateforme` peut contenir :

``` text
aucun
super_administrateur
support
```

Le rôle dans une communauté ne doit pas être stocké ici.

------------------------------------------------------------------------

# 14. TABLE `communautes`

``` text
communautes
├── id
├── proprietaire_id
├── nom
├── slug
├── description
├── logo
├── image_couverture
├── couleur_principale
├── couleur_secondaire
├── statut
├── visibilite
├── parametres
├── date_creation
└── date_modification
```

## Statuts

``` text
active
suspendue
archivee
```

## Visibilité

``` text
publique
privee
```

------------------------------------------------------------------------

# 15. TABLE `membres_communautes`

Cette table est fondamentale.

``` text
membres_communautes
├── id
├── communaute_id
├── utilisateur_id
├── role
├── statut
├── date_adhesion
├── date_derniere_activite
└── date_modification
```

## Contraintes

``` text
UNIQUE(communaute_id, utilisateur_id)
```

Une personne ne peut avoir qu'une seule adhésion active par communauté.

------------------------------------------------------------------------

# 16. ISOLATION DES COMMUNAUTÉS

Exemple :

``` text
membres_communautes
```

Une requête ne doit jamais faire :

``` sql
SELECT * FROM publications;
```

dans un contexte communautaire.

Elle doit obligatoirement être filtrée :

``` sql
SELECT *
FROM publications
WHERE communaute_id = :communaute_id;
```

Cette règle s'applique à :

-   publications ;
-   commentaires ;
-   likes ;
-   formations ;
-   ressources ;
-   événements ;
-   notifications ;
-   messages communautaires ;
-   signalements ;
-   statistiques.

------------------------------------------------------------------------

# 17. REPOSITORIES TENANT-AWARE

Les repositories métier doivent recevoir le contexte communautaire.

Exemple conceptuel :

``` text
PublicationRepository
    ↓
chercherParCommunaute(communauteId)
```

Éviter les méthodes dangereuses :

``` text
chercherToutesLesPublications()
```

sans contexte.

------------------------------------------------------------------------

# 18. PUBLICATIONS

``` text
publications
├── id
├── communaute_id
├── utilisateur_id
├── contenu
├── type
├── statut
├── date_creation
└── date_modification
```

Types :

``` text
texte
image
video
fichier
lien
sondage
```

------------------------------------------------------------------------

# 19. MÉDIAS

``` text
medias_publications
├── id
├── communaute_id
├── publication_id
├── type
├── nom_fichier
├── nom_stockage
├── chemin
├── url
├── mime_type
├── taille
├── largeur
├── hauteur
├── ordre
└── date_creation
```

Toujours vérifier que :

``` text
media.communaute_id
=
publication.communaute_id
```

------------------------------------------------------------------------

# 20. COMMENTAIRES

``` text
commentaires
├── id
├── communaute_id
├── publication_id
├── utilisateur_id
├── commentaire_parent_id
├── contenu
├── statut
├── date_creation
└── date_modification
```

------------------------------------------------------------------------

# 21. LIKES

``` text
likes_publications
├── id
├── communaute_id
├── publication_id
├── utilisateur_id
└── date_creation
```

Contrainte :

``` text
UNIQUE(communaute_id, publication_id, utilisateur_id)
```

------------------------------------------------------------------------

# 22. ENREGISTREMENTS

``` text
publications_enregistrees
├── id
├── communaute_id
├── publication_id
├── utilisateur_id
└── date_creation
```

------------------------------------------------------------------------

# 23. PARTAGES

``` text
partages_publications
├── id
├── communaute_id
├── publication_id
├── utilisateur_id
└── date_creation
```

------------------------------------------------------------------------

# 24. SIGNALEMENTS

``` text
signalements
├── id
├── communaute_id
├── utilisateur_id
├── publication_id
├── commentaire_id
├── motif
├── statut
└── date_creation
```

------------------------------------------------------------------------

# 25. FORMATIONS

``` text
formations
├── id
├── communaute_id
├── titre
├── slug
├── description
├── image
├── statut
├── ordre
├── date_creation
└── date_modification
```

------------------------------------------------------------------------

# 26. LEÇONS

``` text
lecons
├── id
├── communaute_id
├── formation_id
├── titre
├── slug
├── description
├── contenu
├── video_url
├── ordre
├── statut
└── date_creation
```

Toujours vérifier :

``` text
lecon.communaute_id
=
formation.communaute_id
```

------------------------------------------------------------------------

# 27. PROGRESSION

``` text
progression_formations
├── id
├── communaute_id
├── utilisateur_id
├── lecon_id
├── terminee
├── date_completion
└── date_creation
```

------------------------------------------------------------------------

# 28. RESSOURCES

``` text
ressources
├── id
├── communaute_id
├── titre
├── description
├── type
├── chemin
├── url
├── nom_fichier
├── statut
├── ordre
├── date_creation
└── date_modification
```

------------------------------------------------------------------------

# 29. ÉVÉNEMENTS

``` text
evenements
├── id
├── communaute_id
├── titre
├── slug
├── description
├── date_debut
├── date_fin
├── type
├── lien
├── image
├── statut
├── date_creation
└── date_modification
```

------------------------------------------------------------------------

# 30. NOTIFICATIONS

Les notifications communautaires doivent être liées à leur communauté.

``` text
notifications
├── id
├── communaute_id
├── utilisateur_id
├── type
├── titre
├── message
├── lien
├── lue
└── date_creation
```

------------------------------------------------------------------------

# 31. MESSAGERIE

## Conversations

``` text
conversations
├── id
├── communaute_id
├── date_creation
└── date_modification
```

## Participants

``` text
participants_conversations
├── conversation_id
├── communaute_id
├── utilisateur_id
└── date_creation
```

## Messages

``` text
messages
├── id
├── communaute_id
├── conversation_id
├── utilisateur_id
├── contenu
├── lu
└── date_creation
```

Une conversation communautaire ne doit jamais traverser deux
communautés.

------------------------------------------------------------------------

# 32. AUTHENTIFICATION

Fonctionnalités :

``` text
Inscription
Connexion
Déconnexion
Vérification email
Mot de passe oublié
Réinitialisation
Changement mot de passe
Gestion sessions
```

Utiliser :

``` php
password_hash()
password_verify()
```

Après connexion :

``` php
session_regenerate_id(true);
```

------------------------------------------------------------------------

# 33. APPARTENANCE AUX COMMUNAUTÉS

Un utilisateur connecté peut avoir :

``` text
Communauté A
Communauté B
Communauté C
```

L'interface doit permettre de changer de communauté si l'utilisateur en
possède plusieurs.

Exemple :

``` text
┌─────────────────────────┐
│ Communauté actuelle     │
│                         │
│ ● Marketing Pro         │
│   Business Academy      │
│   Freelance Club        │
└─────────────────────────┘
```

------------------------------------------------------------------------

# 34. CRÉATION D'UNE COMMUNAUTÉ

Le créateur doit pouvoir :

1.  créer un compte ;
2.  créer une communauté ;
3.  choisir son nom ;
4.  choisir son slug ;
5.  ajouter logo ;
6.  ajouter description ;
7.  configurer sa communauté ;
8.  inviter des membres.

À la création :

``` text
utilisateur
↓
communauté
↓
membres_communautes
role = proprietaire
```

------------------------------------------------------------------------

# 35. INVITATIONS

Prévoir :

``` text
invitations_communautes
```

Structure :

``` text
invitations_communautes
├── id
├── communaute_id
├── email
├── token
├── role
├── expire_le
├── acceptee
└── date_creation
```

Une invitation appartient à une seule communauté.

------------------------------------------------------------------------

# 36. ACCÈS À UNE COMMUNAUTÉ

L'accès doit vérifier :

``` text
1. communauté existe
2. communauté active
3. utilisateur authentifié si privée
4. utilisateur membre
5. rôle
6. autorisation
```

------------------------------------------------------------------------

# 37. COMMUNAUTÉS PUBLIQUES

Une communauté peut être :

``` text
publique
```

La page d'accueil peut être consultable publiquement.

Mais les fonctionnalités privées restent protégées.

Exemple :

``` text
/c/marketing-pro
```

visible publiquement.

Puis :

``` text
/c/marketing-pro/app
```

réservé aux membres.

------------------------------------------------------------------------

# 38. FEED

Le Feed est le cœur de chaque communauté.

``` text
Feed Cado.me
=
Feed de la communauté courante
```

Ne jamais mélanger :

``` text
Communauté A
+
Communauté B
```

dans un même Feed.

------------------------------------------------------------------------

# 39. PUBLICATIONS MULTIMÉDIAS

Une publication peut contenir :

``` text
Texte
Image
Plusieurs images
Vidéo
Fichier
Lien
Sondage
```

Le système doit supporter plusieurs médias.

------------------------------------------------------------------------

# 40. STOCKAGE

Créer :

``` text
StorageService
```

avec :

``` text
StockageLocal
StockageS3
```

## Local

``` text
/uploads/{communaute_id}/
```

Exemple :

``` text
/uploads/12/publications/
/uploads/12/avatars/
/uploads/12/formations/
```

## Production

``` text
Cloudflare R2
```

Le code métier ne doit jamais dépendre directement de R2.

------------------------------------------------------------------------

# 41. R2 / S3

Configuration future :

``` text
STORAGE_DRIVER=s3
S3_ENDPOINT=
S3_BUCKET=
S3_ACCESS_KEY=
S3_SECRET_KEY=
S3_REGION=
```

Pour Cloudflare R2, utiliser le SDK compatible S3.

------------------------------------------------------------------------

# 42. SÉCURITÉ DU STOCKAGE

Les fichiers doivent être isolés logiquement par :

``` text
communaute_id
```

Ne jamais accepter un chemin fourni directement par l'utilisateur.

Toujours générer :

``` text
nom_stockage
```

aléatoire et sécurisé.

------------------------------------------------------------------------

# 43. DESIGN SYSTEM

La DA Violet & Blanc est conservée pour Cado.me.

Couleur principale :

``` text
#7830E0
```

Violet foncé :

``` text
#6420C7
```

Violet clair :

``` text
#9B5DEB
```

Violet très clair :

``` text
#F3EAFF
```

Blanc :

``` text
#FFFFFF
```

------------------------------------------------------------------------

# 44. TYPOGRAPHIE

Police principale :

``` text
Sora
```

Fallback :

``` text
-apple-system,
BlinkMacSystemFont,
"Segoe UI",
Roboto,
sans-serif
```

------------------------------------------------------------------------

# 45. INTERFACE SAAS

Il existe trois interfaces principales.

## Interface plateforme

Pour Cado.me :

``` text
/admin
```

## Interface propriétaire

Pour gérer une communauté :

``` text
/c/{slug}/gestion
```

## Interface membre

Pour utiliser une communauté :

``` text
/c/{slug}/app
```

------------------------------------------------------------------------

# 46. TABLEAU DE BORD PROPRIÉTAIRE

Afficher :

``` text
Membres
Publications
Engagement
Formations
Événements
Activité récente
```

Statistiques possibles :

``` text
Nombre de membres
Nouveaux membres
Nombre de publications
Commentaires
Likes
Taux d'activité
```

------------------------------------------------------------------------

# 47. GESTION DE COMMUNAUTÉ

Le propriétaire peut :

``` text
Modifier nom
Modifier description
Modifier logo
Modifier couverture
Modifier couleurs
Modifier slug
Gérer membres
Gérer contenus
Gérer formations
Gérer ressources
Gérer événements
Gérer modération
```

------------------------------------------------------------------------

# 48. ADMINISTRATEURS DE COMMUNAUTÉ

Le propriétaire peut ajouter des administrateurs.

Exemple :

``` text
Jean
role = administrateur
communaute_id = 5
```

Cet administrateur n'a aucun accès aux communautés :

``` text
1
2
3
4
```

------------------------------------------------------------------------

# 49. MODÉRATEURS

Le modérateur peut :

``` text
Voir les signalements
Masquer une publication
Masquer un commentaire
Suspendre un membre
```

Il ne peut pas :

``` text
Supprimer la communauté
Changer le propriétaire
Gérer la facturation globale
```

sauf permission explicitement prévue.

------------------------------------------------------------------------

# 50. ADMINISTRATION PLATEFORME

Le super administrateur peut :

``` text
Voir toutes les communautés
Suspendre une communauté
Réactiver une communauté
Voir les utilisateurs
Voir les statistiques globales
Gérer les plans
Gérer les abonnements
Gérer les signalements globaux
```

Les actions globales doivent être séparées des actions communautaires.

------------------------------------------------------------------------

# 51. SAAS --- ABONNEMENTS

Le système doit être conçu pour pouvoir monétiser Cado.me.

Prévoir les concepts :

``` text
plans
abonnements
paiements
factures
```

## Table `plans`

``` text
plans
├── id
├── nom
├── description
├── prix_mensuel
├── prix_annuel
├── limite_membres
├── limite_stockage
├── limite_formations
├── limite_communautes
├── actif
└── date_creation
```

------------------------------------------------------------------------

# 52. ABONNEMENTS

``` text
abonnements
├── id
├── communaute_id
├── plan_id
├── statut
├── periode_debut
├── periode_fin
├── fournisseur
├── identifiant_externe
├── date_creation
└── date_modification
```

------------------------------------------------------------------------

# 53. PAIEMENTS

``` text
paiements
├── id
├── communaute_id
├── abonnement_id
├── montant
├── devise
├── statut
├── fournisseur
├── identifiant_externe
└── date_creation
```

------------------------------------------------------------------------

# 54. FACTURES

``` text
factures
├── id
├── communaute_id
├── abonnement_id
├── numero
├── montant
├── devise
├── statut
├── date_emission
└── date_echeance
```

------------------------------------------------------------------------

# 55. PAIEMENT

Le fournisseur de paiement doit être abstrait.

Créer :

``` text
PaiementService
```

Puis des implémentations :

``` text
StripePaiement
```

ou autre fournisseur lorsque nécessaire.

Ne pas intégrer un fournisseur directement dans les controllers.

------------------------------------------------------------------------

# 56. V1 SAAS

Le système peut commencer sans paiement réel.

Développement :

``` text
plan gratuit
```

Puis intégrer le paiement en production.

------------------------------------------------------------------------

# 57. LIMITES DES PLANS

Les limites doivent être contrôlées par un service :

``` text
LimitePlanService
```

Exemples :

``` text
nombre maximal de membres
stockage maximal
nombre de formations
nombre d'administrateurs
```

Ne pas dupliquer ces contrôles dans tous les controllers.

------------------------------------------------------------------------

# 58. STOCKAGE ET QUOTAS

Chaque communauté peut avoir un quota.

Exemple :

``` text
Plan Gratuit
1 Go

Plan Pro
50 Go

Plan Business
200 Go
```

Les valeurs réelles doivent être configurables.

------------------------------------------------------------------------

# 59. URL ET SLUGS

Chaque communauté possède :

``` text
slug
```

Exemple :

``` text
/c/academie-business
```

Le slug doit être :

-   unique ;
-   URL-safe ;
-   normalisé ;
-   validé ;
-   protégé contre les slugs réservés.

------------------------------------------------------------------------

# 60. SLUGS RÉSERVÉS

Bloquer notamment :

``` text
admin
api
app
login
connexion
inscription
support
pricing
tarifs
dashboard
settings
parametres
```

------------------------------------------------------------------------

# 61. ISOLATION DES PERMISSIONS

Chaque action sensible doit vérifier :

``` text
utilisateur
+
communaute
+
membre
+
role
+
permission
```

Exemple :

``` text
POST /c/marketing-pro/gestion/formations
```

doit vérifier que l'utilisateur est administrateur de :

``` text
marketing-pro
```

et pas simplement administrateur d'une autre communauté.

------------------------------------------------------------------------

# 62. PROTECTION IDOR

Interdit :

``` text
/c/communaute-a/publications/500
```

permettant de récupérer une publication appartenant à :

``` text
communaute-b
```

Toutes les requêtes par ID doivent inclure le contexte :

``` text
WHERE id = :id
AND communaute_id = :communaute_id
```

------------------------------------------------------------------------

# 63. SÉCURITÉ

Obligatoire :

``` text
CSRF
XSS
SQL Injection
IDOR
Rate limiting
Sessions sécurisées
Validation serveur
Upload sécurisé
Contrôle d'accès
Secrets .env
HTTPS en production
```

------------------------------------------------------------------------

# 64. RATE LIMITING

Prévoir des limites sur :

``` text
Connexion
Inscription
Mot de passe oublié
Publications
Commentaires
Messages
Invitations
Uploads
```

Redis pourra être utilisé plus tard.

Pour une V1 simple, un mécanisme basé MySQL ou session peut être utilisé
si nécessaire.

------------------------------------------------------------------------

# 65. EMAIL

Utiliser :

``` text
Symfony Mailer
```

Local :

``` text
Mailpit
```

Production :

``` text
fournisseur SMTP / API email
```

------------------------------------------------------------------------

# 66. NOTIFICATIONS

Les notifications doivent respecter le contexte :

``` text
communaute_id
```

Un utilisateur peut recevoir :

``` text
Notification communauté A
Notification communauté B
```

L'interface doit indiquer la communauté concernée si nécessaire.

------------------------------------------------------------------------

# 67. MESSAGERIE

La messagerie doit respecter le tenant.

Un utilisateur ne doit pas pouvoir envoyer un message à un utilisateur
d'une autre communauté via une conversation appartenant à sa communauté.

------------------------------------------------------------------------

# 68. RECHERCHE

La recherche V1 doit être limitée à la communauté courante.

Exemple :

``` text
Recherche
↓
WHERE communaute_id = :communaute_id
```

Recherche possible :

``` text
Membres
Publications
Formations
Ressources
Événements
```

------------------------------------------------------------------------

# 69. FEED --- PAGINATION

Le Feed doit utiliser :

``` text
pagination
```

ou :

``` text
chargement progressif
```

Toujours filtré par :

``` text
communaute_id
```

------------------------------------------------------------------------

# 70. PERFORMANCE MULTI-TENANT

Ajouter des index sur :

``` text
communaute_id
```

et fréquemment :

``` text
(communaute_id, date_creation)
(communaute_id, statut)
(communaute_id, utilisateur_id)
```

Selon les requêtes réelles.

------------------------------------------------------------------------

# 71. INDEX

Exemples :

``` text
communautes.slug UNIQUE

membres_communautes(communaute_id, utilisateur_id) UNIQUE

publications(communaute_id, date_creation)

commentaires(communaute_id, publication_id)

likes_publications(communaute_id, publication_id, utilisateur_id) UNIQUE

formations(communaute_id, slug)

evenements(communaute_id, date_debut)
```

------------------------------------------------------------------------

# 72. TRANSACTIONS

Utiliser les transactions pour les opérations multi-tables importantes.

Exemple création de communauté :

``` text
BEGIN
↓
Créer communauté
↓
Créer membre propriétaire
↓
Créer abonnement initial
↓
COMMIT
```

En cas d'erreur :

``` text
ROLLBACK
```

------------------------------------------------------------------------

# 73. SUPPRESSION DE COMMUNAUTÉ

Ne jamais supprimer brutalement une communauté sans stratégie.

Prévoir :

``` text
suspendue
archivee
```

Puis suppression définitive éventuellement asynchrone.

La suppression doit traiter :

``` text
membres
publications
médias
commentaires
formations
ressources
événements
notifications
messages
fichiers
abonnement
```

------------------------------------------------------------------------

# 74. SOFT DELETE

Pour les données sensibles, prévoir éventuellement :

``` text
date_suppression
```

plutôt qu'une suppression immédiate.

À utiliser lorsque nécessaire, pas partout automatiquement.

------------------------------------------------------------------------

# 75. DESIGN

L'interface doit conserver :

``` text
Violet
+
Blanc
+
Sora
+
Lucide
+
Minimalisme
+
Premium
```

La communauté peut éventuellement avoir :

``` text
couleur_principale
couleur_secondaire
logo
image_couverture
```

Mais les personnalisations doivent rester compatibles avec l'identité
Cado.me.

------------------------------------------------------------------------

# 76. PERSONNALISATION COMMUNAUTÉ

Chaque créateur peut personnaliser :

``` text
Nom
Logo
Couverture
Couleur principale
Description
```

Ne pas permettre une personnalisation qui casse l'accessibilité ou
l'identité globale.

------------------------------------------------------------------------

# 77. FRONTEND

Utiliser :

``` text
HTML5
Tailwind CSS
Alpine.js
JavaScript Vanilla
Lucide
```

Pas de SPA.

Le rendu est principalement :

``` text
PHP Server Rendered
```

------------------------------------------------------------------------

# 78. COMPOSANTS RÉUTILISABLES

Créer :

``` text
Bouton
Avatar
Publication
Commentaire
Modal
Dropdown
Toast
Badge
Card
Pagination
Upload
Formulaire
Navigation
Sidebar
```

------------------------------------------------------------------------

# 79. LAYOUT MEMBRE

Desktop :

``` text
┌─────────────────────────────────────────────┐
│ Logo │ Communauté │ Recherche │ Notifs │ Profil │
├────────────┬────────────────────┬───────────┤
│ Navigation │       Feed         │ Sidebar   │
│            │                    │           │
│ Accueil    │ Publication        │ Membres   │
│ Membres    │ Publication        │ Événements│
│ Formation  │ Publication        │           │
│ Ressources │                    │           │
│ Événements │                    │           │
└────────────┴────────────────────┴───────────┘
```

Mobile :

``` text
Header
↓
Feed
↓
Navigation basse
```

------------------------------------------------------------------------

# 80. TABLEAU DE BORD CRÉATEUR

``` text
┌─────────────────────────────────────┐
│ Tableau de bord                     │
├─────────────────────────────────────┤
│ Membres       Publications          │
│ 1 240         3 842                │
│                                     │
│ Activité                            │
│ ███████████████                     │
│                                     │
│ Nouveaux membres                    │
│ Publications récentes              │
└─────────────────────────────────────┘
```

------------------------------------------------------------------------

# 81. AUTHENTIFICATION ET MULTI-COMMUNAUTÉ

Une authentification globale permet à l'utilisateur de se connecter à
Cado.me.

Ensuite :

``` text
Utilisateur
↓
Communautés auxquelles il appartient
↓
Sélection
↓
Contexte communauté
```

Il ne faut pas créer un compte séparé pour chaque communauté.

------------------------------------------------------------------------

# 82. INVITATION ET INSCRIPTION

Deux scénarios :

## Inscription classique

``` text
Cado.me
↓
Créer compte
↓
Rejoindre communauté
```

## Invitation

``` text
Lien invitation
↓
Créer / connecter compte
↓
Accepter invitation
↓
Créer membre_communautes
```

------------------------------------------------------------------------

# 83. TABLEAU DE BORD GLOBAL UTILISATEUR

Prévoir :

``` text
/app
```

avec :

``` text
Mes communautés
Créer une communauté
Activité récente
```

------------------------------------------------------------------------

# 84. CRÉATION DE COMMUNAUTÉ PAR UTILISATEUR

Un utilisateur peut avoir plusieurs communautés si son plan le permet.

Exemple :

``` text
Utilisateur
├── Communauté A
├── Communauté B
└── Communauté C
```

La limite dépend du plan.

------------------------------------------------------------------------

# 85. PLAN ET LIMITES

Le plan peut limiter :

``` text
Nombre de communautés
Nombre de membres
Stockage
Administrateurs
Formations
Ressources
```

Le contrôle doit être centralisé.

------------------------------------------------------------------------

# 86. ABONNEMENT D'UNE COMMUNAUTÉ

L'abonnement est attaché à :

``` text
communaute_id
```

et non directement à l'utilisateur.

Cela permet :

``` text
Propriétaire
↓
Communauté
↓
Abonnement
```

------------------------------------------------------------------------

# 87. CHANGEMENT DE PROPRIÉTAIRE

Le propriétaire peut éventuellement transférer la propriété.

Workflow :

``` text
Propriétaire actuel
↓
Sélection utilisateur
↓
Confirmation
↓
Transaction
↓
Nouveau propriétaire
```

Cette opération doit être fortement protégée.

------------------------------------------------------------------------

# 88. ADMINISTRATION PLATEFORME VS COMMUNAUTÉ

## Plateforme

``` text
/admin
```

voit :

``` text
Toutes les communautés
Tous les utilisateurs
Tous les plans
Tous les abonnements
```

## Communauté

``` text
/c/{slug}/gestion
```

voit uniquement :

``` text
Sa communauté
```

------------------------------------------------------------------------

# 89. STOCKAGE LOCAL

Pour commencer :

``` text
/uploads
```

Aucun R2 obligatoire.

Structure :

``` text
uploads/
├── communautes/
│   └── 12/
├── avatars/
│   └── 12/
├── publications/
│   └── 12/
├── formations/
│   └── 12/
└── ressources/
    └── 12/
```

------------------------------------------------------------------------

# 90. CLOUDFARE R2 EN PRODUCTION

Quand le volume augmente :

``` text
Cado.me
↓
StorageService
↓
Cloudflare R2
```

Le changement doit être principalement :

``` text
.env
```

et non une réécriture du produit.

------------------------------------------------------------------------

# 91. MINIO

MinIO est optionnel.

Il peut reproduire localement le fonctionnement S3 :

``` text
Cado.me
↓
StorageService
↓
MinIO
```

Mais la V1 peut fonctionner avec :

``` text
/uploads
```

------------------------------------------------------------------------

# 92. REDIS

Redis n'est pas obligatoire au démarrage.

Il pourra servir pour :

``` text
Cache
Rate limiting
Sessions
Jobs
Notifications
```

Ne pas ajouter Redis uniquement pour respecter une mode technique.

------------------------------------------------------------------------

# 93. QUEUES / JOBS

À prévoir pour les traitements lourds :

``` text
Emails
Images
Vidéos
Notifications
Suppression de fichiers
Statistiques
```

Une architecture worker pourra être introduite plus tard.

------------------------------------------------------------------------

# 94. OBSERVABILITÉ

Prévoir :

``` text
Logs
Erreurs
Actions administratives
Événements de sécurité
```

Utiliser :

``` text
Monolog
```

si nécessaire.

------------------------------------------------------------------------

# 95. JOURNAL D'AUDIT

Prévoir :

``` text
journaux_audit
```

Structure :

``` text
journaux_audit
├── id
├── communaute_id
├── utilisateur_id
├── action
├── entite
├── entite_id
├── donnees
├── adresse_ip
└── date_creation
```

Les actions sensibles doivent être journalisées.

------------------------------------------------------------------------

# 96. DONNÉES PERSONNELLES

Prévoir les fonctions nécessaires pour :

``` text
Modifier profil
Changer email
Changer mot de passe
Exporter données
Supprimer compte
```

Les données communautaires doivent être traitées avec prudence lors de
la suppression d'un compte.

------------------------------------------------------------------------

# 97. RÈGLE DE CONTEXTE

Le `communaute_id` courant ne doit pas être accepté aveuglément depuis
un champ POST.

Exemple dangereux :

``` text
POST communaute_id = 99
```

Le serveur doit déterminer le contexte depuis :

``` text
URL
+
session
+
appartenance
```

et vérifier les autorisations.

------------------------------------------------------------------------

# 98. RÈGLE DE SÉCURITÉ CRITIQUE

**Ne jamais faire confiance à `communaute_id` fourni par le
navigateur.**

Le serveur doit calculer / résoudre :

``` text
communauté courante
```

puis vérifier :

``` text
utilisateur appartient-il à cette communauté ?
```

------------------------------------------------------------------------

# 99. REQUÊTES SQL

Toujours utiliser :

``` text
PDO
+
requêtes préparées
```

Jamais :

``` php
$sql = "SELECT * FROM utilisateurs WHERE id = " . $_GET['id'];
```

------------------------------------------------------------------------

# 100. XSS

Échapper toutes les données utilisateur.

Les publications ne doivent pas être rendues comme HTML arbitraire.

Si du contenu riche est ajouté :

``` text
sanitization
+
whitelist
```

------------------------------------------------------------------------

# 101. CSRF

Toutes les actions mutantes doivent être protégées :

``` text
POST
PUT
PATCH
DELETE
```

avec token CSRF.

------------------------------------------------------------------------

# 102. UPLOAD

Vérifier :

``` text
Taille
MIME réel
Extension
Contenu
Nom
Destination
```

Limiter les formats.

Exemples images :

``` text
jpg
jpeg
png
webp
```

------------------------------------------------------------------------

# 103. LIMITES D'UPLOAD

Les limites doivent être configurables par plan.

Exemple :

``` text
taille maximale fichier
taille totale stockage
nombre de fichiers
```

------------------------------------------------------------------------

# 104. RESPONSIVE

Obligatoire :

``` text
Mobile
Tablette
Desktop
```

Le Feed doit être parfaitement utilisable sur mobile.

------------------------------------------------------------------------

# 105. ACCESSIBILITÉ

Prévoir :

``` text
Labels
Focus
Contraste
Alt
Navigation clavier
Boutons sémantiques
États accessibles
```

------------------------------------------------------------------------

# 106. ANIMATIONS

Utiliser des transitions légères :

``` text
150ms
200ms
300ms
```

Pas d'animations inutiles.

------------------------------------------------------------------------

# 107. ÉTATS UI

Chaque fonctionnalité interactive doit prévoir :

``` text
Default
Hover
Active
Focus
Disabled
Loading
Error
Success
Empty
```

------------------------------------------------------------------------

# 108. ROUTES GLOBALES

``` text
GET  /
GET  /connexion
POST /connexion
GET  /inscription
POST /inscription
POST /deconnexion

GET  /app
GET  /app/communautes
GET  /app/communautes/creer
POST /app/communautes

GET  /c/{slug}
GET  /c/{slug}/app
```

------------------------------------------------------------------------

# 109. ROUTES COMMUNAUTÉ

``` text
GET  /c/{slug}/app
GET  /c/{slug}/membres
GET  /c/{slug}/membres/{identifiant}

GET  /c/{slug}/formations
GET  /c/{slug}/formations/{formation}

GET  /c/{slug}/ressources
GET  /c/{slug}/evenements

GET  /c/{slug}/notifications
GET  /c/{slug}/messages
GET  /c/{slug}/profil
```

------------------------------------------------------------------------

# 110. ROUTES PUBLICATIONS

``` text
POST /c/{slug}/publications
POST /c/{slug}/publications/{id}/like
POST /c/{slug}/publications/{id}/commentaires
POST /c/{slug}/publications/{id}/enregistrer
POST /c/{slug}/publications/{id}/signaler
```

Chaque route doit vérifier :

``` text
id
+
communaute_id
```

------------------------------------------------------------------------

# 111. ROUTES GESTION

``` text
GET  /c/{slug}/gestion
GET  /c/{slug}/gestion/membres
GET  /c/{slug}/gestion/publications
GET  /c/{slug}/gestion/formations
GET  /c/{slug}/gestion/ressources
GET  /c/{slug}/gestion/evenements
GET  /c/{slug}/gestion/signalements
GET  /c/{slug}/gestion/parametres
```

------------------------------------------------------------------------

# 112. ROUTES PLATEFORME

``` text
GET /admin
GET /admin/communautes
GET /admin/utilisateurs
GET /admin/plans
GET /admin/abonnements
GET /admin/paiements
GET /admin/signalements
```

------------------------------------------------------------------------

# 113. API

Pas d'API publique obligatoire en V1.

Prévoir cependant une architecture permettant plus tard :

``` text
/api/v1
```

Si une API est ajoutée :

``` text
authentification
rate limiting
validation
permissions
tenant context
```

doivent être obligatoires.

------------------------------------------------------------------------

# 114. EMAILS AUTOMATIQUES

Prévoir :

``` text
Bienvenue
Vérification email
Invitation
Mot de passe oublié
Nouveau membre
Notification importante
Paiement
Abonnement
```

------------------------------------------------------------------------

# 115. NOTIFICATIONS COMMUNAUTAIRES

Une action dans :

``` text
Communauté A
```

ne doit pas générer une notification dans :

``` text
Communauté B
```

sauf événement global explicitement prévu.

------------------------------------------------------------------------

# 116. RECHERCHE GLOBALE

La recherche globale Cado.me peut chercher :

``` text
communautés publiques
```

Mais elle ne doit jamais exposer :

``` text
publications privées
membres privés
formations privées
ressources privées
```

d'une communauté.

------------------------------------------------------------------------

# 117. DÉCOUVERTE DES COMMUNAUTÉS

La plateforme peut avoir :

``` text
/c
```

ou une page découverte.

Elle peut afficher uniquement les communautés :

``` text
publiques
actives
```

------------------------------------------------------------------------

# 118. COMMUNAUTÉ PRIVÉE

Une communauté privée ne doit pas exposer ses données au public.

La page publique peut afficher :

``` text
Nom
Logo
Description
Nombre de membres éventuellement
```

Mais pas :

``` text
Feed privé
Liste complète des membres
Ressources privées
Formations privées
Messages
```

sans autorisation.

------------------------------------------------------------------------

# 119. CRÉATEUR

Le propriétaire est responsable de sa communauté.

Il peut :

``` text
Publier
Créer formations
Créer ressources
Créer événements
Inviter membres
Modérer
Gérer administrateurs
Gérer paramètres
```

------------------------------------------------------------------------

# 120. ONBOARDING CRÉATEUR

Workflow recommandé :

``` text
Inscription
↓
Bienvenue
↓
Créer communauté
↓
Nom
↓
Slug
↓
Logo
↓
Description
↓
Inviter membres
↓
Créer première publication
↓
Dashboard
```

------------------------------------------------------------------------

# 121. ONBOARDING MEMBRE

``` text
Invitation / découverte
↓
Inscription
↓
Profil
↓
Rejoindre communauté
↓
Présentation
↓
Feed
```

------------------------------------------------------------------------

# 122. ADMIN DASHBOARD

Le dashboard global peut afficher :

``` text
Communautés actives
Utilisateurs
Nouveaux utilisateurs
Abonnements
Revenus
Stockage
Erreurs
```

Les statistiques globales ne doivent pas être exposées aux propriétaires
de communautés.

------------------------------------------------------------------------

# 123. CACHE

Ne pas ajouter de cache avant besoin réel.

Lorsque nécessaire :

``` text
Redis
```

ou cache fichier/local.

Les clés de cache doivent obligatoirement inclure le contexte
communautaire lorsque la donnée est tenant-specific.

Exemple :

``` text
communaute:12:feed:page:1
```

Jamais :

``` text
feed:page:1
```

si le cache contient des données privées.

------------------------------------------------------------------------

# 124. TESTS MULTI-TENANT

Les tests doivent absolument vérifier l'isolation.

Exemples :

``` text
[ ] Publication A invisible dans B
[ ] Commentaire A inaccessible dans B
[ ] Formation A inaccessible dans B
[ ] Ressource A inaccessible dans B
[ ] Membre A inaccessible dans B
[ ] Admin A incapable d'administrer B
[ ] Message A inaccessible dans B
[ ] Fichier A inaccessible dans B
[ ] Notifications A isolées de B
```

------------------------------------------------------------------------

# 125. TESTS D'AUTORISATION

Tester :

``` text
Membre → action membre
Membre → action admin = refus
Admin A → communauté A = autorisé
Admin A → communauté B = refus
Propriétaire A → communauté A = autorisé
Propriétaire A → communauté B = refus
Super admin → plateforme = autorisé
```

------------------------------------------------------------------------

# 126. TESTS DE SÉCURITÉ

Tester :

``` text
CSRF
XSS
SQL Injection
IDOR
Brute force
Upload malveillant
Accès URL direct
Escalade de privilèges
Cross-tenant access
```

------------------------------------------------------------------------

# 127. ENVIRONNEMENT LOCAL

Aucun service payant n'est nécessaire.

Installer :

``` text
PHP
MySQL
Composer
Git
Node.js
Tailwind
Alpine.js
Mailpit
```

Optionnel :

``` text
Docker
MinIO
Redis
```

------------------------------------------------------------------------

# 128. VARIABLES `.env`

``` text
APP_NAME=Cado.me
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cado_me
DB_USERNAME=root
DB_PASSWORD=

SESSION_NAME=cado_me_session

MAIL_HOST=127.0.0.1
MAIL_PORT=1025

STORAGE_DRIVER=local
STORAGE_PATH=uploads

S3_ENDPOINT=
S3_BUCKET=
S3_ACCESS_KEY=
S3_SECRET_KEY=
S3_REGION=
```

------------------------------------------------------------------------

# 129. GIT

Le `.env` ne doit jamais être commit.

Utiliser :

``` text
.env.example
```

Commits :

``` text
feat:
fix:
refactor:
test:
docs:
chore:
```

------------------------------------------------------------------------

# 130. DÉVELOPPEMENT PAR PHASES

## Phase 1 --- Fondation

``` text
Architecture MVC
Router
PDO
Configuration
.env
Views
Tailwind
Alpine
Git
Migrations
```

## Phase 2 --- Auth

``` text
Inscription
Connexion
Déconnexion
Sessions
CSRF
Email
Reset password
```

## Phase 3 --- Multi-tenancy

``` text
Communautés
Membres communautés
Rôles
Contexte communauté
Middleware
Isolation
```

## Phase 4 --- Feed

``` text
Publications
Images
Vidéos
Fichiers
Commentaires
Likes
Partages
Enregistrements
Signalements
```

## Phase 5 --- Contenus

``` text
Formations
Leçons
Progression
Ressources
```

## Phase 6 --- Événements

``` text
Événements
Calendrier
```

## Phase 7 --- Communication

``` text
Notifications
Messagerie
```

## Phase 8 --- Créateur

``` text
Dashboard
Membres
Contenus
Paramètres
Invitations
```

## Phase 9 --- SaaS

``` text
Plans
Quotas
Abonnements
Paiements
Factures
```

## Phase 10 --- Administration plateforme

``` text
Super admin
Communautés
Utilisateurs
Abonnements
Modération globale
```

## Phase 11 --- Qualité

``` text
Tests
Sécurité
Performance
Accessibilité
Responsive
Logs
```

## Phase 12 --- Production

``` text
VPS
Nginx
PHP-FPM
MySQL
HTTPS
Cloudflare
R2
Email
Backups
Monitoring
```

------------------------------------------------------------------------

# 131. RÈGLE DE DÉVELOPPEMENT DE L'AGENT IA

L'agent doit développer étape par étape.

Pour chaque étape :

1.  annoncer l'objectif ;
2.  créer les fichiers ;
3.  donner le code complet ;
4.  expliquer où placer les fichiers ;
5.  donner les commandes ;
6.  lancer / demander le test ;
7.  corriger les erreurs ;
8.  valider ;
9.  passer à l'étape suivante.

------------------------------------------------------------------------

# 132. NE PAS TOUT CODER EN UNE FOIS

Ne pas produire une architecture gigantesque non testée.

Priorité :

``` text
Fondation
↓
Auth
↓
Multi-tenancy
↓
Feed
↓
Contenus
↓
Administration
↓
SaaS
```

------------------------------------------------------------------------

# 133. RÈGLE DE SIMPLICITÉ

Même si Cado.me devient un SaaS, ne pas sur-architecturer.

La première architecture doit rester :

``` text
Monolithe modulaire
```

et non :

``` text
microservices
```

------------------------------------------------------------------------

# 134. MONOLITHE MODULAIRE

Les modules sont séparés logiquement :

``` text
Authentification
Communautés
Membres
Feed
Contenus
Événements
Communication
Facturation
Administration
```

Mais ils tournent dans :

``` text
une application PHP
```

------------------------------------------------------------------------

# 135. RÈGLE DE RÉUTILISABILITÉ

Les composants suivants doivent être réutilisables :

``` text
Publication
Commentaire
Avatar
Bouton
Modal
Formulaire
Pagination
Notification
Upload
Card
Tableau
Badge
```

------------------------------------------------------------------------

# 136. RÈGLE DE SÉPARATION

``` text
Controller
=
orchestration

Service
=
logique métier

Repository
=
base de données

View
=
présentation

Middleware
=
contrôle accès / contexte
```

------------------------------------------------------------------------

# 137. RÈGLE MULTI-TENANT ABSOLUE

Toute fonctionnalité qui manipule une donnée communautaire doit pouvoir
répondre à :

``` text
Dans quelle communauté suis-je ?
```

Si la réponse est inconnue :

``` text
refuser l'opération
```

------------------------------------------------------------------------

# 138. RÈGLE DE REQUÊTE

Une donnée tenant-specific doit toujours être filtrée par :

``` text
communaute_id
```

ou être obtenue via une relation garantissant cette isolation.

------------------------------------------------------------------------

# 139. RÈGLE DE ROUTE

Une URL comme :

``` text
/c/a/publications/10
```

ne garantit pas que la publication `10` appartient à `a`.

Le backend doit vérifier.

------------------------------------------------------------------------

# 140. RÈGLE DE FICHIERS

Une URL de fichier ne doit pas permettre d'accéder directement à un
fichier d'une autre communauté.

Pour les fichiers privés :

``` text
Controller
↓
Permission
↓
StorageService
↓
fichier
```

------------------------------------------------------------------------

# 141. PRODUCTION

Architecture cible :

``` text
Internet
↓
Cloudflare
↓
Nginx
↓
PHP-FPM
↓
Cado.me
├── MySQL
├── Redis éventuel
├── R2
└── Email
```

------------------------------------------------------------------------

# 142. SAUVEGARDES

En production :

``` text
Backup MySQL
Backup configuration
Backup données critiques
Versionnement code
```

Les fichiers stockés sur R2 doivent avoir une stratégie de récupération
adaptée.

------------------------------------------------------------------------

# 143. DÉPLOIEMENT

Prévoir :

``` text
Git
↓
Serveur
↓
Composer install --no-dev
↓
Configuration .env
↓
Migrations
↓
Permissions
↓
Nginx
↓
PHP-FPM
↓
HTTPS
```

------------------------------------------------------------------------

# 144. HTTPS

Obligatoire en production.

Les cookies doivent utiliser :

``` text
Secure
HttpOnly
SameSite
```

------------------------------------------------------------------------

# 145. DOMAINES

Production possible :

``` text
cado.me
www.cado.me
```

Communautés :

``` text
cado.me/c/slug
```

Future évolution :

``` text
slug.cado.me
```

------------------------------------------------------------------------

# 146. CHECKLIST FONDATION

-   [ ] PHP 8.4+
-   [ ] Composer
-   [ ] MySQL 8+
-   [ ] PDO
-   [ ] MVC
-   [ ] Router
-   [ ] Middleware
-   [ ] Services
-   [ ] Repositories
-   [ ] Views
-   [ ] .env
-   [ ] Git
-   [ ] Tailwind
-   [ ] Alpine
-   [ ] Lucide

------------------------------------------------------------------------

# 147. CHECKLIST MULTI-TENANT

-   [ ] Table communautés
-   [ ] Membres communautés
-   [ ] Rôles
-   [ ] Contexte communauté
-   [ ] Middleware communauté
-   [ ] Isolation SQL
-   [ ] Isolation fichiers
-   [ ] Isolation notifications
-   [ ] Isolation messages
-   [ ] Isolation cache
-   [ ] Tests cross-tenant
-   [ ] Protection IDOR

------------------------------------------------------------------------

# 148. CHECKLIST FEED

-   [ ] Feed par communauté
-   [ ] Publication texte
-   [ ] Image
-   [ ] Plusieurs images
-   [ ] Vidéo
-   [ ] Fichier
-   [ ] Lien
-   [ ] Likes
-   [ ] Commentaires
-   [ ] Réponses
-   [ ] Partages
-   [ ] Enregistrements
-   [ ] Signalements
-   [ ] Pagination

------------------------------------------------------------------------

# 149. CHECKLIST CRÉATEUR

-   [ ] Créer communauté
-   [ ] Modifier communauté
-   [ ] Logo
-   [ ] Couverture
-   [ ] Couleurs
-   [ ] Membres
-   [ ] Invitations
-   [ ] Administrateurs
-   [ ] Modérateurs
-   [ ] Publications
-   [ ] Formations
-   [ ] Ressources
-   [ ] Événements
-   [ ] Dashboard

------------------------------------------------------------------------

# 150. CHECKLIST SAAS

-   [ ] Plans
-   [ ] Limites
-   [ ] Quotas
-   [ ] Abonnement
-   [ ] Paiements
-   [ ] Factures
-   [ ] Statut abonnement
-   [ ] Gestion suspension
-   [ ] Upgrade
-   [ ] Downgrade
-   [ ] Annulation

------------------------------------------------------------------------

# 151. CHECKLIST PRODUCTION

-   [ ] VPS
-   [ ] Nginx
-   [ ] PHP-FPM
-   [ ] MySQL
-   [ ] HTTPS
-   [ ] Cloudflare
-   [ ] R2
-   [ ] Email
-   [ ] Backups
-   [ ] Monitoring
-   [ ] Logs
-   [ ] Firewall
-   [ ] Secrets
-   [ ] Permissions fichiers

------------------------------------------------------------------------

# 152. DÉFINITION DE TERMINÉ

Une fonctionnalité n'est terminée que si :

``` text
[ ] Fonctionnelle
[ ] Sécurisée
[ ] Isolée par communauté
[ ] Validée côté serveur
[ ] Responsive
[ ] Testée
[ ] Gestion erreurs
[ ] Loading
[ ] Empty state
[ ] Code propre
```

------------------------------------------------------------------------

# 153. PRINCIPES ABSOLUS

## 1.

**Cado.me est un SaaS multi-communautés.**

## 2.

**Chaque communauté est un tenant logique.**

## 3.

**Chaque donnée communautaire doit être isolée par `communaute_id`.**

## 4.

**Un utilisateur peut appartenir à plusieurs communautés.**

## 5.

**Le rôle dépend de la communauté.**

## 6.

**Le propriétaire d'une communauté ne peut pas administrer une autre
communauté.**

## 7.

**Le super administrateur de la plateforme peut administrer
l'ensemble.**

## 8.

**PHP reste natif avec une architecture MVC maison.**

## 9.

**MySQL reste la base principale.**

## 10.

**Les noms de la base de données sont en français.**

## 11.

**Le Feed est central.**

## 12.

**Les publications supportent les médias.**

## 13.

**Le stockage est abstrait par `StorageService`.**

## 14.

**Le développement local ne dépend d'aucun service payant.**

## 15.

**R2 intervient uniquement lorsque nécessaire en production.**

## 16.

**Aucune requête tenant-specific ne doit être exécutée sans contexte
communautaire.**

## 17.

**Ne jamais faire confiance au `communaute_id` envoyé par le client.**

## 18.

**Toujours tester les attaques cross-tenant.**

## 19.

**Ne pas sur-architecturer.**

## 20.

**Construire, tester, valider, puis continuer.**

------------------------------------------------------------------------

# 154. VISION FINALE

Cado.me devient :

``` text
UNE PLATEFORME SAAS
        │
        ▼
PLUSIEURS COMMUNAUTÉS
        │
        ├── Communauté A
        │     ├── Feed
        │     ├── Membres
        │     ├── Formations
        │     ├── Ressources
        │     ├── Événements
        │     └── Messages
        │
        ├── Communauté B
        │     ├── Feed
        │     ├── Membres
        │     ├── Formations
        │     ├── Ressources
        │     ├── Événements
        │     └── Messages
        │
        └── Communauté C
              └── ...
```

Le créateur vient sur Cado.me, crée sa communauté, la personnalise,
invite ses membres et utilise les outils de Cado.me pour faire vivre son
espace.

Les membres utilisent un compte Cado.me unique et peuvent appartenir à
plusieurs communautés.

L'ensemble reste :

``` text
Simple
+
Modulaire
+
Sécurisé
+
Multi-tenant
+
Responsive
+
Premium
+
Évolutif
```

------------------------------------------------------------------------

# FIN DU DOCUMENT

**Projet : Cado.me**

**Type : SaaS multi-communautés**

**Architecture : PHP Native MVC --- monolithe modulaire**

**Base : MySQL --- nomenclature française**

**Frontend : HTML + Tailwind CSS + Alpine.js + JavaScript + Lucide**

**Multi-tenancy : base partagée + `communaute_id`**

**Stockage local au démarrage**

**Stockage production : compatible S3 / Cloudflare R2**

**Modèle : plusieurs communautés indépendantes sur une même plateforme**
