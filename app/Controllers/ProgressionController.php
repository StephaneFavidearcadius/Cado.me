<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\ProgressionService;

class ProgressionController extends Controller
{
    /**
     * Marquer une leçon comme terminée
     */
    public function marquerTerminee(string $slug, string $formation, string $lecon): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $progressionService = new ProgressionService();
        $resultat = $progressionService->marquerTerminee(
            $communaute['id'],
            Session::get('utilisateur_id'),
            (int) $lecon
        );

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json([
                'success' => $resultat['success'],
                'pourcentage' => $resultat['pourcentage'] ?? 0,
            ]);
        }

        if ($resultat['success']) {
            Session::flash('success', 'Leçon marquée comme terminée !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}");
    }

    /**
     * Annuler la complétion d'une leçon
     */
    public function annulerTerminee(string $slug, string $formation, string $lecon): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $progressionService = new ProgressionService();
        $resultat = $progressionService->annulerTerminee(
            $communaute['id'],
            Session::get('utilisateur_id'),
            (int) $lecon
        );

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json([
                'success' => $resultat['success'],
                'pourcentage' => $resultat['pourcentage'] ?? 0,
            ]);
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}");
    }
}
