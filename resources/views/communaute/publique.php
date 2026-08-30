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

    <!-- Header simple style Skool -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 flex items-center justify-center" style="background: <?= $commColor ?>;">
                        <?php if ($logoUrl): ?>
                        <img src="<?= htmlspecialchars($logoUrl) ?>" class="w-8 h-8 object-cover" alt="">
                        <?php else: ?>
                        <span class="text-white font-bold text-sm"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="font-bold text-lg text-gray-900"><?= htmlspecialchars($communaute['nom']) ?></span>
                </a>
                <?php if ($estConnecte): ?>
                <a href="/app" class="px-4 py-2 text-sm font-semibold text-white transition" style="background: <?= $commColor ?>;">Mon espace</a>
                <?php else: ?>
                <a href="/connexion" class="px-5 py-2 text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Contenu -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-8">

            <!-- ===== CONTENU PRINCAL (gauche) ===== -->
            <div class="flex-1 min-w-0">
                <div class="bg-white border border-gray-200">

                    <!-- Nom communauté -->
                    <div class="px-8 pt-8 pb-4">
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($communaute['nom']) ?></h1>
                    </div>

                    <!-- Bannière image (pleine largeur) -->
                    <?php if ($coverUrl): ?>
                    <div class="px-8 mb-6">
                        <img src="<?= htmlspecialchars($coverUrl) ?>" alt="" class="w-full object-cover" style="max-height: 450px;">
                    </div>
                    <?php else: ?>
                    <div class="mx-8 h-64 mb-6 flex items-center justify-center" style="background: <?= $commColor ?>18;">
                        <i data-lucide="image" class="w-12 h-12" style="color: <?= $commColor ?>40;"></i>
                    </div>
                    <?php endif; ?>

                    <!-- Meta row (style Skool) -->
                    <div class="px-8 pb-6 flex flex-wrap items-center gap-5 text-sm text-gray-600">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="<?= $isPrivee ? 'lock' : 'globe' ?>" class="w-4 h-4 text-gray-400"></i>
                            <span><?= $isPrivee ? 'Privée' : 'Publique' ?></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            <span><?= number_format($totalMembres) ?> membre(s)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                            <span>Gratuit</span>
                        </div>
                        <?php if ($proprietaire): ?>
                        <div class="flex items-center gap-1.5">
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

                    <!-- Description complète -->
                    <div class="px-8 pb-8 border-t border-gray-100 pt-6">
                        <?php if (!empty($communaute['description'])): ?>
                            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?= nl2br(htmlspecialchars($communaute['description'])) ?></div>
                        <?php else: ?>
                            <p class="text-gray-400 italic text-sm">Aucune description pour le moment.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- ===== SIDEBAR DROITE (résumé compact) ===== -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-20">

                    <!-- Cover thumbnail -->
                    <?php if ($coverUrl): ?>
                    <div class="overflow-hidden border border-gray-200">
                        <img src="<?= htmlspecialchars($coverUrl) ?>" alt="" class="w-full h-32 object-cover">
                    </div>
                    <?php else: ?>
                    <div class="h-32 border border-gray-200" style="background: <?= $commColor ?>18;"></div>
                    <?php endif; ?>

                    <div class="bg-white border border-t-0 border-gray-200 p-5 space-y-4">

                        <!-- Nom -->
                        <h2 class="font-bold text-gray-900 text-lg leading-tight"><?= htmlspecialchars($communaute['nom']) ?></h2>

                        <!-- URL -->
                        <p class="text-xs text-gray-400 select-all cursor-pointer" onclick="navigator.clipboard.writeText('cado.me/<?= htmlspecialchars($communaute['slug']) ?>').then(()=>{this.textContent='Lien copié !';setTimeout(()=>this.textContent='cado.me/<?= htmlspecialchars($communaute['slug']) ?>',2000)})">cado.me/<?= htmlspecialchars($communaute['slug']) ?></p>

                        <!-- Description courte -->
                        <?php if (!empty($communaute['description'])): ?>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            <?= htmlspecialchars(mb_strimwidth($communaute['description'], 0, 150, '...')) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-3 text-center py-3 border-y border-gray-100">
                            <div>
                                <div class="text-lg font-bold text-gray-900"><?= number_format($totalMembres) ?></div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Membres</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-gray-900">—</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">En ligne</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-gray-900">1</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Admin</div>
                            </div>
                        </div>

                        <!-- Avatars membres -->
                        <?php if (!empty($derniersMembres)): ?>
                        <div class="flex -space-x-2">
                            <?php foreach (array_slice($derniersMembres, 0, 8) as $membre): ?>
                            <?php if (!empty($membre['avatar'])): ?>
                            <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-8 h-8 border-2 border-white object-cover" alt="">
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

                        <!-- Bouton -->
                        <?php if ($estConnecte && $estMembre): ?>
                        <a href="/c/<?= htmlspecialchars($communaute['slug']) ?>/feed"
                           class="block w-full text-center py-2.5 text-sm font-bold text-white transition"
                           style="background: <?= $commColor ?>;">
                            ENTRER
                        </a>
                        <?php elseif ($estConnecte): ?>
                        <form method="POST" action="/c/<?= htmlspecialchars($communaute['slug']) ?>/rejoindre">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit"
                                    class="block w-full text-center py-2.5 text-sm font-bold text-white transition"
                                    style="background: <?= $commColor ?>;">
                                REJOINDRE
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="/inscription"
                           class="block w-full text-center py-2.5 text-sm font-bold text-white transition"
                           style="background: <?= $commColor ?>;">
                            REJOINDRE
                        </a>
                        <?php endif; ?>

                        <!-- Powered by -->
                        <p class="text-center text-[11px] text-gray-400 pt-1">Powered by <span class="font-semibold text-gray-500">Cado.me</span></p>
                    </div>
                </div>
            </aside>

        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
