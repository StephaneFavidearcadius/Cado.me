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

        return $this->viewCommunity('messages.index', [
            'communaute' => $communaute,
            'conversations' => $conversations,
            'titre' => 'Messages',
        ]);
    }
}
