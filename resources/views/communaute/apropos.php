<?php
$slug = htmlspecialchars($communaute['slug']);
$storage = new \App\Services\StorageService();
$coverUrl = !empty($communaute['image_couverture']) ? $storage->url($communaute['image_couverture']) : '';
$logoUrl = !empty($communaute['logo']) ? $storage->url($communaute['logo']) : '';
$commColor = $communaute['couleur_principale'] ?? '#7830E0';
$commColorLight = $commColor . '18';
$isPrivee = $communaute['visibilite'] === 'privee';
?>

<!-- Page À propos -->
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">À propos de <?= htmlspecialchars($communaute['nom']) ?></h1>
    </div>

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
                <div class="w-full h-48 mb-6 flex items-center justify-center" style="background: <?= $commColorLight ?>;">
                    <i data-lucide="image" class="w-12 h-12" style="color: <?= $commColor ?>40;"></i>
                </div>
                <?php endif; ?>

                <!-- Profile Thumbnail -->
                <div class="mb-6">
                    <?php if ($logoUrl): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" class="w-16 h-16 object-cover border-4 border-white shadow-sm" style="border-radius: 0;">
                    <?php else: ?>
                    <div class="w-16 h-16 flex items-center justify-center border-4 border-white shadow-sm" style="background: <?= $commColorLight ?>; border-radius: 0;">
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
                        <span><?= number_format($totalMembres, 0, ',', ' ') ?> membres</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                        <span>Gratuit</span>
                    </div>
                    <?php if ($proprietaire): ?>
                    <div class="flex items-center gap-2">
                        <?php if (!empty($proprietaire['avatar'])): ?>
                        <img src="<?= htmlspecialchars($proprietaire['avatar']) ?>" class="w-5 h-5 object-cover" style="border-radius: 0;" alt="">
                        <?php else: ?>
                        <div class="w-5 h-5 flex items-center justify-center text-[9px] font-bold text-white" style="background: <?= $commColor ?>; border-radius: 0;">
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
                <div class="h-32 border border-gray-200" style="background: <?= $commColorLight ?>;"></div>
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
                            <div class="text-lg font-bold text-gray-900"><?= number_format($totalMembres, 0, ',', ' ') ?></div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wide">Membres</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900">—</div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wide">En ligne</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900"><?= $totalAdmins ?></div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wide">Admin<?= $totalAdmins > 1 ? 's' : '' ?></div>
                        </div>
                    </div>

                    <!-- Member Avatars -->
                    <?php if (!empty($derniersMembres)): ?>
                    <div class="flex -space-x-2">
                        <?php foreach (array_slice($derniersMembres, 0, 8) as $membre): ?>
                        <?php if (!empty($membre['avatar'])): ?>
                        <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-8 h-8 border-2 border-white object-cover" style="border-radius: 0;" alt="<?= htmlspecialchars($membre['prenom']) ?>">
                        <?php else: ?>
                        <div class="w-8 h-8 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white" style="background: <?= $commColor ?>; border-radius: 0;">
                            <?= strtoupper(substr($membre['prenom'] ?? 'U', 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($totalMembres > 8): ?>
                        <div class="w-8 h-8 border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-semibold text-gray-500" style="border-radius: 0;">
                            +<?= $totalMembres - 8 ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Settings Button (admin only) -->
                    <?php if ($estAdmin): ?>
                    <a href="/c/<?= $slug ?>/gestion/parametres"
                       class="block w-full text-center py-2.5 border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-400 transition">
                        PARAMÈTRES
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>lucide.createIcons();</script>
