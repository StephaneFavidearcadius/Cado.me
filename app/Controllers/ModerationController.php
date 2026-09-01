<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\SignalementService;

class ModerationController extends Controller
{
    /**
     * Liste des signalements
     */
    public function index(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $signalementService = new SignalementService();
        $filtre = $_GET['filtre'] ?? null;
        $signalements = $signalementService->lister($communaute['id'], $filtre);
        $nbEnAttente = $signalementService->compterEnAttente($communaute['id']);

        return $this->viewCommunity('moderation.index', [
            'communaute' => $communaute,
            'signalements' => $signalements,
            'nbEnAttente' => $nbEnAttente,
            'filtre' => $filtre,
            'titre' => "Modération - {$communaute['nom']}",
        ]);
    }

    /**
     * Traiter un signalement (masquer ou rejeter)
     */
    public function traiter(string $slug, string $id): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $decision = $_POST['decision'] ?? '';
        if (!in_array($decision, ['traite', 'rejete'])) {
            Session::flash('error', 'Décision invalide.');
            return $this->redirect("/c/{$slug}/gestion/moderation");
        }

        $signalementService = new SignalementService();
        $resultat = $signalementService->traiter($communaute['id'], (int) $id, $decision);

        if ($resultat['success']) {
            $msg = $decision === 'traite' ? 'Signalement traité. Contenu masqué.' : 'Signalement rejeté.';
            Session::flash('success', $msg);
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors du traitement.');
        }

        return $this->redirect("/c/{$slug}/gestion/moderation");
    }
}
