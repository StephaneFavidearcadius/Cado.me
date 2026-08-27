<?php

namespace App\Services;

use App\Core\Database;

class LimitePlanService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Vérifier si une action est autorisée selon les limites du plan
     */
    public function estAutorise(int $communauteId, string $type): bool
    {
        $plan = $this->getPlanCommunaute($communauteId);
        if (!$plan) {
            return true; // Pas de plan = pas de limite
        }

        return match ($type) {
            'membres' => $this->compter('membres_communautes', $communauteId) < $plan['limite_membres'],
            'formations' => $this->compter('formations', $communauteId) < $plan['limite_formations'],
            'stockage' => $this->getEspaceUtilise($communauteId) < $plan['limite_stockage'],
            default => true,
        };
    }

    /**
     * Obtenir le plan d'une communauté
     */
    public function getPlanCommunaute(int $communauteId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.* FROM plans p
             JOIN abonnements a ON a.plan_id = p.id
             WHERE a.communaute_id = :cid AND a.statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'statut' => 'actif']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Obtenir l'usage actuel
     */
    public function getUsage(int $communauteId): array
    {
        return [
            'membres' => $this->compter('membres_communautes', $communauteId),
            'formations' => $this->compter('formations', $communauteId),
            'stockage' => $this->getEspaceUtilise($communauteId),
        ];
    }

    private function compter(string $table, int $communauteId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as c FROM {$table} WHERE communaute_id = :cid AND statut IN ('actif', 'active')");
        $stmt->execute(['cid' => $communauteId]);
        return (int) $stmt->fetch()['c'];
    }

    private function getEspaceUtilise(int $communauteId): int
    {
        $storageService = new StorageService();
        return $storageService->espaceUtilise($communauteId);
    }
}
