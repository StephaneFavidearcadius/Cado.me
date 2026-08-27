<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (!Session::has('utilisateur_id')) {
            Session::flash('error', 'Veuillez vous connecter pour accéder à cette page.');
            return Response::redirect('/connexion'));
        }

        return null;
    }
}
