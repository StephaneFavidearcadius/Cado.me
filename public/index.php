<?php

/**
 * Cado.me - SaaS Multi-Communautés
 * Point d'entrée principal
 */

// Autoload Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Charger la configuration
\App\Core\Config::load();

// Démarrer la session
\App\Core\Session::start();

// Charger les routes
$router = require dirname(__DIR__) . '/routes/web.php';

// Dispatch la requête
$request = new \App\Core\Request();
$response = $router->dispatch($request);

// Envoyer la réponse
$response->send();
