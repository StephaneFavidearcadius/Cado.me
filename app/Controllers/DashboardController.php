<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $db = \App\Core\Database::getInstance();
        $uid = Session::get('utilisateur_id');

        // Recharger depuis la DB pour avoir les données à jour
        $stmt = $db->prepare(
            'SELECT mc.*, c.nom, c.slug, c.logo, c.couleur_principale, c.image_couverture
             FROM membres_communautes mc
             JOIN communautes c ON c.id = mc.communaute_id
             WHERE mc.utilisateur_id = :uid AND mc.statut = :statut
             ORDER BY mc.date_adhesion DESC'
        );
        $stmt->execute(['uid' => $uid, 'statut' => 'actif']);
        $mesCommunautes = $stmt->fetchAll();

        // Mettre à jour la session
        Session::set('mes_communautes', $mesCommunautes);

        // Vérifier si l'utilisateur a un abonnement actif (plan payant)
        $stmt = $db->prepare(
            'SELECT a.*, p.nom as plan_nom, p.prix_mensuel, p.limite_communautes
             FROM abonnements a
             JOIN plans p ON p.id = a.plan_id
             WHERE a.statut = :statut AND a.periode_fin >= CURDATE()'
        );
        $stmt->execute(['statut' => 'actif']);
        $abonnement = $stmt->fetch();

        return $this->view('createur.dashboard', [
            'mesCommunautes' => $mesCommunautes,
            'abonnement' => $abonnement,
            'titre' => 'Mon tableau de bord',
        ]);
    }
}
