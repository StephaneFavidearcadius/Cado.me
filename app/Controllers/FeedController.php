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
            'titre' => 'Communauté',
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

        if ((new Request())->isAjax()) {
            if ($resultat['success']) {
                return $this->json(['success' => true, 'publication_id' => $resultat['publication_id']]);
            } else {
                return $this->json(['success' => false, 'errors' => $resultat['errors'] ?? ['Erreur lors de la publication.']], 400);
            }
        }

        if ($resultat['success']) {
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

    public function listerCommentaires(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->json(['error' => 'Not found'], 404);

        $commentaireService = new \App\Services\CommentaireService();
        $commentaires = $commentaireService->lister($communaute['id'], (int)$id);

        return $this->json($commentaires);
    }

    public function supprimer(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $publicationService = new PublicationService();
        $publicationService->supprimer($communaute['id'], (int)$id);

        Session::flash('success', 'Publication supprimée.');
        return $this->redirect("/c/{$slug}/feed");
    }

    public function epingle(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare('SELECT epinglee FROM publications WHERE id = :id AND communaute_id = :cid');
        $stmt->execute(['id' => (int)$id, 'cid' => $communaute['id']]);
        $pub = $stmt->fetch();

        if ($pub) {
            $newState = $pub['epinglee'] ? 0 : 1;
            $stmt = $db->prepare('UPDATE publications SET epinglee = :val WHERE id = :id AND communaute_id = :cid');
            $stmt->execute(['val' => $newState, 'id' => (int)$id, 'cid' => $communaute['id']]);
            Session::flash('success', $newState ? 'Publication épinglée.' : 'Publication désépinglée.');
        }

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json(['success' => true, 'epinglee' => $newState ?? 0]);
        }

        return $this->redirect("/c/{$slug}/feed");
    }

    public function partager(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $utilisateurId = Session::get('utilisateur_id');
        $partageService = new \App\Services\PartageService();

        // Vérifier si déjà partagé pour toggle
        if ($partageService->estPartage($communaute['id'], (int) $id, $utilisateurId)) {
            $resultat = $partageService->annulerPartage($communaute['id'], (int) $id, $utilisateurId);
            $action = 'unshare';
        } else {
            $resultat = $partageService->partager($communaute['id'], (int) $id, $utilisateurId);
            $action = 'share';
        }

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json(['success' => true, 'action' => $action, 'nb_partages' => $resultat['nb_partages'] ?? 0]);
        }

        return $this->redirect("/c/{$slug}/feed");
    }

    public function signaler(string $slug, string $id): Response
    {
        $communaute = $this->getCommunaute($slug);
        if (!$communaute) return $this->view('errors.404', [], 404);

        $motif = $_POST['motif'] ?? '';
        $utilisateurId = Session::get('utilisateur_id');

        $signalementService = new \App\Services\SignalementService();
        $resultat = $signalementService->signalerPublication(
            $communaute['id'],
            $utilisateurId,
            (int) $id,
            $motif
        );

        if ($resultat['success']) {
            if ((new \App\Core\Request())->isAjax()) {
                return $this->json(['success' => true]);
            }
            Session::flash('success', 'Signalement envoyé. Merci.');
        } else {
            if ((new \App\Core\Request())->isAjax()) {
                return $this->json(['success' => false, 'errors' => $resultat['errors']], 422);
            }
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors du signalement.');
        }

        return $this->redirect("/c/{$slug}/feed");
    }

    private function getCommunaute(string $slug): ?array
    {
        $communauteService = new \App\Services\CommunauteService();
        return $communauteService->recupererParSlug($slug);
    }
}
