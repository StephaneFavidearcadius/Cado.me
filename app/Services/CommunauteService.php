<?php

namespace App\Services;

use App\Core\Config;
use App\Core\Database;
use App\Core\Session;

class CommunauteService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une nouvelle communauté
     */
    public function creer(array $data, int $proprietaireId): array
    {
        $errors = $this->validerCreation($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->db->beginTransaction();

            // 1. Créer la communauté
            $slug = $this->genererSlug($data['nom']);

            $stmt = $this->db->prepare(
                'INSERT INTO communautes (proprietaire_id, nom, slug, description, couleur_principale, statut, visibilite, date_creation, date_modification)
                 VALUES (:proprietaire_id, :nom, :slug, :description, :couleur_principale, :statut, :visibilite, NOW(), NOW())'
            );

            $stmt->execute([
                'proprietaire_id' => $proprietaireId,
                'nom' => htmlspecialchars(trim($data['nom'])),
                'slug' => $slug,
                'description' => htmlspecialchars(trim($data['description'] ?? '')),
                'couleur_principale' => $data['couleur_principale'] ?? '#7830E0',
                'statut' => 'active',
                'visibilite' => $data['visibilite'] ?? 'privee',
            ]);

            $communauteId = $this->db->lastInsertId();

            // 2. Ajouter le propriétaire comme membre
            $stmt = $this->db->prepare(
                'INSERT INTO membres_communautes (communaute_id, utilisateur_id, role, statut, date_adhesion, date_modification)
                 VALUES (:cid, :uid, :role, :statut, NOW(), NOW())'
            );

            $stmt->execute([
                'cid' => $communauteId,
                'uid' => $proprietaireId,
                'role' => 'proprietaire',
                'statut' => 'actif',
            ]);

            // 3. Créer l'abonnement gratuit par défaut
            // Plan gratuit par défaut (plan_id = 1)
            $stmt = $this->db->prepare(
                'INSERT INTO abonnements (communaute_id, plan_id, statut, periode_debut, periode_fin, date_creation, date_modification)
                 VALUES (:cid, 1, :statut, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), NOW(), NOW())'
            );
            $stmt->execute([
                'cid' => $communauteId,
                'statut' => 'actif',
            ]);

            $this->db->commit();

            // Recharger les communautés en session directement
            $stmt = $this->db->prepare(
                'SELECT mc.*, c.nom, c.slug, c.logo, c.couleur_principale
                 FROM membres_communautes mc
                 JOIN communautes c ON c.id = mc.communaute_id
                 WHERE mc.utilisateur_id = :uid AND mc.statut = :statut
                 ORDER BY mc.date_adhesion DESC'
            );
            $stmt->execute(['uid' => $proprietaireId, 'statut' => 'actif']);
            Session::set('mes_communautes', $stmt->fetchAll());

            return ['success' => true, 'communaute_id' => $communauteId, 'slug' => $slug];

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Récupérer une communauté par son slug
     */
    public function recupererParSlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM communautes WHERE slug = :slug AND statut = :statut');
        $stmt->execute(['slug' => $slug, 'statut' => 'active']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Récupérer les communautés publiques
     */
    public function recupererPubliques(int $page = 1, int $perPage = 12): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT c.*, COUNT(mc.id) as nombre_membres
             FROM communautes c
             LEFT JOIN membres_communautes mc ON mc.communaute_id = c.id AND mc.statut = :statut_membre
             WHERE c.visibilite = :visibilite AND c.statut = :statut
             GROUP BY c.id
             ORDER BY nombre_membres DESC
             LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':statut_membre', 'actif');
        $stmt->bindValue(':visibilite', 'publique');
        $stmt->bindValue(':statut', 'active');
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Modifier une communauté
     */
    public function modifier(int $communauteId, array $data): array
    {
        $champs = [];
        $params = ['cid' => $communauteId];

        if (isset($data['nom'])) {
            $champs[] = 'nom = :nom';
            $params['nom'] = htmlspecialchars(trim($data['nom']));
        }
        if (isset($data['description'])) {
            $champs[] = 'description = :description';
            $params['description'] = htmlspecialchars(trim($data['description']));
        }
        if (isset($data['couleur_principale'])) {
            $champs[] = 'couleur_principale = :couleur_principale';
            $params['couleur_principale'] = $data['couleur_principale'];
        }
        if (isset($data['visibilite'])) {
            $champs[] = 'visibilite = :visibilite';
            $params['visibilite'] = $data['visibilite'];
        }

        if (empty($champs)) {
            return ['success' => false, 'errors' => ['Aucun champ à modifier.']];
        }

        $champs[] = 'date_modification = NOW()';
        $sql = 'UPDATE communautes SET ' . implode(', ', $champs) . ' WHERE id = :cid';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return ['success' => true];
    }

    private function validerCreation(array $data): array
    {
        $errors = [];

        if (empty($data['nom']) || strlen(trim($data['nom'])) < 3) {
            $errors['nom'] = 'Le nom doit contenir au moins 3 caractères.';
        } elseif (strlen(trim($data['nom'])) > 100) {
            $errors['nom'] = 'Le nom ne peut pas dépasser 100 caractères.';
        }

        if (isset($data['nom'])) {
            $slug = $this->genererSlug($data['nom']);
            if ($this->slugExiste($slug)) {
                $errors['nom'] = 'Ce nom génère un slug déjà utilisé.';
            }
        }

        return $errors;
    }

    private function genererSlug(string $nom): string
    {
        $slug = strtolower(trim($nom));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    private function slugExiste(string $slug): bool
    {
        $slugsReserves = ['admin', 'api', 'app', 'login', 'connexion', 'inscription', 'support', 'pricing', 'tarifs', 'dashboard', 'settings', 'parametres'];

        if (in_array($slug, $slugsReserves)) {
            return true;
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) as c FROM communautes WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        return (int) $stmt->fetch()['c'] > 0;
    }
}
