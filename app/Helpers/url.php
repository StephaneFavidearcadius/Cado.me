<?php

/**
 * Génère une URL avec le base path de l'application
 * Ex: url('/connexion') → /Cado.me/public/connexion
 */
function url(string $path = ''): string
{
    $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Génère une URL relative (sans le base path) pour les formulaires
 * Ex: action_url('/connexion') → /Cado.me/public/connexion
 */
function action_url(string $path = ''): string
{
    return url($path);
}
