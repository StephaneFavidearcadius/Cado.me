<?php

namespace App\Services;

use App\Core\Database;

class FormationService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une formation
     */
    public function creer(int $communauteId, array $data): array
    {
        if (empty($data['titre'])) {
            return ['success' => false, 'errors' => ['Le titre est requis.']];
        }

        $slug = $this->genererSlug($data['titre']);

        $stmt = $this->db->prepare(
            'INSERT INTO formations (communaute_id, titre, slug, description, statut, ordre, date_creation, date_modification)
             VALUES (:cid, :titre, :slug, :description, :statut, :ordre, NOW(), NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'titre' => htmlspecialchars(trim($data['titre'])),
            'slug' => $slug,
            'description' => htmlspecialchars(trim($data['description'] ?? '')),
            'statut' => 'brouillon',
            'ordre' => $data['ordre'] ?? 0,
        ]);

        return ['success' => true, 'formation_id' => $this->db->lastInsertId(), 'slug' => $slug];
    }

    /**
     * Lister les formations d'une communauté
     */
    public function lister(int $communauteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT f.*, COUNT(l.id) as nombre_lecons
             FROM formations f
             LEFT JOIN lecons l ON l.formation_id = f.id AND l.communaute_id = :cid2
             WHERE f.communaute_id = :cid AND f.statut != :statut
             GROUP BY f.id
             ORDER BY f.ordre ASC, f.date_creation DESC'
        );

        $stmt->execute(['cid' => $communauteId, 'cid2' => $communauteId, 'statut' => 'supprimee']);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une formation par slug
     */
    public function recupererParSlug(int $communauteId, string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM formations WHERE communaute_id = :cid AND slug = :slug AND statut != :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'slug' => $slug, 'statut' => 'supprimee']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Ajouter une leçon
     */
    public function ajouterLecon(int $communauteId, int $formationId, array $data): array
    {
        // Vérifier la formation
        $stmt = $this->db->prepare(
            'SELECT id FROM formations WHERE id = :fid AND communaute_id = :cid'
        );
        $stmt->execute(['fid' => $formationId, 'cid' => $communauteId]);

        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Formation introuvable.']];
        }

        $slug = $this->genererSlug($data['titre'] ?? 'lecon');

        $stmt = $this->db->prepare(
            'INSERT INTO lecons (communaute_id, formation_id, titre, slug, description, contenu, video_url, ordre, statut, date_creation)
             VALUES (:cid, :fid, :titre, :slug, :description, :contenu, :video_url, :ordre, :statut, NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'fid' => $formationId,
            'titre' => htmlspecialchars(trim($data['titre'] ?? '')),
            'slug' => $slug,
            'description' => htmlspecialchars(trim($data['description'] ?? '')),
            'contenu' => $data['contenu'] ?? '',
            'video_url' => $data['video_url'] ?? null,
            'ordre' => $data['ordre'] ?? 0,
            'statut' => 'active',
        ]);

        return ['success' => true, 'lecon_id' => $this->db->lastInsertId()];
    }

    /**
     * Lister les leçons d'une formation
     */
    public function listerLecons(int $communauteId, int $formationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM lecons WHERE communaute_id = :cid AND formation_id = :fid AND statut = :statut ORDER BY ordre ASC'
        );
        $stmt->execute(['cid' => $communauteId, 'fid' => $formationId, 'statut' => 'active']);
        return $stmt->fetchAll();
    }

    private function genererSlug(string $titre): string
    {
        $slug = strtolower(trim($titre));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
