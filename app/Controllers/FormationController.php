<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\FormationService;

class FormationController extends Controller
{
    // ===== FORMATIONS =====

    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $formationService = new FormationService();
        $formations = $formationService->lister($communaute['id']);

        return $this->viewCommunity('formations.index', [
            'communaute' => $communaute,
            'formations' => $formations,
            'titre' => 'Formations',
        ]);
    }

    public function classroom(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $formationService = new FormationService();
        $formations = $formationService->lister($communaute['id']);

        return $this->viewCommunity('formations.classroom', [
            'communaute' => $communaute,
            'formations' => $formations,
            'titre' => 'Classe',
        ]);
    }

    public function detail(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->view('errors.404', [], 404);

        $modules = $formationService->listerModules($formationData['id']);
        // Charger les leçons pour chaque module
        foreach ($modules as &$mod) {
            $mod['lecons'] = $formationService->listerLeconsParModule($mod['id']);
        }
        unset($mod);

        // Leçons sans module
        $leconsSansModule = $formationService->listerLecons($communaute['id'], $formationData['id']);
        $leconsSansModule = array_filter($leconsSansModule, fn($l) => empty($l['module_id']));

        // Progression de l'utilisateur
        $userId = \App\Core\Session::get('utilisateur_id');
        $progressionService = new \App\Services\ProgressionService();
        $leconsTerminees = $progressionService->getLeconsTerminees($communaute['id'], $userId, $formationData['id']);
        $pourcentage = $progressionService->getPourcentage($communaute['id'], $userId, $formationData['id']);

        return $this->viewCommunity('formations.detail', [
            'communaute' => $communaute,
            'formation' => $formationData,
            'modules' => $modules,
            'lecons' => $leconsSansModule,
            'leconsTerminees' => $leconsTerminees,
            'pourcentage' => $pourcentage,
            'titre' => $formationData['titre'],
        ]);
    }

    public function creer(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $fichierImage = $_FILES['image_couverture'] ?? null;
        $resultat = $formationService->creer($communaute['id'], $_POST, $fichierImage);

        if ($resultat['success']) {
            Session::flash('success', 'Formation créée !');
            return $this->redirect("/c/{$slug}/formations/{$resultat['slug']}");
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        return $this->redirect("/c/{$slug}/formations");
    }

    public function modifier(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->redirect("/c/{$slug}/formations");

        $resultat = $formationService->modifier($formationData['id'], $_POST);

        if ($resultat['success']) {
            Session::flash('success', 'Formation modifiée !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}");
    }

    public function supprimer(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if ($formationData) {
            $formationService->supprimer($formationData['id']);
            Session::flash('success', 'Formation supprimée.');
        }

        return $this->redirect("/c/{$slug}/formations");
    }

    // ===== MODULES =====

    public function creerModule(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->redirect("/c/{$slug}/formations");

        $resultat = $formationService->creerModule($formationData['id'], $_POST);

        if ($resultat['success']) {
            Session::flash('success', 'Module ajouté !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}/modifier");
    }

    public function supprimerModule(string $slug, string $formation, int $moduleId): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationService->supprimerModule($moduleId);
        Session::flash('success', 'Module supprimé.');

        return $this->redirect("/c/{$slug}/formations/{$formation}/modifier");
    }

    // ===== LEÇONS =====

    public function ajouterLecon(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->redirect("/c/{$slug}/formations");

        $fichierVideo = $_FILES['video_fichier'] ?? null;
        $resultat = $formationService->ajouterLecon($communaute['id'], $formationData['id'], $_POST, $fichierVideo);

        if ($resultat['success']) {
            Session::flash('success', 'Leçon ajoutée !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}/modifier");
    }

    public function formulaireModifier(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->redirect("/c/{$slug}/formations");

        $modules = $formationService->listerModules($formationData['id']);
        foreach ($modules as &$mod) {
            $mod['lecons'] = $formationService->listerLeconsParModule($mod['id']);
        }
        unset($mod);

        return $this->viewCommunity('formations.modifier', [
            'communaute' => $communaute,
            'formation' => $formationData,
            'modules' => $modules,
            'titre' => 'Modifier : ' . $formationData['titre'],
        ]);
    }
}
