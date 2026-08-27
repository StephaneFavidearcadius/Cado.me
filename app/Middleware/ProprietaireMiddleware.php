<?php

namespace App\Middleware;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class ProprietaireMiddleware
{
    public function handle(Request $request): ?Response
    {
        $communaute = Session::get('communaute_courante');

        if (!$communaute) {
            return Response::redirect('/app'));
        }

        $utilisateurId = Session::get('utilisateur_id');

        $db = Database::getInstance();
        $stmt = $db->prepare(
            'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND role = :role AND statut = :statut'
        );
        $stmt->execute([
            'cid' => $communaute['id'],
            'uid' => $utilisateurId,
            'role' => 'proprietaire',
            'statut' => 'actif',
        ]);

        if (!$stmt->fetch()) {
            Session::flash('error', 'Vous n\'avez pas les droits de propriétaire pour cette communauté.');
            return Response::redirect("/c/{$communaute['slug']}/app"));
        }

        return null;
    }
}
