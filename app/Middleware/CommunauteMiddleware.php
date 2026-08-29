<?php

namespace App\Middleware;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CommunauteMiddleware
{
    public function handle(Request $request): ?Response
    {
        // Extraire le slug depuis l'URL /c/{slug}/...
        $uri = $request->uri();
        $matches = [];

        if (preg_match('#^/c/([^/]+)#', $uri, $matches)) {
            $slug = $matches[1];

            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM communautes WHERE slug = :slug AND statut = :statut');
            $stmt->execute(['slug' => $slug, 'statut' => 'active']);
            $communaute = $stmt->fetch();

            if (!$communaute) {
                return Response::html(
                    \App\Core\View::make('errors.404'),
                    404
                );
            }

            // Vérifier que l'utilisateur est membre si la communauté est privée
            if ($communaute['visibilite'] === 'privee' && Session::has('utilisateur_id')) {
                $stmt = $db->prepare(
                    'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
                );
                $stmt->execute([
                    'cid' => $communaute['id'],
                    'uid' => Session::get('utilisateur_id'),
                    'statut' => 'actif',
                ]);

                if (!$stmt->fetch()) {
                    Session::flash('error', 'Vous n\'êtes pas membre de cette communauté.');
                    return Response::redirect('/app');
                }
            }

            // Récupérer le rôle de l'utilisateur dans cette communauté
            $role = 'visiteur';
            if (Session::has('utilisateur_id')) {
                $stmtRole = $db->prepare(
                    'SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
                );
                $stmtRole->execute([
                    'cid' => $communaute['id'],
                    'uid' => Session::get('utilisateur_id'),
                    'statut' => 'actif',
                ]);
                $rowRole = $stmtRole->fetch();
                if ($rowRole) {
                    $role = $rowRole['role'];
                }
            }

            // Stocker le contexte communautaire en session avec le rôle
            $contexte = $communaute;
            $contexte['role'] = $role;
            Session::set('communaute_courante', $contexte);
        }

        return null;
    }
}
