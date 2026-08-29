<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\MembreCommunauteService;

class MembreController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $membreService = new MembreCommunauteService();
        $membres = $membreService->lister($communaute['id']);

        return $this->viewCommunity('membres.index', [
            'communaute' => $communaute,
            'membres' => $membres,
            'titre' => 'Membres',
        ]);
    }

    public function leaderboards(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare(
            'SELECT u.id, u.prenom, u.nom, u.avatar, u.identifiant,
                    (SELECT COUNT(*) FROM publications WHERE utilisateur_id = u.id AND communaute_id = :cid1 AND statut = :statut) as nb_pubs,
                    (SELECT COUNT(*) FROM commentaires WHERE utilisateur_id = u.id AND communaute_id = :cid2 AND statut = :statut_c) as nb_comments,
                    (SELECT COUNT(*) FROM likes_publications WHERE utilisateur_id = u.id AND communaute_id = :cid3) as nb_likes
             FROM membres_communautes mc
             JOIN utilisateurs u ON u.id = mc.utilisateur_id
             WHERE mc.communaute_id = :cid4 AND mc.statut = :statut2
             ORDER BY (nb_pubs * 3 + nb_comments * 2 + nb_likes) DESC
             LIMIT 50'
        );
        $stmt->execute([
            'cid1' => $communaute['id'], 'cid2' => $communaute['id'], 'cid3' => $communaute['id'], 'cid4' => $communaute['id'],
            'statut' => 'active', 'statut_c' => 'actif', 'statut2' => 'actif'
        ]);
        $classement = $stmt->fetchAll();

        // Ajouter les points
        foreach ($classement as &$m) {
            $m['points'] = ($m['nb_pubs'] * 3) + ($m['nb_comments'] * 2) + $m['nb_likes'];
        }
        unset($m);

        return $this->viewCommunity('membres.leaderboards', [
            'communaute' => $communaute,
            'classement' => $classement,
            'titre' => 'Classement',
        ]);
    }

    public function profil(string $slug, string $identifiant): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        // Récupérer l'utilisateur par identifiant
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE identifiant = :identifiant');
        $stmt->execute(['identifiant' => $identifiant]);
        $membre = $stmt->fetch();

        if (!$membre) {
            return $this->view('errors.404', [], 404);
        }

        return $this->viewCommunity('membres.profil', [
            'communaute' => $communaute,
            'membre' => $membre,
            'titre' => "{$membre['prenom']} {$membre['nom']}",
        ]);
    }
}
