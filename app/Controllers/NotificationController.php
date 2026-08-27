<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $notificationService = new NotificationService();
        $notifications = $notificationService->lister($communaute['id'], Session::get('utilisateur_id'));

        return $this->viewCommunity('notifications.index', [
            'communaute' => $communaute,
            'notifications' => $notifications,
            'titre' => 'Notifications',
        ]);
    }

    public function marquerToutLu(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if ($communaute) {
            $notificationService = new NotificationService();
            $notificationService->marquerToutLu($communaute['id'], Session::get('utilisateur_id'));
        }

        return $this->redirect("/c/{$slug}/notifications");
    }
}
