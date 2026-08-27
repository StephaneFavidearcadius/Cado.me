<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\PublicationService;

class FeedController extends Controller
{
    public function index(string $slug): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $page = (int) ($_GET['page'] ?? 1);
        $publicationService = new PublicationService();
        $data = $publicationService->feed($communaute['id'], $page);

        return $this->viewCommunity('feed.index', [
            'communaute' => $communaute,
            'publications' => $data['publications'],
            'total' => $data['total'],
            'page' => $data['page'],
            'lastPage' => $data['last_page'],
            'titre' => 'Feed',
        ]);
    }

    public function creer(string $slug): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $data = $_POST;
        $data['images'] = $_FILES['images'] ?? null;
        $data['videos'] = $_FILES['videos'] ?? null;
        $data['fichiers'] = $_FILES['fichiers'] ?? null;

        $publicationService = new PublicationService();
        $resultat = $publicationService->creer($communaute['id'], Session::get('utilisateur_id'), $data);

        if ($resultat['success']) {
            if ((new Request())->isAjax()) {
                return $this->json(['success' => true, 'publication_id' => $resultat['publication_id']]);
            }
            Session::flash('success', 'Publication créée !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de la publication.');
        }

        return $this->redirect("/c/{$slug}/app");
    }

    public function aimer(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $publicationService = new PublicationService();
        $resultat = $publicationService->aimer($communaute['id'], (int)$id, Session::get('utilisateur_id'));

        if ((new Request())->isAjax()) {
            return $this->json($resultat);
        }

        return $this->redirect("/c/{$slug}/app");
    }

    public function commenter(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $contenu = $_POST['contenu'] ?? '';
        $parentId = !empty($_POST['commentaire_parent_id']) ? (int)$_POST['commentaire_parent_id'] : null;

        $commentaireService = new \App\Services\CommentaireService();
        $resultat = $commentaireService->ajouter($communaute['id'], (int)$id, Session::get('utilisateur_id'), $contenu, $parentId);

        if ((new Request())->isAjax()) {
            return $this->json($resultat);
        }

        return $this->redirect("/c/{$slug}/app");
    }

    private function getCommunaute(string $slug): ?array
    {
        $communauteService = new \App\Services\CommunauteService();
        return $communauteService->recupererParSlug($slug);
    }
}
