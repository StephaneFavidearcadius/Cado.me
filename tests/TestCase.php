<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static ?\PDO $testDb = null;

    /**
     * Obtenir une connexion à la base de test
     */
    protected static function getTestDb(): \PDO
    {
        if (self::$testDb === null) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $dbName = $_ENV['DB_DATABASE'] ?? 'cado_me_test';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';

            self::$testDb = new \PDO(
                "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
                $user,
                $pass,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        }

        return self::$testDb;
    }

    /**
     * Nettoyer la base de test avant chaque test
     */
    protected function truncateTables(): void
    {
        $db = self::getTestDb();
        $db->exec('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'journaux_audit', 'rate_limits', 'publications_enregistrees', 'favoris_publications',
            'medias_messages', 'partages_publications', 'signalements', 'invitations_communautes',
            'notifications', 'messages', 'participants_conversations', 'conversations',
            'likes_publications', 'commentaires', 'medias_publications', 'publications',
            'progression_formations', 'lecons', 'modules_formation', 'formations',
            'ressources', 'evenements', 'abonnements', 'plans',
            'membres_communautes', 'communautes', 'utilisateurs',
        ];

        foreach ($tables as $table) {
            $db->exec("DELETE FROM `{$table}`");
        }

        $db->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Créer un utilisateur de test
     */
    protected function creerUtilisateur(array $overrides = []): int
    {
        $db = self::getTestDb();
        $data = array_merge([
            'prenom' => 'Test',
            'nom' => 'User',
            'identifiant' => 'testuser' . uniqid(),
            'email' => 'test' . uniqid() . '@example.com',
            'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 4]),
            'role_plateforme' => 'aucun',
            'statut' => 'actif',
            'email_verifie' => 1,
        ], $overrides);

        $stmt = $db->prepare(
            'INSERT INTO utilisateurs (prenom, nom, identifiant, email, mot_de_passe, role_plateforme, statut, email_verifie, date_creation, date_modification)
             VALUES (:prenom, :nom, :identifiant, :email, :mdp, :role, :statut, :verifie, NOW(), NOW())'
        );
        $stmt->execute([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'identifiant' => $data['identifiant'],
            'email' => $data['email'],
            'mdp' => $data['mot_de_passe'],
            'role' => $data['role_plateforme'],
            'statut' => $data['statut'],
            'verifie' => $data['email_verifie'],
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Créer une communauté de test
     */
    protected function creerCommunaute(int $proprietaireId, array $overrides = []): int
    {
        $db = self::getTestDb();
        $data = array_merge([
            'nom' => 'Communauté Test',
            'slug' => 'communaute-test-' . uniqid(),
            'statut' => 'active',
            'visibilite' => 'publique',
            'couleur_principale' => '#7830E0',
        ], $overrides);

        $stmt = $db->prepare(
            'INSERT INTO communautes (proprietaire_id, nom, slug, statut, visibilite, couleur_principale, date_creation, date_modification)
             VALUES (:pid, :nom, :slug, :statut, :visib, :couleur, NOW(), NOW())'
        );
        $stmt->execute([
            'pid' => $proprietaireId,
            'nom' => $data['nom'],
            'slug' => $data['slug'],
            'statut' => $data['statut'],
            'visib' => $data['visibilite'],
            'couleur' => $data['couleur_principale'],
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Ajouter un membre à une communauté
     */
    protected function ajouterMembre(int $communauteId, int $utilisateurId, string $role = 'membre'): void
    {
        $db = self::getTestDb();
        $stmt = $db->prepare(
            'INSERT INTO membres_communautes (communaute_id, utilisateur_id, role, statut, date_adhesion, date_modification)
             VALUES (:cid, :uid, :role, :statut, NOW(), NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'role' => $role,
            'statut' => 'actif',
        ]);
    }
}
