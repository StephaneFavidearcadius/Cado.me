<?php
/**
 * Seeder de démonstration pour Cado.me
 *
 * Usage : php database/seeders/seed_demo.php
 * ⚠️ Ne pas exécuter en production !
 */

require_once __DIR__ . '/../../vendor/autoload.php';
\App\Core\Config::load();

$db = \App\Core\Database::getInstance();

echo "🌱 Démarrage du seed de démonstration...\n";

// ==========================================
// UTILISATEURS
// ==========================================
echo "👤 Création des utilisateurs...\n";

$users = [
    ['prenom' => 'Admin', 'nom' => 'Cado.me', 'email' => 'admin@cado.me', 'role' => 'super_administrateur', 'identifiant' => 'admin'],
    ['prenom' => 'Aminata', 'nom' => 'Diallo', 'email' => 'aminata@example.com', 'role' => 'aucun', 'identifiant' => 'aminatadiallo'],
    ['prenom' => 'Jean', 'nom' => 'Kouassi', 'email' => 'jean@example.com', 'role' => 'aucun', 'identifiant' => 'jeankouassi'],
    ['prenom' => 'Fatou', 'nom' => 'Bamba', 'email' => 'fatou@example.com', 'role' => 'aucun', 'identifiant' => 'fatoubamba'],
    ['prenom' => 'Marc', 'nom' => 'Touré', 'email' => 'marc@example.com', 'role' => 'aucun', 'identifiant' => 'marctoure'],
    ['prenom' => 'Sophie', 'nom' => 'Martin', 'email' => 'sophie@example.com', 'role' => 'aucun', 'identifiant' => 'sophiemartin'],
];

$hash = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]);
$userIds = [];

foreach ($users as $u) {
    $stmt = $db->prepare(
        'INSERT IGNORE INTO utilisateurs (prenom, nom, identifiant, email, mot_de_passe, role_plateforme, statut, email_verifie, date_creation, date_modification)
         VALUES (:prenom, :nom, :identifiant, :email, :mdp, :role, :statut, 1, NOW(), NOW())'
    );
    $stmt->execute([
        'prenom' => $u['prenom'],
        'nom' => $u['nom'],
        'identifiant' => $u['identifiant'],
        'email' => $u['email'],
        'mdp' => $hash,
        'role' => $u['role'],
        'statut' => 'actif',
    ]);
    $userIds[] = $db->lastInsertId() ?: $db->query("SELECT id FROM utilisateurs WHERE email = '{$u['email']}'")->fetch()['id'];
    echo "  ✅ {$u['prenom']} {$u['nom']} ({$u['email']})\n";
}

// ==========================================
// COMMUNAUTÉ
// ==========================================
echo "\n🏘️ Création de la communauté de démo...\n";

$stmt = $db->prepare(
    'INSERT IGNORE INTO communautes (proprietaire_id, nom, slug, description, couleur_principale, statut, visibilite, date_creation, date_modification)
     VALUES (:pid, :nom, :slug, :desc, :couleur, :statut, :visib, NOW(), NOW())'
);
$stmt->execute([
    'pid' => $userIds[1], // Aminata
    'nom' => 'Marketing Digital Afrique',
    'slug' => 'marketing-digital-afrique',
    'desc' => 'Communauté dédiée au marketing digital en Afrique. Partagez vos astuces, formations et ressources.',
    'couleur' => '#7830E0',
    'statut' => 'active',
    'visib' => 'publique',
]);

$communauteId = $db->lastInsertId();
if (!$communauteId) {
    $communauteId = $db->query("SELECT id FROM communautes WHERE slug = 'marketing-digital-afrique'")->fetch()['id'];
}
echo "  ✅ Marketing Digital Afrique (ID: {$communauteId})\n";

