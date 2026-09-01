<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SignalementService;
use App\Services\PublicationService;
use App\Services\CommentaireService;

class SignalementServiceTest extends TestCase
{
    private int $userId1;
    private int $userId2;
    private int $commId;
    private int $pubId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId1 = $this->creerUtilisateur(['email' => 'sign1_' . uniqid() . '@test.com', 'identifiant' => 'sign1_' . uniqid()]);
        $this->userId2 = $this->creerUtilisateur(['email' => 'sign2_' . uniqid() . '@test.com', 'identifiant' => 'sign2_' . uniqid()]);

        $this->commId = $this->creerCommunaute($this->userId1, ['slug' => 'comm-sign-' . uniqid()]);
        $this->ajouterMembre($this->commId, $this->userId1, 'proprietaire');
        $this->ajouterMembre($this->commId, $this->userId2, 'membre');

        $pubService = new PublicationService();
        $result = $pubService->creer($this->commId, $this->userId1, ['contenu' => 'Publication signalable']);
        $this->pubId = $result['publication_id'];
    }

    public function testSignalerPublication(): void
    {
        $service = new SignalementService();
        $result = $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Contenu inapproprié');

        $this->assertTrue($result['success']);
    }

    public function testSignalementMotifVide(): void
    {
        $service = new SignalementService();
        $result = $service->signalerPublication($this->commId, $this->userId2, $this->pubId, '');

        $this->assertFalse($result['success']);
    }

    public function testDoubleSignalement(): void
    {
        $service = new SignalementService();
        $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Premier signalement');
        $result = $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Deuxième signalement');

        $this->assertFalse($result['success']);
    }

    public function testSignalementPublicationInexistante(): void
    {
        $service = new SignalementService();
        $result = $service->signalerPublication($this->commId, $this->userId2, 99999, 'Test');

        $this->assertFalse($result['success']);
    }

    public function testListerSignalements(): void
    {
        $service = new SignalementService();
        $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Signalement 1');

        $signalements = $service->lister($this->commId);

        $this->assertCount(1, $signalements);
        $this->assertEquals('en_attente', $signalements[0]['statut']);
    }

    public function testTraiterSignalementMasquer(): void
    {
        $service = new SignalementService();
        $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Inapproprié');

        $db = self::getTestDb();
        $stmt = $db->prepare('SELECT id FROM signalements WHERE communaute_id = :cid AND publication_id = :pid');
        $stmt->execute(['cid' => $this->commId, 'pid' => $this->pubId]);
        $signalementId = (int) $stmt->fetch()['id'];

        $result = $service->traiter($this->commId, $signalementId, 'traite');

        $this->assertTrue($result['success']);

        // Vérifier que la publication est masquée
        $stmt2 = $db->prepare('SELECT statut FROM publications WHERE id = :pid');
        $stmt2->execute(['pid' => $this->pubId]);
        $this->assertEquals('masquee', $stmt2->fetch()['statut']);
    }

    public function testTraiterSignalementRejeter(): void
    {
        $service = new SignalementService();
        $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Faux positif');

        $db = self::getTestDb();
        $stmt = $db->prepare('SELECT id FROM signalements WHERE communaute_id = :cid AND publication_id = :pid');
        $stmt->execute(['cid' => $this->commId, 'pid' => $this->pubId]);
        $signalementId = (int) $stmt->fetch()['id'];

        $result = $service->traiter($this->commId, $signalementId, 'rejete');

        $this->assertTrue($result['success']);

        // La publication ne doit PAS être masquée
        $stmt2 = $db->prepare('SELECT statut FROM publications WHERE id = :pid');
        $stmt2->execute(['pid' => $this->pubId]);
        $this->assertEquals('active', $stmt2->fetch()['statut']);
    }

    public function testCompterEnAttente(): void
    {
        $service = new SignalementService();
        $service->signalerPublication($this->commId, $this->userId2, $this->pubId, 'Un');

        $count = $service->compterEnAttente($this->commId);
        $this->assertEquals(1, $count);
    }
}
