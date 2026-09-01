<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\InvitationService;

class InvitationServiceTest extends TestCase
{
    private int $proprietaireId;
    private int $membreId;
    private int $commId;
    private InvitationService $invitationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invitationService = new InvitationService();

        $this->proprietaireId = $this->creerUtilisateur(['email' => 'prop_invite_' . uniqid() . '@test.com', 'identifiant' => 'prop_invite_' . uniqid()]);
        $this->membreId = $this->creerUtilisateur(['email' => 'membre_invite_' . uniqid() . '@test.com', 'identifiant' => 'membre_invite_' . uniqid()]);

        $this->commId = $this->creerCommunaute($this->proprietaireId, ['slug' => 'comm-invite-' . uniqid()]);
        $this->ajouterMembre($this->commId, $this->proprietaireId, 'proprietaire');
    }

    public function testEnvoyerInvitation(): void
    {
        $result = $this->invitationService->envoyer($this->commId, 'invitee@example.com');

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['token']);
        $this->assertEquals(64, strlen($result['token']));
    }

    public function testInvitationEmailInvalide(): void
    {
        $result = $this->invitationService->envoyer($this->commId, 'pas-un-email');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function testInvitationDejaMembre(): void
    {
        $result = $this->invitationService->envoyer($this->commId, 'membre_deja_actif@example.com');

        // Créer d'abord l'utilisateur avec cet email
        $userId = $this->creerUtilisateur([
            'email' => 'membre_deja_actif@example.com',
            'identifiant' => 'membre_deja_' . uniqid(),
        ]);
        $this->ajouterMembre($this->commId, $userId, 'membre');

        $result = $this->invitationService->envoyer($this->commId, 'membre_deja_actif@example.com');

        $this->assertFalse($result['success']);
    }

    public function testDoublonInvitation(): void
    {
        // Première invitation
        $this->invitationService->envoyer($this->commId, 'doublon@example.com');

        // Deuxième invitation même email
        $result = $this->invitationService->envoyer($this->commId, 'doublon@example.com');

        $this->assertFalse($result['success']);
    }

    public function testListerInvitations(): void
    {
        $this->invitationService->envoyer($this->commId, 'list1@example.com');
        $this->invitationService->envoyer($this->commId, 'list2@example.com');

        $invitations = $this->invitationService->lister($this->commId);

        $this->assertCount(2, $invitations);
    }

    public function testSupprimerInvitation(): void
    {
        $result = $this->invitationService->envoyer($this->commId, 'supprimable@example.com');

        $db = self::getTestDb();
        $stmt = $db->prepare('SELECT id FROM invitations_communautes WHERE token = :token');
        $stmt->execute(['token' => $result['token']]);
        $invitationId = (int) $stmt->fetch()['id'];

        $supprime = $this->invitationService->supprimer($this->commId, $invitationId);
        $this->assertTrue($supprime);

        // Vérifier qu'elle n'existe plus
        $stmt->execute(['token' => $result['token']]);
        $this->assertFalse((bool) $stmt->fetch());
    }

    public function testAccepterInvitation(): void
    {
        $result = $this->invitationService->envoyer($this->commId, 'accepteur@example.com');

        // Créer l'utilisateur avec cet email
        $userId = $this->creerUtilisateur([
            'email' => 'accepteur@example.com',
            'identifiant' => 'accepteur_' . uniqid(),
        ]);

        $accepte = $this->invitationService->accepter($result['token'], $userId);

        $this->assertTrue($accepte['success']);
        $this->assertEquals($this->commId, $accepte['communaute_id'] ?? null);

        // Vérifier que l'utilisateur est maintenant membre
        $db = self::getTestDb();
        $stmt = $db->prepare('SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid');
        $stmt->execute(['cid' => $this->commId, 'uid' => $userId]);
        $membre = $stmt->fetch();

        $this->assertNotNull($membre);
        $this->assertEquals('membre', $membre['role']);
    }

    public function testTokenInvalide(): void
    {
        $result = $this->invitationService->accepter('token_inexistant_12345', 1);

        $this->assertFalse($result['success']);
    }

    public function testCompterEnAttente(): void
    {
        $this->invitationService->envoyer($this->commId, 'attente1@example.com');
        $this->invitationService->envoyer($this->commId, 'attente2@example.com');

        $count = $this->invitationService->compterEnAttente($this->commId);
        $this->assertEquals(2, $count);
    }
}
