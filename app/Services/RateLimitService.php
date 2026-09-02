<?php

namespace App\Services;

use App\Core\Database;

class RateLimitService
{
    private \PDO $db;

    /**
     * Limites par action : [action => [max_tentatives, fenetre_en_secondes]]
     */
    private const LIMITES = [
        'connexion'            => [5, 300],      // 5 tentatives / 5 min
        'inscription'          => [3, 3600],     // 3 / heure
        'mot_de_passe_oublie'  => [3, 3600],     // 3 / heure
        'publication'          => [10, 600],     // 10 / 10 min
        'commentaire'          => [20, 600],     // 20 / 10 min
        'message'              => [30, 600],     // 30 / 10 min
        'invitation'           => [10, 3600],    // 10 / heure
        'upload'               => [20, 3600],    // 20 / heure
    ];

    private bool $available = false;

    public function __construct()
    {
        try {
            $this->db = Database::getInstance();
            // Vérifier que la table existe
            $this->db->query('SELECT 1 FROM rate_limits LIMIT 0');
            $this->available = true;
        } catch (\PDOException \Exception) {
            // Table rate_limits inexistante — on désactive le rate limiting
            $this->available = false;
        }
    }

    /**
     * Vérifier si l'action est autorisée
     */
    public function estAutorise(string $action, string $cle): bool
    {
        if (!$this->available) {
            return true; // Pas de table = pas de limite
        }

        $limite = self::LIMITES[$action] ?? [60, 600]; // défaut : 60/10min
        $max = $limite[0];
        $fenetre = $limite[1];

        $cleComplete = "{$action}:{$cle}";

        // Nettoyer les anciennes entrées
        $this->nettoyer($cleComplete, $fenetre);

        // Compter les tentatives récentes
        $stmt = $this->db->prepare(
            'SELECT tentatives FROM rate_limits WHERE cle = :cle AND date_premiere >= DATE_SUB(NOW(), INTERVAL :fenetre SECOND)'
        );
        $stmt->execute(['cle' => $cleComplete, 'fenetre' => $fenetre]);
        $row = $stmt->fetch();

        if ($row && (int) $row['tentatives'] >= $max) {
            return false;
        }

        // Incrémenter
        if ($row) {
            $stmt = $this->db->prepare(
                'UPDATE rate_limits SET tentatives = tentatives + 1, date_derniere = NOW() WHERE cle = :cle'
            );
            $stmt->execute(['cle' => $cleComplete]);
        } else {
            $stmt = $this->db->prepare(
                'INSERT INTO rate_limits (cle, tentatives, date_premiere, date_derniere) VALUES (:cle, 1, NOW(), NOW())'
            );
            $stmt->execute(['cle' => $cleComplete]);
        }

        return true;
    }

    /**
     * Vérifier sans incrémenter (lecture seule)
     */
    public function estLimite(string $action, string $cle): bool
    {
        return !$this->estAutorise($action, $cle);
    }

    /**
     * Obtenir le nombre de tentatives restantes
     */
    public function restantes(string $action, string $cle): int
    {
        if (!$this->available) {
            return 999;
        }

        $limite = self::LIMITES[$action] ?? [60, 600];
        $max = $limite[0];
        $fenetre = $limite[1];

        $cleComplete = "{$action}:{$cle}";

        $stmt = $this->db->prepare(
            'SELECT tentatives FROM rate_limits WHERE cle = :cle AND date_premiere >= DATE_SUB(NOW(), INTERVAL :fenetre SECOND)'
        );
        $stmt->execute(['cle' => $cleComplete, 'fenetre' => $fenetre]);
        $row = $stmt->fetch();

        $utilise = $row ? (int) $row['tentatives'] : 0;
        return max(0, $max - $utilise);
    }

    /**
     * Nettoyer les entrées expirées
     */
    private function nettoyer(string $cle, int $fenetre): void
    {
        if (!$this->available) {
            return;
        }

        $stmt = $this->db->prepare(
            'DELETE FROM rate_limits WHERE cle = :cle AND date_premiere < DATE_SUB(NOW(), INTERVAL :fenetre SECOND)'
        );
        $stmt->execute(['cle' => $cle, 'fenetre' => $fenetre]);
    }

    /**
     * Nettoyer toutes les entrées expirées (à lancer en tâche cron)
     */
    public function nettoyerTout(): int
    {
        $maxFenetre = 0;
        foreach (self::LIMITES as $limite) {
            $maxFenetre = max($maxFenetre, $limite[1]);
        }

        $stmt = $this->db->prepare(
            'DELETE FROM rate_limits WHERE date_premiere < DATE_SUB(NOW(), INTERVAL :fenetre SECOND)'
        );
        $stmt->execute(['fenetre' => $maxFenetre + 60]);
        return $stmt->rowCount();
    }
}