// Membres de la communauté
$membres = [
    [$userIds[1], 'proprietaire'],
    [$userIds[2], 'administrateur'],
    [$userIds[3], 'moderateur'],
    [$userIds[4], 'membre'],
    [$userIds[5], 'membre'],
];

foreach ($membres as [$uid, $role]) {
    $stmt = $db->prepare(
        'INSERT IGNORE INTO membres_communautes (communaute_id, utilisateur_id, role, statut, date_adhesion, date_modification)
         VALUES (:cid, :uid, :role, :statut, NOW(), NOW())'
    );
    $stmt->execute(['cid' => $communauteId, 'uid' => $uid, 'role' => $role, 'statut' => 'actif']);
}
echo "  ✅ " . count($membres) . " membres ajoutés\n";

// Abonnement gratuit
$stmt = $db->prepare(
    'INSERT IGNORE INTO abonnements (communaute_id, plan_id, statut, periode_debut, periode_fin, date_creation, date_modification)
     VALUES (:cid, 1, :statut, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), NOW(), NOW())'
);
$stmt->execute(['cid' => $communauteId, 'statut' => 'actif']);

// ==========================================
// PUBLICATIONS
// ==========================================
echo "\n📝 Création des publications...\n";

$publications = [
    ['uid' => $userIds[1], 'contenu' => 'Bienvenue dans notre communauté ! 🎉 Partagez vos connaissances en marketing digital et apprenez les uns des autres.', 'type' => 'texte'],
    ['uid' => $userIds[2], 'contenu' => '💡 Astuce du jour : Utilisez les stories Instagram pour créer de l\'urgence. Les countdown stickers augmentent l\'engagement de 40%.', 'type' => 'texte'],
    ['uid' => $userIds[3], 'contenu' => 'Quels sont vos meilleurs outils de création de contenu ? Moi j\'utilise Canva et CapCut. Partagez les vôtres !', 'type' => 'texte'],
    ['uid' => $userIds[4], 'contenu' => 'Nouveau article sur le SEO local en Afrique : Comment optimiser votre Google My Business pour attirer des clients locaux. Lien dans les ressources !', 'type' => 'lien'],
    ['uid' => $userIds[1], 'contenu' => '📊 Les stats de cette semaine :\n- 150 nouveaux membres\n- 42 publications\n- 89% de taux d\'engagement\nMerci à tous !', 'type' => 'texte'],
];

foreach ($publications as $p) {
    $stmt = $db->prepare(
        'INSERT INTO publications (communaute_id, utilisateur_id, contenu, type, statut, date_creation, date_modification)
         VALUES (:cid, :uid, :contenu, :type, :statut, NOW(), NOW())'
    );
    $stmt->execute([
        'cid' => $communauteId,
        'uid' => $p['uid'],
        'contenu' => $p['contenu'],
        'type' => $p['type'],
        'statut' => 'active',
    ]);
}
echo "  ✅ " . count($publications) . " publications créées\n";

// ==========================================
// FORMATIONS
// ==========================================
echo "\n📚 Création des formations...\n";

$stmt = $db->prepare(
    'INSERT IGNORE INTO formations (communaute_id, titre, slug, description, statut, ordre, date_creation, date_modification)
     VALUES (:cid, :titre, :slug, :desc, :statut, :ordre, NOW(), NOW())'
);

$formations = [
    ['titre' => 'Fondamentaux du Marketing Digital', 'slug' => 'fondamentaux-marketing-digital', 'desc' => 'Apprenez les bases du marketing digital : SEO, SEA, Social Media, Email Marketing.', 'ordre' => 1],
    ['titre' => 'Création de Contenu Viral', 'slug' => 'creation-contenu-viral', 'desc' => 'Maîtrisez les techniques pour créer du contenu qui cartonne sur les réseaux sociaux.', 'ordre' => 2],
];

