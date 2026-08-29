<?php
$storage = new \App\Services\StorageService();
$coverUrl = !empty($communaute['image_couverture']) ? $storage->url($communaute['image_couverture']) : '';
$logoUrl = !empty($communaute['logo']) ? $storage->url($communaute['logo']) : '';
$commColor = $communaute['couleur_principale'] ?? '#7830E0';
$isPrivee = $communaute['visibilite'] === 'privee';

// Stats
$db = \App\Core\Database::getInstance();
$stmt = $db->prepare('SELECT COUNT(*) as total FROM membres_communautes WHERE communaute_id = :cid AND statut = :statut');
$stmt->execute(['cid' => $communaute['id'], 'statut' => 'actif']);
$totalMembres = (int) $stmt->fetch()['total'];

$stmt = $db->prepare('SELECT u.prenom, u.nom, u.avatar FROM membres_communautes mc JOIN utilisateurs u ON u.id = mc.utilisateur_id WHERE mc.communaute_id = :cid AND mc.role = :role LIMIT 1');
$stmt->execute(['cid' => $communaute['id'], 'role' => 'proprietaire']);
$proprietaire = $stmt->fetch();

$stmt = $db->prepare('SELECT u.id, u.prenom, u.nom, u.avatar FROM membres_communautes mc JOIN utilisateurs u ON u.id = mc.utilisateur_id WHERE mc.communaute_id = :cid AND mc.statut = :statut ORDER BY mc.date_adhesion DESC LIMIT 10');
$stmt->execute(['cid' => $communaute['id'], 'statut' => 'actif']);
$derniersMembres = $stmt->fetchAll();

$estConnecte = !empty($_SESSION['utilisateur_id'] ?? null);

// Vérifier si l'utilisateur est déjà membre
$estMembre = false;
if ($estConnecte) {
    $stmt = $db->prepare('SELECT id FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut');
    $stmt->execute(['cid' => $communaute['id'], 'uid' => $_SESSION['utilisateur_id'], 'statut' => 'actif']);
    $estMembre = (bool) $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($communaute['nom']) ?> - Cado.me</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { violet: { 50:'#F3EAFF',100:'#E8D5FF',200:'#D1B3FF',300:'#B88FFF',400:'#9B5DEB',500:'#7830E0',600:'#6420C7',700:'#5018A0',800:'#3C1278',900:'#280C50' }},
                fontFamily: { 'sora': ['Sora','sans-serif'] }
            }}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>*, *::before, *::after { border-radius: 0 !important; }</style>
