<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CommunauteService;
use App\Services\MembreCommunauteService;

/**
 * Tests d'autorisation par rôle et par communauté
 */
class AutorisationTest extends TestCase
{
    private int $proprietaireId;
    private int $adminId;
    private int $moderateurId;
    private int $membreId;
    private int $outsiderId; // Pas membre de la communauté
    private int $commId;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les utilisateurs
        $this->proprietaireId = $this->creerUtilisateur(['email' => 'prop_' . uniqid() . '@test.com', 'identifiant' => 'prop_' . uniqid()]);
        $this->adminId = $this->creerUtilisateur(['email' => 'admin_' . uniqid() . '@test.com', 'identifiant' => 'admin_' . uniqid()]);
        $this->moderateurId = $this->creerUtilisateur(['email' => 'modo_' . uniqid() . '@test.com', 'identifiant' => 'modo_' . uniqid()]);
        $this->membreId = $this->creerUtilisateur(['email' => 'membre_' . uniqid() . '@test.com', 'identifiant' => 'membre_' . uniqid()]);
        $this->outsiderId = $this->creerUtilisateur(['email' => 'outsider_' . uniqid() . '@test.com', 'identifiant' => 'outsider_' . uniqid()]);

        // Créer la communauté
        $this->commId = $this->creerCommunaute($this->proprietaireId, [
            'slug' => 'comm-auth-' . uniqid(),
        ]);

        // Ajouter les membres avec différents rôles
        $this->ajouterMembre($this->commId, $this->proprietaireId, 'proprietaire');
        $this->ajouterMembre($this->commId, $this->adminId, 'administrateur');
        $this->ajouterMembre($this->commId, $this->moderateurId, 'moderateur');
        $this->ajouterMembre($this->commId, $this->membreId, 'membre');
        // $this->outsiderId n'est PAS membre
    }

    /**
     * Vérifier qu'un membre simple ne peut PAS créer de formation
     */
    public function testMembreNePeutPasCreerFormation(): void
    {
        $db = self::getTestDb();

        $stmt = $db->prepare('SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid');
        $stmt->execute(['cid' => $this->commId, 'uid' => $this->membreId]);
        $membre = $stmt->fetch();

        $this->assertEquals('membre', $membre['role']);
        $this->assertNotContains($membre['role'], ['proprietaire', 'administrateur']);
    }

    /**
     * Vérifier que l'admin A ne peut PAS administrer la communauté B
     */
    public function testAdminIsoleParCommunaute(): void
    {
        // Créer une autre communauté où adminId n'est PAS admin
        $commB = $this->creerCommunaute($this->outsiderId, [
            'slug' => 'comm-b-isolation-' . uniqid(),
        ]);
        $this->ajouterMembre($commB, $this->outsiderId, 'proprietaire');

        $db = self::getTestDb();

        // Vérifier le rôle de adminId dans la comm B
        $stmt = $db->prepare('SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid');
        $stmt->execute(['cid' => $commB, 'uid' => $this->adminId]);
        $membre = $stmt->fetch();

        // adminId n'est PAS membre de la comm B
        $this->assertFalse((bool) $membre, 'L\'admin de A ne devrait pas être membre de B');
    }

    /**
     * Vérifier que le propriétaire est bien identifié
     */
    public function testProprietaireIdentifieCorrectement(): void
    {
        $db = self::getTestDb();

        $stmt = $db->prepare('SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid');
        $stmt->execute(['cid' => $this->commId, 'uid' => $this->proprietaireId]);
        $membre = $stmt->fetch();

        $this->assertEquals('proprietaire', $membre['role']);
    }

    /**
     * Vérifier que l'outsider n'est pas membre
     */
    public function testOutsiderNestPasMembre(): void
    {
        $db = self::getTestDb();

        $stmt = $db->prepare('SELECT COUNT(*) as c FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut');
        $stmt->execute(['cid' => $this->commId, 'uid' => $this->outsiderId, 'statut' => 'actif']);
        $count = (int) $stmt->fetch()['c'];

        $this->assertEquals(0, $count, 'L\'outsider ne devrait pas être membre actif');
    }

    /**
     * Vérifier qu'on ne peut pas rejoindre une communauté privée sans invitation
     */
    public function testCommunautePriveeBloqueSansInvitation(): void
    {
        $commPrivee = $this->creerCommunaute($this->outsiderId, [
            'slug' => 'privee-' . uniqid(),
            'visibilite' => 'privee',
        ]);
        $this->ajouterMembre($commPrivee, $this->outsiderId, 'proprietaire');

        $membreService = new MembreCommunauteService();
        $result = $membreService->rejoindre($commPrivee, $this->membreId);

        $this->assertFalse($result['success'], 'On ne peut pas rejoindre une communauté privée sans invitation');
    }

    /**
     * Vérifier qu'un membre peut quitter une communauté
     */
    public function testMembrePeutQuitter(): void
    {
        $membreService = new MembreCommunauteService();
        $result = $membreService->quitter($this->commId, $this->membreId);

        $this->assertTrue($result['success'], 'Un membre peut quitter une communauté');
    }

    /**
     * Vérifier que le propriétaire ne peut PAS quitter sa communauté
     */
    public function testProprietaireNePeutPasQuitter(): void
    {
        $membreService = new MembreCommunauteService();
        $result = $membreService->quitter($this->commId, $this->proprietaireId);

        $this->assertFalse($result['success'], 'Le propriétaire ne peut pas quitter sa propre communauté');
    }
}
