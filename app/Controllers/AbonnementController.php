<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;

class AbonnementController extends Controller
{
    /**
     * Page d'abonnement — affiche les plans disponibles
     */
    public function index(): Response
    {
        $db = \App\Core\Database::getInstance();

        // Récupérer l'abonnement actif de l'utilisateur
        $stmt = $db->prepare(
            'SELECT a.*, p.nom as plan_nom, p.prix_mensuel
             FROM abonnements a
             JOIN plans p ON p.id = a.plan_id
             WHERE a.statut = :statut AND a.periode_fin >= CURDATE()
             ORDER BY a.date_creation DESC LIMIT 1'
        );
        $stmt->execute(['statut' => 'actif']);
        $abonnement = $stmt->fetch();

        // Récupérer tous les plans actifs
        $stmt2 = $db->query('SELECT * FROM plans WHERE actif = 1 ORDER BY prix_mensuel ASC');
        $plans = $stmt2->fetchAll();

        return $this->view('abonnement.index', [
            'abonnement' => $abonnement,
            'plans' => $plans,
            'titre' => 'Abonnement',
        ]);
    }

    /**
     * Souscrire à un plan (simulation de paiement)
     */
    public function souscrire(): Response
    {
        $planId = (int)($_POST['plan_id'] ?? 0);
        $userId = Session::get('utilisateur_id');

        if (!$planId || !$userId) {
            Session::flash('error', 'Paramètres invalides.');
            return $this->redirect('/abonnement');
        }

        $db = \App\Core\Database::getInstance();

        // Vérifier que le plan existe
        $stmt = $db->prepare('SELECT * FROM plans WHERE id = ? AND actif = 1');
        $stmt->execute([$planId]);
        $plan = $stmt->fetch();

        if (!$plan) {
            Session::flash('error', 'Plan introuvable.');
            return $this->redirect('/abonnement');
        }

        // Désactiver les anciens abonnements
        $stmt = $db->prepare('UPDATE abonnements SET statut = :statut WHERE statut = :statut2');
        $stmt->execute(['statut' => 'annule', 'statut2' => 'actif']);

        // Créer le nouvel abonnement (1 an par défaut)
        $stmt = $db->prepare(
            'INSERT INTO abonnements (communaute_id, plan_id, statut, periode_debut, periode_fin, date_creation, date_modification)
             VALUES (0, :plan_id, :statut, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), NOW(), NOW())'
        );
        $stmt->execute([
            'plan_id' => $planId,
            'statut' => 'actif',
        ]);

        Session::flash('success', 'Abonnement souscrit avec succès ! Vous pouvez maintenant créer votre communauté.');
        return $this->redirect('/app/communautes/creer');
    }
}