</head>
<body class="font-sora bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="<?= $estConnecte ? '/app' : '/' ?>" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <?php if ($estConnecte): ?>
                <a href="/app" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">Mon espace</a>
                <?php else: ?>
                <div class="flex items-center gap-3">
                    <a href="/connexion" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">Rejoindre</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-8">
            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <div class="bg-white border border-gray-200 p-8">
                    <!-- Community Name -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-6"><?= htmlspecialchars($communaute['nom']) ?></h1>

                    <!-- Banner Image -->
                    <?php if ($coverUrl): ?>
                    <div class="mb-6 overflow-hidden">
                        <img src="<?= htmlspecialchars($coverUrl) ?>" alt="Bannière" class="w-full h-auto object-cover" style="max-height: 400px;">
                    </div>
                    <?php else: ?>
                    <div class="w-full h-48 mb-6 flex items-center justify-center" style="background: <?= $commColor ?>18;">
                        <i data-lucide="image" class="w-12 h-12" style="color: <?= $commColor ?>40;"></i>
                    </div>
                    <?php endif; ?>

                    <!-- Profile Thumbnail -->
                    <div class="mb-6">
                        <?php if ($logoUrl): ?>
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" class="w-16 h-16 object-cover border-4 border-white shadow-sm">
                        <?php else: ?>
                        <div class="w-16 h-16 flex items-center justify-center border-4 border-white shadow-sm" style="background: <?= $commColor ?>18;">
                            <span class="text-2xl font-bold" style="color: <?= $commColor ?>;"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Meta Row -->
                    <div class="flex flex-wrap items-center gap-6 mb-8 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="<?= $isPrivee ? 'lock' : 'globe' ?>" class="w-4 h-4 text-gray-400"></i>
                            <span><?= $isPrivee ? 'Privée' : 'Publique' ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            <span><?= number_format($totalMembres) ?> membre(s)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                            <span>Gratuit</span>
                        </div>
                        <?php if ($proprietaire): ?>
                        <div class="flex items-center gap-2">
                            <?php if (!empty($proprietaire['avatar'])): ?>
                            <img src="<?= htmlspecialchars($proprietaire['avatar']) ?>" class="w-5 h-5 object-cover" alt="">
                            <?php else: ?>
                            <div class="w-5 h-5 flex items-center justify-center text-[9px] font-bold text-white" style="background: <?= $commColor ?>;">
                                <?= strtoupper(substr($proprietaire['prenom'] ?? 'U', 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                            <span>Par <?= htmlspecialchars(($proprietaire['prenom'] ?? '') . ' ' . substr($proprietaire['nom'] ?? '', 0, 1) . '.') ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="prose prose-gray max-w-none">
                        <?php if (!empty($communaute['description'])): ?>
                            <?= nl2br(htmlspecialchars($communaute['description'])) ?>
                        <?php else: ?>
                            <p class="text-gray-400 italic">Aucune description pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-24 space-y-0">
                    <!-- Cover Thumbnail -->
                    <?php if ($coverUrl): ?>
                    <div class="overflow-hidden border border-gray-200">
                        <img src="<?= htmlspecialchars($coverUrl) ?>" alt="" class="w-full h-32 object-cover">
                    </div>
                    <?php else: ?>
                    <div class="h-32 border border-gray-200" style="background: <?= $commColor ?>18;"></div>
                    <?php endif; ?>

                    <div class="bg-white border border-t-0 border-gray-200 p-5 space-y-4">
                        <!-- Community Name -->
                        <h2 class="font-bold text-gray-900 text-lg"><?= htmlspecialchars($communaute['nom']) ?></h2>

                        <!-- URL -->
                        <p class="text-xs text-gray-400">cado.me/c/<?= htmlspecialchars($communaute['slug']) ?></p>

                        <!-- Short Description -->
                        <?php if (!empty($communaute['description'])): ?>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?= htmlspecialchars(mb_strimwidth($communaute['description'], 0, 150, '...')) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-3 text-center py-3 border-y border-gray-100">
                            <div>
                                <div class="text-lg font-bold text-gray-900"><?= number_format($totalMembres) ?></div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wide">Membres</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-gray-900">—</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wide">En ligne</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-gray-900">1</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wide">Admin</div>
                            </div>
                        </div>

                        <!-- Member Avatars -->
                        <?php if (!empty($derniersMembres)): ?>
                        <div class="flex -space-x-2">
                            <?php foreach (array_slice($derniersMembres, 0, 8) as $membre): ?>
                            <?php if (!empty($membre['avatar'])): ?>
                            <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-8 h-8 border-2 border-white object-cover" alt="<?= htmlspecialchars($membre['prenom']) ?>">
                            <?php else: ?>
                            <div class="w-8 h-8 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white" style="background: <?= $commColor ?>;">
                                <?= strtoupper(substr($membre['prenom'] ?? 'U', 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if ($totalMembres > 8): ?>
                            <div class="w-8 h-8 border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-semibold text-gray-500">
                                +<?= $totalMembres - 8 ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Bouton selon le statut -->
                        <?php if ($estConnecte && $estMembre): ?>
                        <a href="/c/<?= htmlspecialchars($communaute['slug']) ?>/feed"
                           class="block w-full text-center py-2.5 text-sm font-semibold text-white transition"
                           style="background: <?= $commColor ?>;">
                            ENTRER
                        </a>
                        <?php elseif ($estConnecte): ?>
                        <form method="POST" action="/c/<?= htmlspecialchars($communaute['slug']) ?>/rejoindre">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit"
                                    class="block w-full text-center py-2.5 text-sm font-semibold text-white transition"
                                    style="background: <?= $commColor ?>;">
                                REJOINDRE
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="/inscription"
                           class="block w-full text-center py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">
                            REJOINDRE
                        </a>
                        <?php endif; ?>

                        <?php if (!$estMembre): ?>
                        <p class="text-center text-xs text-gray-400">Powered by <span class="font-semibold">Cado.me</span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
