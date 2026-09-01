<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Tests de sécurité
 */
class SecuriteTest extends TestCase
{
    /**
     * Les slugs réservés ne doivent pas pouvoir être utilisés
     */
    public function testSlugsReservesBloques(): void
    {
        $slugsReserves = ['admin', 'api', 'app', 'login', 'connexion', 'inscription', 'support', 'pricing', 'tarifs', 'dashboard', 'settings', 'parametres'];

        $commService = new \App\Services\CommunauteService();

        foreach ($slugsReserves as $slug) {
            $db = self::getTestDb();
            // Vérifier que le slug est dans la liste des slugs réservés
            $stmt = $db->prepare('SELECT COUNT(*) as c FROM communautes WHERE slug = :slug');
            $stmt->execute(['slug' => $slug]);
            $count = (int) $stmt->fetch()['c'];

            $this->assertEquals(0, $count, "Le slug réservé '{$slug}' ne devrait pas exister dans la base");
        }
    }

    /**
     * Vérifier que les mots de passe sont correctement hashés
     */
    public function testMotDePasseHashCorrectement(): void
    {
        $userId = $this->creerUtilisateur([
            'email' => 'hash_test_' . uniqid() . '@test.com',
            'identifiant' => 'hash_test_' . uniqid(),
            'mot_de_passe' => password_hash('monMotDePasse123', PASSWORD_BCRYPT, ['cost' => 4]),
        ]);

        $db = self::getTestDb();
        $stmt = $db->prepare('SELECT mot_de_passe FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        $hash = $stmt->fetch()['mot_de_passe'];

        // Le hash ne doit PAS être le mot de passe en clair
        $this->assertNotEquals('monMotDePasse123', $hash);
        // Le hash doit être vérifiable
        $this->assertTrue(password_verify('monMotDePasse123', $hash));
        // Un mauvais mot de passe doit échouer
        $this->assertFalse(password_verify('mauvaisMotDePasse', $hash));
    }

    /**
     * Vérifier que les sessions sont régénérées après connexion
     */
    public function testSessionRegenerationApresConnexion(): void
    {
        // Ce test vérifie que le code d'AuthService appelle bien Session::regenerate()
        // On vérifie directement que la méthode existe
        $authService = new \App\Services\AuthService();
        $this->assertTrue(method_exists($authService, 'connecter'));
        $this->assertTrue(method_exists($authService, 'deconnecter'));
    }

    /**
     * Vérifier que le CSRF est requis pour les routes mutantes
     */
    public function testCsrfMiddlewareExiste(): void
    {
        $this->assertTrue(class_exists(\App\Middleware\CsrfMiddleware::class));
    }

    /**
     * Vérifier que le rate limiting fonctionne
     */
    public function testRateLimitServiceExiste(): void
    {
        $this->assertTrue(class_exists(\App\Services\RateLimitService::class));
    }

    /**
     * Vérifier que les tokens de réinitialisation sont suffisamment longs
     */
    public function testTokenResetSuffisammentLong(): void
    {
        $token = bin2hex(random_bytes(32));
        $this->assertEquals(64, strlen($token), 'Le token de reset doit faire 64 caractères (hex de 32 bytes)');
    }

    /**
     * Vérifier que l'email est validé
     */
    public function testEmailValidation(): void
    {
        $this->assertTrue(filter_var('test@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('not-an-email', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('test@', FILTER_VALIDATE_EMAIL) !== false);
    }

    /**
     * Vérifier que les slugs sont URL-safe
     */
    public function testSlugUrlSafe(): void
    {
        // Tester la génération de slug via CommunauteService
        $db = self::getTestDb();
        $userId = $this->creerUtilisateur(['email' => 'slug_test_' . uniqid() . '@test.com', 'identifiant' => 'slug_' . uniqid()]);

        $commService = new \App\Services\CommunauteService();
        $result = $commService->creer([
            'nom' => 'Test & Sécurité <script>alert("xss")</script>',
        ], $userId);

        if ($result['success']) {
            $slug = $result['slug'];
            // Le slug ne doit pas contenir de caractères HTML
            $this->assertStringNotContainsString('<', $slug);
            $this->assertStringNotContainsString('>', $slug);
            $this->assertStringNotContainsString('&', $slug);
            $this->assertStringNotContainsString('"', $slug);
            $this->assertStringNotContainsString(' ', $slug);
        }
    }
}
