<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PartageService;
use App\Services\PublicationService;

class PartageServiceTest extends TestCase
{
    private int $userId1;
    private int $userId2;
    private int $commId;
    private int $pubId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId1 = $this->creerUtilisateur(['email' => 'share1_' . uniqid() . '@test.com', 'identifiant' => 'share1_' . uniqid()]);
        $this->userId2 = $this->creerUtilisateur(['email' => 'share2_' . uniqid() . '@test.com', 'identifiant' => 'share2_' . uniqid()]);

        $this->commId = $this->creerCommunaute($this->userId1, ['slug' => 'comm-share-' . uniqid()]);
        $this->ajouterMembre($this->commId, $this->userId1, 'proprietaire');
        $this->ajouterMembre($this->commId, $this->userId2, 'membre');

        $pubService = new PublicationService();
        $result = $pubService->creer($this->commId, $this->userId1, ['contenu' => 'Publication à partager']);
        $this->pubId = $result['publication_id'];
    }

    public function testPartager(): void
    {
        $service = new PartageService();
        $result = $service->partager($this->commId, $this->pubId, $this->userId2);

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['nb_partages']);
    }

    public function testDoublePartage(): void
    {
        $service = new PartageService();
        $service->partager($this->commId, $this->pubId, $this->userId2);
        $result = $service->partager($this->commId, $this->pubId, $this->userId2);

        $this->assertFalse($result['success']);
    }

    public function testAnnulerPartage(): void
    {
        $service = new PartageService();
        $service->partager($this->commId, $this->pubId, $this->userId2);
        $result = $service->annulerPartage($this->commId, $this->pubId, $this->userId2);

        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['nb_partages']);
    }

    public function testCompterPartages(): void
    {
        $service = new PartageService();
        $service->partager($this->commId, $this->pubId, $this->userId1);
        $service->partager($this->commId, $this->pubId, $this->userId2);

        $count = $service->compterPartages($this->commId, $this->pubId);
        $this->assertEquals(2, $count);
    }

    public function testEstPartage(): void
    {
        $service = new PartageService();

        $this->assertFalse($service->estPartage($this->commId, $this->pubId, $this->userId1));

        $service->partager($this->commId, $this->pubId, $this->userId1);

        $this->assertTrue($service->estPartage($this->commId, $this->pubId, $this->userId1));
    }
}
