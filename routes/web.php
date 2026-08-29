<?php

use App\Controllers\AccueilController;
use App\Controllers\AuthController;
use App\Controllers\CommunauteController;
use App\Controllers\DashboardController;
use App\Controllers\FeedController;
use App\Controllers\FormationController;
use App\Controllers\EvenementController;
use App\Controllers\MessageController;
use App\Controllers\MembreController;
use App\Controllers\NotificationController;
use App\Controllers\PlateformeController;
use App\Controllers\ProfilController;
use App\Controllers\RessourceController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CommunauteMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\AdministrateurMiddleware;
use App\Middleware\ProprietaireMiddleware;
use App\Middleware\SuperAdministrateurMiddleware;

$router = new \App\Core\Router();

// ===== ROUTES PUBLIQUES =====
$router->middleware([CsrfMiddleware::class]);
$router->get('/', [AccueilController::class, 'index']);
$router->get('/decouvrir', [AccueilController::class, 'decouvrir']);

// Auth
$router->middleware([GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/connexion', [AuthController::class, 'formulaireConnexion']);
$router->post('/connexion', [AuthController::class, 'connecter']);
$router->get('/inscription', [AuthController::class, 'formulaireInscription']);
$router->post('/inscription', [AuthController::class, 'inscrire']);

// Déconnexion
$router->middleware([CsrfMiddleware::class]);
$router->post('/deconnexion', [AuthController::class, 'deconnecter']);

// ===== DASHBOARD UTILISATEUR =====
$router->middleware([AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/app', [DashboardController::class, 'index']);
$router->get('/app/communautes', [CommunauteController::class, 'liste']);
$router->get('/app/communautes/creer', [CommunauteController::class, 'formulaireCreation']);
$router->post('/app/communautes', [CommunauteController::class, 'creer']);

// ===== COMMUNAUTÉS (publiques, sans auth) =====
$router->middleware([CommunauteMiddleware::class]);
$router->get('/c/{slug}', [CommunauteController::class, 'accueil']);
$router->post('/c/{slug}/rejoindre', [CommunauteController::class, 'rejoindre']);

// ===== COMMUNAUTÉS (auth requise) — Toutes les routes internes =====
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/app', [CommunauteController::class, 'app']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/a-propos', [CommunauteController::class, 'apropos']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/feed', [FeedController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/publications', [FeedController::class, 'creer']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/publications/{id}/like', [FeedController::class, 'aimer']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/publications/{id}/commentaires', [FeedController::class, 'commenter']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/publications/{id}/supprimer', [FeedController::class, 'supprimer']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class]);
$router->get('/c/{slug}/publications/{id}/commentaires', [FeedController::class, 'listerCommentaires']);

// Membres
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/membres', [MembreController::class, 'index']);
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/leaderboards', [MembreController::class, 'leaderboards']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/membres/{identifiant}', [MembreController::class, 'profil']);

// Formations
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/classroom', [FormationController::class, 'classroom']);
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/formations', [FormationController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/formations/{formation}', [FormationController::class, 'detail']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/formations', [FormationController::class, 'creer']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/formations/{formation}/lecons', [FormationController::class, 'ajouterLecon']);

// Ressources
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/ressources', [RessourceController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/ressources', [RessourceController::class, 'creer']);

// Événements
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/evenements', [EvenementController::class, 'index']);
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/calendrier', [EvenementController::class, 'calendrier']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/evenements', [EvenementController::class, 'creer']);

// Notifications
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/notifications', [NotificationController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/notifications/tout-lu', [NotificationController::class, 'marquerToutLu']);

// Messages
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/messages', [MessageController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/messages', [MessageController::class, 'creerConversation']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/messages/{conversation}', [MessageController::class, 'voir']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/messages/{conversation}', [MessageController::class, 'envoyer']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/membres/{membreId}/chat', [MessageController::class, 'ouvrir']);

// Profil
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/profil', [ProfilController::class, 'index']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/profil', [ProfilController::class, 'modifier']);

// ===== GESTION COMMUNAUTÉ (propriétaire/admin) =====
$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/gestion', [CommunauteController::class, 'gestion']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->get('/c/{slug}/gestion/parametres', [CommunauteController::class, 'parametres']);

$router->middleware([AuthMiddleware::class, CommunauteMiddleware::class, AdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->post('/c/{slug}/gestion/parametres', [CommunauteController::class, 'modifierParametres']);

// ===== ADMINISTRATION PLATEFORME =====
$router->middleware([AuthMiddleware::class, SuperAdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin', [PlateformeController::class, 'dashboard']);

$router->middleware([AuthMiddleware::class, SuperAdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/communautes', [PlateformeController::class, 'communautes']);

$router->middleware([AuthMiddleware::class, SuperAdministrateurMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/utilisateurs', [PlateformeController::class, 'utilisateurs']);

return $router;