$formationIds = [];
foreach ($formations as $f) {
    $stmt->execute([
        'cid' => $communauteId,
        'titre' => $f['titre'],
        'slug' => $f['slug'],
        'desc' => $f['desc'],
        'statut' => 'active',
        'ordre' => $f['ordre'],
    ]);
    $fid = $db->lastInsertId();
    if (!$fid) {
        $fid = $db->query("SELECT id FROM formations WHERE slug = '{$f['slug']}' AND communaute_id = {$communauteId}")->fetch()['id'];
    }
    $formationIds[] = $fid;
    echo "  ✅ {$f['titre']}\n";
}

// Leçons de la formation 1
$lecons = [
    ['fid' => $formationIds[0], 'titre' => 'Introduction au Marketing Digital', 'slug' => 'intro-marketing-digital', 'desc' => 'Découvrez le marketing digital et son importance pour les entreprises africaines.', 'contenu' => '<h2>Qu\'est-ce que le marketing digital ?</h2><p>Le marketing digital englobe toutes les stratégies marketing utilisées sur les supports numériques. En Afrique, il représente une opportunité unique de toucher des millions de personnes connectées via leurs smartphones.</p><h3>Les piliers :</h3><ul><li>SEO (Référencement naturel)</li><li>SEA (Référencement payant)</li><li>Social Media Marketing</li><li>Email Marketing</li><li>Content Marketing</li></ul>', 'ordre' => 1],
    ['fid' => $formationIds[0], 'titre' => 'Comprendre le SEO', 'slug' => 'comprendre-seo', 'desc' => 'Apprenez à optimiser votre site pour les moteurs de recherche.', 'contenu' => '<h2>Le SEO expliqué simplement</h2><p>Le SEO (Search Engine Optimization) est l\'art de positionner votre site en haut des résultats de Google. En Afrique, avec la montée de Google, c\'est un levier essentiel.</p>', 'ordre' => 2],
    ['fid' => $formationIds[1], 'titre' => 'Les Formats de Contenu', 'slug' => 'formats-contenu', 'desc' => 'Découvrez les différents formats de contenu et quand les utiliser.', 'contenu' => '<h2>Quel format choisir ?</h2><p>Chaque plateforme a ses préférences. Instagram favorise les carrousels, TikTok les vidéos courtes, LinkedIn les articles longs.</p>', 'ordre' => 1],
];

foreach ($lecons as $l) {
    $stmt = $db->prepare(
        'INSERT IGNORE INTO lecons (communaute_id, formation_id, titre, slug, description, contenu, ordre, statut, date_creation)
         VALUES (:cid, :fid, :titre, :slug, :desc, :contenu, :ordre, :statut, NOW())'
    );
    $stmt->execute([
        'cid' => $communauteId,
        'fid' => $l['fid'],
        'titre' => $l['titre'],
        'slug' => $l['slug'],
        'desc' => $l['desc'],
        'contenu' => $l['contenu'],
        'ordre' => $l['ordre'],
        'statut' => 'active',
    ]);
}
echo "  ✅ " . count($lecons) . " leçons créées\n";

// ==========================================
// ÉVÉNEMENTS
// ==========================================
echo "\n📅 Création des événements...\n";

$stmt = $db->prepare(
    'INSERT IGNORE INTO evenements (communaute_id, titre, slug, description, date_debut, date_fin, type, statut, date_creation, date_modification)
     VALUES (:cid, :titre, :slug, :desc, :debut, :fin, :type, :statut, NOW(), NOW())'
);

$evenements = [
    ['titre' => 'Webinaire SEO Local', 'slug' => 'webinaire-seo-local', 'desc' => 'Apprenez à optimiser votre référencement local avec nos experts.', 'debut' => date('Y-m-d 14:00:00', strtotime('+7 days')), 'fin' => date('Y-m-d 16:00:00', strtotime('+7 days')), 'type' => 'webinaire'],
    ['titre' => 'Meetup Marketing Afrique', 'slug' => 'meetup-marketing-afrique', 'desc' => 'Rencontrez les acteurs du marketing digital en Afrique de l\'Ouest.', 'debut' => date('Y-m-d 18:00:00', strtotime('+14 days')), 'fin' => date('Y-m-d 21:00:00', strtotime('+14 days')), 'type' => 'meetup'],
];

