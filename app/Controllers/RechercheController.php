<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\RechercheService;

class RechercheController extends Controller
{
    /**
     * Recherche dans la communauté (JSON pour AJAX)
     */
    public function rechercher(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->json(['error' => 'Communauté introuvable'], 404);
        }

        $requete = trim($_GET['q'] ?? '');
        if (strlen($requete) < 2) {
            return $this->json(['resultats' => []]);
        }

        $rechercheService = new RechercheService();
        $resultats = $rechercheService->rechercher($communaute['id'], $requete, 15);

        // Formater les résultats pour le frontend
        $formates = [];
        foreach ($resultats as $r) {
            $item = [
                'type' => $r['type'],
                'titre' => $r['titre'] ?? $r['contenu'] ?? $r['prenom'] . ' ' . $r['nom'] ?? '',
            ];

            switch ($r['type']) {
                case 'publication':
                    $item['url'] = "/c/{$slug}/feed#pub-{$r['id']}";
                    $item['soustitre'] = $r['prenom'] . ' ' . $r['nom'];
                    $item['icone'] = 'message-square';
                    break;
                case 'membre':
                    $item['url'] = "/c/{$slug}/membres/" . htmlspecialchars($r['identifiant']);
                    $item['soustitre'] = '@' . $r['identifiant'];
                    $item['icone'] = 'user';
                    $item['avatar'] = $r['avatar'] ?? null;
                    break;
                case 'formation':
                    $item['url'] = "/c/{$slug}/formations/" . htmlspecialchars($r['slug']);
                    $item['soustitre'] = mb_strimwidth($r['description'] ?? '', 0, 80, '...');
                    $item['icone'] = 'book-open';
                    break;
                case 'ressource':
                    $item['url'] = "/c/{$slug}/ressources";
                    $item['soustitre'] = mb_strimwidth($r['description'] ?? '', 0, 80, '...');
                    $item['icone'] = 'folder';
                    break;
                case 'evenement':
                    $item['url'] = "/c/{$slug}/evenements";
                    $item['soustitre'] = isset($r['date_debut']) ? date('d/m/Y', strtotime($r['date_debut'])) : '';
                    $item['icone'] = 'calendar';
                    break;
            }

            $formates[] = $item;
        }

        return $this->json(['resultats' => $formates]);
    }
}
