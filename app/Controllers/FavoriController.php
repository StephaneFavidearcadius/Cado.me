<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;

class FavoriController extends Controller
{
    public function toggle(string $slug, string $publicationId): Response
    {
        $db = \App\Core\Database::getInstance();
        $userId = Session::get('utilisateur_id');
        $pubId = (int) $publicationId;

        // Vérifier si déjà en favori
        $stmt = $db->prepare('SELECT id FROM favoris_publications WHERE utilisateur_id = :uid AND publication_id = :pid');
        $stmt->execute(['uid' => $userId, 'pid' => $pubId]);

        if ($stmt->fetch()) {
            // Retirer des favoris
            $stmt = $db->prepare('DELETE FROM favoris_publications WHERE utilisateur_id = :uid AND publication_id = :pid');
            $stmt->execute(['uid' => $userId, 'pid' => $pubId]);
            $action = 'remove';
        } else {
            // Ajouter aux favoris
            $stmt = $db->prepare('INSERT INTO favoris_publications (utilisateur_id, publication_id) VALUES (:uid, :pid)');
            $stmt->execute(['uid' => $userId, 'pid' => $pubId]);
            $action = 'add';
        }

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json(['success' => true, 'action' => $action]);
        }

        return $this->redirect("/c/{$slug}/feed");
    }

    public function index(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $db = \App\Core\Database::getInstance();
        $userId = Session::get('utilisateur_id');

        $stmt = $db->prepare(
            'SELECT p.*, u.prenom, u.nom, u.avatar, mc.role as role_utilisateur,
                    (SELECT COUNT(*) FROM likes_publications WHERE publication_id = p.id) as nb_likes,
                    (SELECT COUNT(*) FROM commentaires WHERE publication_id = p.id) as nb_commentaires,
                    1 as est_favori
             FROM favoris_publications f
             JOIN publications p ON p.id = f.publication_id
             JOIN utilisateurs u ON u.id = p.utilisateur_id
             LEFT JOIN membres_communautes mc ON mc.utilisateur_id = p.utilisateur_id AND mc.communaute_id = p.communaute_id
             WHERE f.utilisateur_id = :uid AND p.communaute_id = :cid AND p.statut = :statut
             ORDER BY f.date_creation DESC'
        );
        $stmt->execute(['uid' => $userId, 'cid' => $communaute['id'], 'statut' => 'active']);
        $publications = $stmt->fetchAll();

        // Charger les médias
        foreach ($publications as &$pub) {
            $stmt2 = $db->prepare('SELECT * FROM medias_publications WHERE publication_id = :pid');
            $stmt2->execute(['pid' => $pub['id']]);
            $pub['medias'] = $stmt2->fetchAll();
        }
        unset($pub);

        return $this->viewCommunity('favoris.index', [
            'communaute' => $communaute,
            'publications' => $publications,
            'titre' => 'Mes favoris',
        ]);
    }
}
