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

    // ===== FORMATIONS =====

    public function creer(int $communauteId, array $data, ?array $fichierImage = null): array
    {
        if (empty($data['titre'])) {
            return ['success' => false, 'errors' => ['Le titre est requis.']];
        }

        $slug = $this->genererSlug($data['titre']);

        $stmt = $this->db->prepare(
            'INSERT INTO formations (communaute_id, titre, slug, description, image_couverture, statut, ordre, date_creation, date_modification)
             VALUES (:cid, :titre, :slug, :desc, :image, :statut, :ordre, NOW(), NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'titre' => htmlspecialchars(trim($data['titre'])),
            'slug' => $slug,
            'desc' => htmlspecialchars(trim($data['description'] ?? '')),
            'image' => null,
            'statut' => $data['statut'] ?? 'active',
            'ordre' => (int)($data['ordre'] ?? 0),
        ]);
        $formationId = $this->db->lastInsertId();

        // Upload image couverture
        if ($fichierImage && isset($fichierImage['tmp_name']) && is_uploaded_file($fichierImage['tmp_name'])) {
            $extension = strtolower(pathinfo($fichierImage['name'], PATHINFO_EXTENSION));
            $extensionsValides = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $tailleMax = 5 * 1024 * 1024; // 5 Mo

            if (in_array($extension, $extensionsValides) && $fichierImage['size'] <= $tailleMax) {
                $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;
                $dossier = __DIR__ . '/../../public/uploads/' . $communauteId . '/formations/';
                if (!is_dir($dossier)) {
                    mkdir($dossier, 0755, true);
                }
                if (move_uploaded_file($fichierImage['tmp_name'], $dossier . $nomFichier)) {
                    $chemin = 'uploads/' . $communauteId . '/formations/' . $nomFichier;
                    $stmt = $this->db->prepare('UPDATE formations SET image_couverture = :img WHERE id = :id');
                    $stmt->execute(['img' => $chemin, 'id' => $formationId]);
                }
            }
        }

        return ['success' => true, 'formation_id' => $formationId, 'slug' => $slug];
    }

    public function modifier(int $formationId, array $data): array
    {
        $stmt = $this->db->prepare(
            'UPDATE formations SET titre = :titre, description = :desc, statut = :statut, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute([
            'titre' => htmlspecialchars(trim($data['titre'])),
            'desc' => htmlspecialchars(trim($data['description'] ?? '')),
            'statut' => $data['statut'] ?? 'active',
            'id' => $formationId,
        ]);

        return ['success' => true];
    }

    public function supprimer(int $formationId): void
    {
        $stmt = $this->db->prepare('UPDATE formations SET statut = :statut WHERE id = :id');
        $stmt->execute(['statut' => 'supprimee', 'id' => $formationId]);
    }

    public function lister(int $communauteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT f.*,
                    (SELECT COUNT(*) FROM modules_formation WHERE formation_id = f.id) as nb_modules,
                    (SELECT COUNT(*) FROM lecons WHERE formation_id = f.id) as nombre_lecons
             FROM formations f
             WHERE f.communaute_id = :cid AND f.statut != :statut
             ORDER BY f.ordre ASC, f.date_creation DESC'
        );
        $stmt->execute(['cid' => $communauteId, 'statut' => 'supprimee']);
        return $stmt->fetchAll();
    }

    public function recupererParSlug(int $communauteId, string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM formations WHERE communaute_id = :cid AND slug = :slug AND statut != :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'slug' => $slug, 'statut' => 'supprimee']);
        return $stmt->fetch() ?: null;
    }

    // ===== MODULES =====

    public function creerModule(int $formationId, array $data): array
    {
        if (empty($data['titre'])) {
            return ['success' => false, 'errors' => ['Le titre du module est requis.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO modules_formation (formation_id, titre, description, ordre, date_creation)
             VALUES (:fid, :titre, :desc, :ordre, NOW())'
        );
        $stmt->execute([
            'fid' => $formationId,
            'titre' => htmlspecialchars(trim($data['titre'])),
            'desc' => htmlspecialchars(trim($data['description'] ?? '')),
            'ordre' => (int)($data['ordre'] ?? 0),
        ]);

        return ['success' => true, 'module_id' => $this->db->lastInsertId()];
    }

    public function modifierModule(int $moduleId, array $data): array
    {
        $stmt = $this->db->prepare('UPDATE modules_formation SET titre = :titre, description = :desc WHERE id = :id');
        $stmt->execute([
            'titre' => htmlspecialchars(trim($data['titre'])),
            'desc' => htmlspecialchars(trim($data['description'] ?? '')),
            'id' => $moduleId,
        ]);
        return ['success' => true];
    }

    public function supprimerModule(int $moduleId): void
    {
        // Supprimer les leçons liées
        $stmt = $this->db->prepare('UPDATE lecons SET statut = :statut WHERE module_id = :id');
        $stmt->execute(['statut' => 'supprimee', 'id' => $moduleId]);
        // Supprimer le module
        $stmt = $this->db->prepare('DELETE FROM modules_formation WHERE id = :id');
        $stmt->execute(['id' => $moduleId]);
    }

    public function listerModules(int $formationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*,
                    (SELECT COUNT(*) FROM lecons WHERE module_id = m.id AND statut = :statut) as nb_lecons
             FROM modules_formation m
             WHERE m.formation_id = :fid
             ORDER BY m.ordre ASC, m.date_creation ASC'
        );
        $stmt->execute(['fid' => $formationId, 'statut' => 'active']);
        return $stmt->fetchAll();
    }

    public function getModule(int $moduleId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM modules_formation WHERE id = :id');
        $stmt->execute(['id' => $moduleId]);
        return $stmt->fetch() ?: null;
    }

    // ===== LEÇONS =====

    public function ajouterLecon(int $communauteId, int $formationId, array $data, ?array $fichierVideo = null): array
    {
        if (empty($data['titre'])) {
            return ['success' => false, 'errors' => ['Le titre est requis.']];
        }

        $slug = $this->genererSlug($data['titre']);
        $moduleId = !empty($data['module_id']) ? (int)$data['module_id'] : null;

        // Gérer l'upload de vidéo
        $videoFichier = null;
        if ($fichierVideo && isset($fichierVideo['tmp_name']) && is_uploaded_file($fichierVideo['tmp_name'])) {
            $extension = strtolower(pathinfo($fichierVideo['name'], PATHINFO_EXTENSION));
            $extensionsValides = ['mp4', 'webm', 'mov', 'avi', 'mkv'];
            $tailleMax = 100 * 1024 * 1024; // 100 Mo

            if (in_array($extension, $extensionsValides) && $fichierVideo['size'] <= $tailleMax) {
                $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;
                $dossier = __DIR__ . '/../../public/uploads/' . $communauteId . '/videos/lecons/';

                if (!is_dir($dossier)) {
                    mkdir($dossier, 0755, true);
                }

                if (move_uploaded_file($fichierVideo['tmp_name'], $dossier . $nomFichier)) {
                    $videoFichier = 'uploads/' . $communauteId . '/videos/lecons/' . $nomFichier;
                }
            }
        }

        $stmt = $this->db->prepare(
            'INSERT INTO lecons (communaute_id, formation_id, module_id, titre, slug, description, contenu, video_url, video_fichier, ordre, statut, date_creation)
             VALUES (:cid, :fid, :mid, :titre, :slug, :desc, :contenu, :video, :vfichier, :ordre, :statut, NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'fid' => $formationId,
            'mid' => $moduleId,
            'titre' => htmlspecialchars(trim($data['titre'])),
            'slug' => $slug,
            'desc' => htmlspecialchars(trim($data['description'] ?? '')),
            'contenu' => $data['contenu'] ?? '',
            'video' => trim($data['video_url'] ?? '') ?: null,
            'vfichier' => $videoFichier,
            'ordre' => (int)($data['ordre'] ?? 0),
            'statut' => 'active',
        ]);

        return ['success' => true, 'lecon_id' => $this->db->lastInsertId()];
    }

    public function listerLecons(int $communauteId, int $formationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT l.*, m.titre as module_titre
             FROM lecons l
             LEFT JOIN modules_formation m ON m.id = l.module_id
             WHERE l.communaute_id = :cid AND l.formation_id = :fid AND l.statut = :statut
             ORDER BY l.ordre ASC, l.date_creation ASC'
        );
        $stmt->execute(['cid' => $communauteId, 'fid' => $formationId, 'statut' => 'active']);
        return $stmt->fetchAll();
    }

    public function listerLeconsParModule(int $moduleId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM lecons WHERE module_id = :mid AND statut = :statut ORDER BY ordre ASC, date_creation ASC'
        );
        $stmt->execute(['mid' => $moduleId, 'statut' => 'active']);
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
