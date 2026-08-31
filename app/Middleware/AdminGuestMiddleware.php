<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdminGuestMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (Session::has('utilisateur_id')) {
            $role = Session::get('role_plateforme');
            if ($role === 'super_administrateur') {
                return Response::redirect('/admin');
            }
            return Response::redirect('/app');
        }

        return null;
    }
}
