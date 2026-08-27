<?php

namespace App\Middleware;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdministrateurMiddleware
{
    public function handle(Request $request): ?Response
    {
        $communaute = Session::get('communaute_courante');

        if (!$communaute) {
            return Response::redirect('/app');
        }

        $utilisateurId = Session::get('utilisateur_id');

        $db = Database::getInstance();
        $stmt = $db->prepare(
            'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND role IN (:r1, :r2) AND statut = :statut'
        );
        $stmt->execute([
            'cid' => $communaute['id'],
            'uid' => $utilisateurId,
            'r1' => 'proprietaire',
            'r2' => 'administrateur',
            'statut' => 'actif',
        ]);

        if (!$stmt->fetch()) {
            Session::flash('error', 'Vous n\'avez pas les droits d\'administration pour cette communauté.');
            return Response::redirect("/c/{$communaute['slug']}/app");
        }

        return null;
    }
}
