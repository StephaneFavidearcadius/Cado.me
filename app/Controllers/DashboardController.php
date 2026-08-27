<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $mesCommunautes = Session::get('mes_communautes', []);

        return $this->view('createur.dashboard', [
            'mes_communautes' => $mesCommunautes,
            'titre' => 'Mon tableau de bord',
        ]);
    }
}
