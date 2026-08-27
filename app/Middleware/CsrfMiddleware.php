<?php

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request->input('_token') ?? $request->header('X-CSRF-Token');

            if (!Csrf::verify($token)) {
                Session::flash('error', 'Token de sécurité invalide. Veuillez réessayer.');
                return Response::redirect($request->header('HTTP_REFERER', '/'));
            }
        }

        return null;
    }
}
