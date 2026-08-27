<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class GuestMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (Session::has('utilisateur_id')) {
            return Response::redirect('/app'));
        }

        return null;
    }
}
