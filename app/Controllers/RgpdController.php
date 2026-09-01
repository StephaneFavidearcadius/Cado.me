<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\RgpdService;

class RgpdController extends Controller
{
    /**
     * Exporter les données de l'utilisateur (JSON)
     */
    public function exporterDonnees(): Response
    {
        $userId = Session::get('utilisateur_id');
        if (!$userId) {
            return $this->redirect('/connexion');
        }

        $rgpdService = new RgpdService();
        $chemin = $rgpdService->genererFichierExport($userId);

        if ($chemin && file_exists($chemin)) {
            $nomFichier = 'cado_me_export_' . date('Y-m-d') . '.json';
            header('Content-Type: application/json');
            header("Content-Disposition: attachment; filename=\"{$nomFichier}\"");
            header('Content-Length: ' . filesize($chemin));
            readfile($chemin);
            unlink($chemin);
            exit;
        }

        Session::flash('error', 'Erreur lors de l\'export.');
        return $this->redirect('/app');
    }

    /**
     * Page de suppression de compte
     */
    public function formulaireSuppression(): Response
    {
        $userId = Session::get('utilisateur_id');
        if (!$userId) {
            return $this->redirect('/connexion');
        }

        // Vérifier si l'utilisateur est propriétaire
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare(
            'SELECT COUNT(*) as c FROM communautes WHERE proprietaire_id = :uid AND statut = :statut'
        );
        $stmt->execute(['uid' => $userId, 'statut' => 'active']);
        $nbProprietaire = (int) $stmt->fetch()['c'];

        return $this->view('rgpd.supprimer', [
            'nbCommunautesPossedees' => $nbProprietaire,
            'titre' => 'Supprimer mon compte',
        ]);
    }

    /**
     * Traiter la suppression de compte
     */
    public function supprimerCompte(): Response
    {
        $userId = Session::get('utilisateur_id');
        if (!$userId) {
            return $this->redirect('/connexion');
        }

        $motDePasse = $_POST['mot_de_passe'] ?? '';
        if (empty($motDePasse)) {
            Session::flash('error', 'Veuillez saisir votre mot de passe.');
            return $this->redirect('/app/compte/supprimer');
        }

        $rgpdService = new RgpdService();
        $resultat = $rgpdService->supprimerCompte($userId, $motDePasse);

        if ($resultat['success']) {
            Session::flash('success', 'Votre compte a été supprimé. À bientôt sur Cado.me.');
            return $this->redirect('/');
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de la suppression.');
        return $this->redirect('/app/compte/supprimer');
    }
}
