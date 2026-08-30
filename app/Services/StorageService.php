<?php

namespace App\Services;

use App\Core\Config;

class StorageService
{
    private string $driver;
    private string $localPath;

    public function __construct()
    {
        $this->driver = Config::get('storage.driver', 'local');
        $this->localPath = Config::get('storage.local_path', 'uploads');
    }

    /**
     * Stocker un fichier uploadé
     */
    public function stocker(array $fichier, int $communauteId, string $dossier = 'publications'): ?string
    {
        if (!isset($fichier['tmp_name']) || !is_uploaded_file($fichier['tmp_name'])) {
            return null;
        }

        // Valider le fichier
        if (!$this->validerFichier($fichier)) {
            return null;
        }

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $nomStockage = bin2hex(random_bytes(16)) . '.' . $extension;

        // Chemin : /uploads/{communaute_id}/{dossier}/{nom_stockage}
        $chemin = "{$this->localPath}/{$communauteId}/{$dossier}/{$nomStockage}";
        $dossierComplet = dirname($chemin);

        if (!is_dir($dossierComplet)) {
            mkdir($dossierComplet, 0755, true);
        }

        if (!move_uploaded_file($fichier['tmp_name'], $chemin)) {
            return null;
        }

        return $chemin;
    }

    /**
     * Supprimer un fichier
     */
    public function supprimer(string $chemin): bool
    {
        if (file_exists($chemin)) {
            return unlink($chemin);
        }
        return false;
    }

    /**
     * Obtenir l'URL d'accès à un fichier
     */
    public function url(string $chemin): string
    {
        if ($this->driver === 's3') {
            return $chemin;
        }

        // Si le chemin commence par public/, enlever ce préfixe pour l'URL web
        $clean = ltrim($chemin, '/');
        if (str_starts_with($clean, 'public/')) {
            return '/' . substr($clean, 7);
        }
        return '/' . $clean;
    }

    /**
     * Valider un fichier uploadé
     */
    private function validerFichier(array $fichier): bool
    {
        // Taille max (100 Mo)
        if ($fichier['size'] > 100 * 1024 * 1024) {
            return false;
        }

        // Vérifier le MIME type réel
        $info = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($info, $fichier['tmp_name']);
        finfo_close($info);

        $typesAutorises = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'application/pdf',
            'application/zip',
            'video/mp4',
            'video/webm',
            'video/x-matroska',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-flv',
            'video/x-ms-wmv',
            'video/ogg',
            'audio/mpeg',
            'audio/mp3',
            'audio/wav',
            'audio/ogg',
        ];

        return in_array($mimeType, $typesAutorises);
    }

    /**
     * Obtenir l'espace utilisé par une communauté
     */
    public function espaceUtilise(int $communauteId): int
    {
        $dossier = "{$this->localPath}/{$communauteId}";

        if (!is_dir($dossier)) {
            return 0;
        }

        $taille = 0;
        $fichiers = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dossier));

        foreach ($fichiers as $fichier) {
            if ($fichier->isFile()) {
                $taille += $fichier->getSize();
            }
        }

        return $taille;
    }
}
