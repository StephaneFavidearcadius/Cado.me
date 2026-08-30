<?php

namespace App\Services;

use App\Core\Database;

class EvenementService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer un événement
     */
    public function creer(int $communauteId, array $data): array
    {
        if (empty($data['titre']) || empty($data['date_debut'])) {
            return ['success' => false, 'errors' => ['Le titre et la date de début sont requis.']];
        }

        $slug = $this->genererSlug($data['titre']);

        $stmt = $this->db->prepare(
            'INSERT INTO evenements (communaute_id, titre, slug, description, date_debut, date_fin, type, lien, statut, date_creation, date_modification)
             VALUES (:cid, :titre, :slug, :description, :date_debut, :date_fin, :type, :lien, :statut, NOW(), NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'titre' => trim($data['titre']),
            'slug' => $slug,
            'description' => trim($data['description'] ?? ''),
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'] ?? null,
            'type' => $data['type'] ?? 'autre',
            'lien' => $data['lien'] ?? null,
            'statut' => 'active',
        ]);

        return ['success' => true, 'evenement_id' => $this->db->lastInsertId(), 'slug' => $slug];
    }

    /**
     * Lister les événements d'une communauté
     */
    public function lister(int $communauteId, ?string $filtre = null): array
    {
        $sql = 'SELECT * FROM evenements WHERE communaute_id = :cid AND statut = :statut';
        $params = ['cid' => $communauteId, 'statut' => 'active'];

        if ($filtre === 'a_venir') {
            $sql .= ' AND date_debut >= NOW()';
        } elseif ($filtre === 'passe') {
            $sql .= ' AND date_debut < NOW()';
        }

        $sql .= ' ORDER BY date_debut ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un événement par slug
     */
    public function recupererParSlug(int $communauteId, string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM evenements WHERE communaute_id = :cid AND slug = :slug AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'slug' => $slug, 'statut' => 'active']);
        return $stmt->fetch() ?: null;
    }

    private function genererSlug(string $titre): string
    {
        $slug = strtolower(trim($titre));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
