<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $messageService = new MessageService();
        $conversations = $messageService->listerConversations($communaute['id'], Session::get('utilisateur_id'));

        // Enrichir chaque conversation avec les noms des participants
        foreach ($conversations as &$conv) {
            $conv['participants'] = $messageService->listerParticipants((int)$conv['id'], $communaute['id']);
        }
        unset($conv);

        // Lister les membres pour le formulaire de nouvelle conversation
        $membreService = new \App\Services\MembreCommunauteService();
        $membres = $membreService->lister($communaute['id']);

        return $this->viewCommunity('messages.index', [
            'communaute' => $communaute,
            'conversations' => $conversations,
            'membres' => $membres,
            'titre' => 'Messages',
        ]);
    }

    /**
     * Voir une conversation
     */
    public function voir(string $slug, int $conversationId): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $messageService = new MessageService();
        $userId = Session::get('utilisateur_id');

        // Vérifier que l'utilisateur est participant
        $participants = $messageService->listerParticipants($conversationId, $communaute['id']);
        $isParticipant = false;
        foreach ($participants as $p) {
            if ((int)$p['utilisateur_id'] === $userId) {
                $isParticipant = true;
                break;
            }
        }
        if (!$isParticipant) {
            Session::flash('error', 'Accès refusé.');
            return $this->redirect("/c/{$slug}/messages");
        }

        // Récupérer les messages
        $messages = $messageService->listerMessages($conversationId, $communaute['id']);

        // Marquer comme lus
        $messageService->marquerCommeLus($conversationId, $communaute['id'], $userId);

        return $this->viewCommunity('messages.conversation', [
            'communaute' => $communaute,
            'conversation_id' => $conversationId,
            'messages' => $messages,
            'participants' => $participants,
            'titre' => 'Conversation',
        ]);
    }

    /**
     * Ouvrir ou créer une conversation avec un membre (depuis le bouton CHAT)
     */
    public function ouvrir(string $slug, int $membreId): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $messageService = new MessageService();
        $userId = Session::get('utilisateur_id');

        // Chercher une conversation existante avec ce membre
        $convId = $messageService->trouverConversation($communaute['id'], $userId, $membreId);

        if ($convId) {
            return $this->redirect("/c/{$slug}/messages/{$convId}");
        }

        // Créer une nouvelle conversation
        $resultat = $messageService->creerConversation($communaute['id'], $userId, [$membreId]);
        if ($resultat['success']) {
            return $this->redirect("/c/{$slug}/messages/{$resultat['conversation_id']}");
        }

        Session::flash('error', 'Impossible de créer la conversation.');
        return $this->redirect("/c/{$slug}/messages");
    }

    public function creerConversation(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $participants = $_POST['participants'] ?? [];
        $contenu = trim($_POST['premier_message'] ?? '');

        if (empty($participants) || empty($contenu)) {
            Session::flash('error', 'Sélectionnez un membre et écrivez un message.');
            return $this->redirect("/c/{$slug}/messages");
        }

        $messageService = new MessageService();
        $resultat = $messageService->creerConversation($communaute['id'], Session::get('utilisateur_id'), $participants);

        if ($resultat['success']) {
            $messageService->envoyer($communaute['id'], $resultat['conversation_id'], Session::get('utilisateur_id'), $contenu);
            Session::flash('success', 'Conversation créée !');
            return $this->redirect("/c/{$slug}/messages/{$resultat['conversation_id']}");
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/messages");
    }

    public function envoyer(string $slug, int $conversationId): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $contenu = trim($_POST['contenu'] ?? '');
        $fichiers = $_FILES['fichiers'] ?? null;

        // Reconstruire le tableau de fichiers pour n'inclure que ceux uploadés
        $uploadFiles = [];
        if ($fichiers && !empty($fichiers['name'][0])) {
            $count = count($fichiers['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($fichiers['error'][$i] === UPLOAD_ERR_OK) {
                    $uploadFiles[] = [
                        'name' => $fichiers['name'][$i],
                        'type' => $fichiers['type'][$i],
                        'tmp_name' => $fichiers['tmp_name'][$i],
                        'error' => $fichiers['error'][$i],
                        'size' => $fichiers['size'][$i],
                    ];
                }
            }
        }

        $messageService = new MessageService();
        $resultat = $messageService->envoyerAvecMedia(
            $communaute['id'],
            $conversationId,
            Session::get('utilisateur_id'),
            $contenu,
            $uploadFiles
        );

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json($resultat);
        }

        return $this->redirect("/c/{$slug}/messages/{$conversationId}");
    }
}
