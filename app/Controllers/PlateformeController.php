<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Response;
use App\Core\Session;

class PlateformeController extends Controller
{
    public function dashboard(): Response
    {
        $db = Database::getInstance();

        $stats = [
            'communautes' => (int) $db->query('SELECT COUNT(*) FROM communautes WHERE statut = \'active\'')->fetchColumn(),
            'utilisateurs' => (int) $db->query('SELECT COUNT(*) FROM utilisateurs WHERE statut = \'actif\'')->fetchColumn(),
            'publications' => (int) $db->query('SELECT COUNT(*) FROM publications WHERE statut = \'active\'')->fetchColumn(),
        ];

        $communautes = $db->query('SELECT c.*, COUNT(mc.id) as nombre_membres FROM communautes c LEFT JOIN membres_communautes mc ON mc.communaute_id = c.id AND mc.statut = \'actif\' GROUP BY c.id ORDER BY c.date_creation DESC LIMIT 20')->fetchAll();

        return $this->view('plateforme.dashboard', [
            'stats' => $stats,
            'communautes' => $communautes,
            'titre' => 'Administration plateforme',
        ]);
    }

    public function communautes(): Response
    {
        $db = Database::getInstance();
        $communautes = $db->query('SELECT c.*, COUNT(mc.id) as nombre_membres, u.prenom, u.nom as proprietaire_nom FROM communautes c LEFT JOIN membres_communautes mc ON mc.communaute_id = c.id AND mc.statut = \'actif\' JOIN utilisateurs u ON u.id = c.proprietaire_id GROUP BY c.id ORDER BY c.date_creation DESC')->fetchAll();

        return $this->view('plateforme.communautes', [
            'communautes' => $communautes,
            'titre' => 'Gestion des communautés',
        ]);
    }

    public function utilisateurs(): Response
    {
        $db = Database::getInstance();
        $utilisateurs = $db->query('SELECT * FROM utilisateurs ORDER BY date_creation DESC LIMIT 50')->fetchAll();

        return $this->view('plateforme.utilisateurs', [
            'utilisateurs' => $utilisateurs,
            'titre' => 'Gestion des utilisateurs',
        ]);
    }
}