foreach ($evenements as $e) {
    $stmt->execute([
        'cid' => $communauteId,
        'titre' => $e['titre'],
        'slug' => $e['slug'],
        'desc' => $e['desc'],
        'debut' => $e['debut'],
        'fin' => $e['fin'],
        'type' => $e['type'],
        'statut' => 'active',
    ]);
}
echo "  ✅ " . count($evenements) . " événements créés\n";

// ==========================================
// LIKES & COMMENTAIRES
// ==========================================
echo "\n❤️ Ajout de likes et commentaires...\n";

// Récupérer les IDs des publications
$stmt = $db->prepare('SELECT id FROM publications WHERE communaute_id = :cid ORDER BY id ASC');
$stmt->execute(['cid' => $communauteId]);
$pubs = $stmt->fetchAll();

// Likes
$likeCount = 0;
foreach ($pubs as $i => $pub) {
    foreach ($userIds as $j => $uid) {
        if ($i === $j) continue; // pas de like sur sa propre pub
        $stmt = $db->prepare(
            'INSERT IGNORE INTO likes_publications (communaute_id, publication_id, utilisateur_id, date_creation)
             VALUES (:cid, :pid, :uid, NOW())'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $pub['id'], 'uid' => $uid]);
        if ($stmt->rowCount()) $likeCount++;
    }
}
echo "  ✅ {$likeCount} likes ajoutés\n";

// Commentaires
$commentaires = [
    ['pub_idx' => 0, 'uid' => $userIds[2], 'contenu' => 'Super initiative ! Hâte de partager mes connaissances. 🚀'],
    ['pub_idx' => 0, 'uid' => $userIds[3], 'contenu' => 'Merci pour cette communauté !'],
    ['pub_idx' => 1, 'uid' => $userIds[3], 'contenu' => 'Les stories c\'est vraiment puissant. J\'ai testé et ça fonctionne !'],
    ['pub_idx' => 2, 'uid' => $userIds[4], 'contenu' => 'Je recommande aussi Figma pour le design et Descript pour la vidéo.'],
    ['pub_idx' => 2, 'uid' => $userIds[5], 'contenu' => 'Content Marketing Institute est une bonne ressource aussi.'],
];

$commentCount = 0;
foreach ($commentaires as $c) {
    $stmt = $db->prepare(
        'INSERT INTO commentaires (communaute_id, publication_id, utilisateur_id, contenu, statut, date_creation, date_modification)
         VALUES (:cid, :pid, :uid, :contenu, :statut, NOW(), NOW())'
    );
    $stmt->execute([
        'cid' => $communauteId,
        'pid' => $pubs[$c['pub_idx']]['id'],
        'uid' => $c['uid'],
        'contenu' => $c['contenu'],
        'statut' => 'actif',
    ]);
    $commentCount++;
}
echo "  ✅ {$commentCount} commentaires ajoutés\n";

// ==========================================
// RÉSULTATS
// ==========================================
echo "\n";
echo "════════════════════════════════════════════\n";
echo "  🎉 Seed terminé avec succès !\n";
echo "════════════════════════════════════════════\n";
echo "\n";
echo "  📧 Comptes de démonstration :\n";
echo "  ──────────────────────────────\n";
foreach ($users as $u) {
    echo "  {$u['prenom']} {$u['nom']}\n";
    echo "    Email: {$u['email']}\n";
    echo "    Mdp:   password123\n";
    echo "    Rôle:  {$u['role']}\n\n";
}
echo "  🏘️ Communauté : Marketing Digital Afrique\n";
echo "     URL: /c/marketing-digital-afrique\n";
echo "\n";
