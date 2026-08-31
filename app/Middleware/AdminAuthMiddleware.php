<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdminAuthMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (!Session::has('utilisateur_id')) {
            Session::flash('error', 'Veuillez vous connecter pour acceder a l\'administration.');
            return Response::redirect('/admin/connexion');
        }

        return null;
    }
}
