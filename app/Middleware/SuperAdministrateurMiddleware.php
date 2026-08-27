<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class SuperAdministrateurMiddleware
{
    public function handle(Request $request): ?Response
    {
        $role = Session::get('role_plateforme');

        if ($role !== 'super_administrateur') {
            Session::flash('error', 'Accès réservé aux super administrateurs.');
            return Response::redirect('/app');
        }

        return null;
    }
}
