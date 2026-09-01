<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PublicationService;
use App\Services\CommentaireService;
use App\Services\CommunauteService;

/**
 * Tests critiques d'isolation multi-tenant
 *
 * Vérifie qu'aucune donnée d'une communauté ne fuite vers une autre.
 */
class IsolationMultiTenantTest extends TestCase
{
    private int $userId1;
    private int $userId2;
    private int $commIdA;
    private int $commIdB;
    private int $pubIdA;
    private int $pubIdB;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer 2 utilisateurs
        $this->userId1 = $this->creerUtilisateur(['email' => 'user1_' . uniqid() . '@test.com', 'identifiant' => 'user1_' . uniqid()]);
        $this->userId2 = $this->creerUtilisateur(['email' => 'user2_' . uniqid() . '@test.com', 'identifiant' => 'user2_' . uniqid()]);

        // Créer 2 communautés distinctes
        $this->commIdA = $this->creerCommunaute($this->userId1, [
            'nom' => 'Communauté A',
            'slug' => 'comm-a-' . uniqid(),
        ]);
        $this->commIdB = $this->creerCommunaute($this->userId2, [
            'nom' => 'Communauté B',
            'slug' => 'comm-b-' . uniqid(),
        ]);

        // Ajouter les membres
        $this->ajouterMembre($this->commIdA, $this->userId1, 'proprietaire');
        $this->ajouterMembre($this->commIdA, $this->userId2, 'membre');
        $this->ajouterMembre($this->commIdB, $this->userId2, 'proprietaire');
        $this->ajouterMembre($this->commIdB, $this->userId1, 'membre');

        // Créer des publications dans chaque communauté
        $pubServiceA = new PublicationService();
        $resultA = $pubServiceA->creer($this->commIdA, $this->userId1, ['contenu' => 'Publication de la communauté A - CONFIDENTIELLE']);
        $this->pubIdA = $resultA['publication_id'];

        $pubServiceB = new PublicationService();
        $resultB = $pubServiceB->creer($this->commIdB, $this->userId2, ['contenu' => 'Publication de la communauté B - CONFIDENTIELLE']);
        $this->pubIdB = $resultB['publication_id'];
    }

    /**
     * Test fondamental : le feed de la communauté A ne doit PAS contenir de publication de B
     */
    public function testFeedIsoleParCommunaute(): void
    {
        $pubService = new PublicationService();

        $feedA = $pubService->feed($this->commIdA);
        $feedB = $pubService->feed($this->commIdB);

        // Le feed A ne doit contenir que la publication A
        $this->assertCount(1, $feedA['publications'], 'Le feed A ne doit contenir qu\'une seule publication');
        $this->assertEquals($this->pubIdA, $feedA['publications'][0]['id']);

        // Le feed B ne doit contenir que la publication B
        $this->assertCount(1, $feedB['publications'], 'Le feed B ne doit contenir qu\'une seule publication');
        $this->assertEquals($this->pubIdB, $feedB['publications'][0]['id']);
    }

    /**
     * On ne peut PAS liker une publication d'une autre communauté via le service
     */
    public function testLikeIsoleParCommunaute(): void
    {
        $pubService = new PublicationService();

        // Essayer de liker la pub B depuis le contexte A
        $result = $pubService->aimer($this->commIdA, $this->pubIdB, $this->userId1);
        $this->assertFalse($result['success'], 'On ne peut pas liker une pub d\'une autre communauté');
    }

    /**
     * On ne peut PAS supprimer une publication d'une autre communauté
     */
    public function testSuppressionIsoléeParCommunaute(): void
    {
        $pubService = new PublicationService();

        // Essayer de supprimer la pub B depuis le contexte A
        $supprime = $pubService->supprimer($this->commIdA, $this->pubIdB);
        $this->assertFalse($supprime, 'On ne peut pas supprimer une pub d\'une autre communauté');

        // Vérifier que la pub B existe toujours
        $feedB = (new PublicationService())->feed($this->commIdB);
        $this->assertCount(1, $feedB['publications'], 'La pub B doit toujours exister');
    }

    /**
     * Les commentaires sont isolés par communauté
     */
    public function testCommentairesIsolesParCommunaute(): void
    {
        $commentService = new CommentaireService();

        // Ajouter un commentaire sur la pub A
        $result = $commentService->ajouter($this->commIdA, $this->pubIdA, $this->userId2, 'Commentaire test A');
        $this->assertTrue($result['success']);

        // Essayer de commenter la pub B depuis le contexte A
        $resultFail = $commentService->ajouter($this->commIdA, $this->pubIdB, $this->userId1, 'Commentaire cross-tenant');
        $this->assertFalse($resultFail['success'], 'On ne peut pas commenter une pub d\'une autre communauté');

        // Lister les commentaires de A ne doit pas contenir de commentaires de B
        $commentairesA = $commentService->lister($this->commIdA, $this->pubIdA);
        $this->assertCount(1, $commentairesA);
    }

    /**
     * Vérifier que la vérification d'appartenance fonctionne
     */
    public function testAppartenanceCommunaute(): void
    {
        $pubService = new PublicationService();

        // La pub A appartient bien à la comm A
        $this->assertTrue($pubService->publicationAppartientA($this->commIdA, $this->pubIdA));

        // La pub A N'APPARTIENT PAS à la comm B
        $this->assertFalse($pubService->publicationAppartientA($this->commIdB, $this->pubIdA));

        // La pub B N'APPARTIENT PAS à la comm A
        $this->assertFalse($pubService->publicationAppartientA($this->commIdA, $this->pubIdB));
    }
}
