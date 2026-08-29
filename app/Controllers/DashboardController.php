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

        // Recharger depuis la DB pour avoir les données à jour (logo, cover, etc.)
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

        // Si l'utilisateur a des communautés, rediriger vers le feed de la première
        if (!empty($mesCommunautes)) {
            Session::set('communaute_courante', $mesCommunautes[0]);
            return $this->redirect("/c/{$mesCommunautes[0]['slug']}/feed");
        }

        return $this->view('createur.dashboard', [
            'mesCommunautes' => $mesCommunautes,
            'titre' => 'Mon tableau de bord',
        ]);
    }
}
