<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\RateLimitService;

class RateLimitMiddleware
{
    /**
     * Les actions à vérifier en fonction de la route
     */
    private const ROUTE_ACTIONS = [
        'POST:/connexion'                    => 'connexion',
        'POST:/inscription'                  => 'inscription',
        'POST:/mot-de-passe-oublie'          => 'mot_de_passe_oublie',
        'POST:/reinitialiser-mot-de-passe'   => 'mot_de_passe_oublie',
    ];

    public function handle(Request $request): ?Response
    {
        // Le rate limiting ne s'applique qu'aux requêtes POST
        if ($request->method() !== 'POST') {
            return null;
        }

        $method = $request->method();
        $uri = $request->uri();
        $cle = $this->getCleClient();

        // Vérifier les routes POST known
        $routeKey = strtoupper($method) . ':' . $uri;

        foreach (self::ROUTE_ACTIONS as $pattern => $action) {
            if ($routeKey === $pattern || str_starts_with($uri, ltrim($pattern, 'POST:'))) {
                $rateLimit = new RateLimitService();
                if (!$rateLimit->estAutorise($action, $cle)) {
                    Session::flash('error', 'Trop de tentatives. Veuillez patienter quelques minutes.');
                    return Response::back();
                }
                return null;
            }
        }

        // Vérifier les routes de publication / commentaire / message par slug
        if ($method === 'POST') {
            $rateLimit = new RateLimitService();

            if (preg_match('#^/c/[^/]+/publications$#', $uri)) {
                if (!$rateLimit->estAutorise('publication', $cle)) {
                    return $this->tooMany();
                }
            } elseif (preg_match('#^/c/[^/]+/publications/\d+/commentaires$#', $uri)) {
                if (!$rateLimit->estAutorise('commentaire', $cle)) {
                    return $this->tooMany();
                }
            } elseif (preg_match('#^/c/[^/]+/messages#', $uri)) {
                if (!$rateLimit->estAutorise('message', $cle)) {
                    return $this->tooMany();
                }
            } elseif (preg_match('#/gestion/invitations/envoyer', $uri)) {
                if (!$rateLimit->estAutorise('invitation', $cle)) {
                    return $this->tooMany();
                }
            }
        }

        return null;
    }

    private function getCleClient(): string
    {
        // Utiliser l'ID utilisateur si connecté, sinon l'IP
        $userId = Session::get('utilisateur_id');
        if ($userId) {
            return "user:{$userId}";
        }

        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    private function tooMany(): Response
    {
        Session::flash('error', 'Limite de requêtes atteinte. Réessayez dans quelques minutes.');
        return Response::back();
    }
}
