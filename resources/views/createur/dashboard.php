<?php
$storage = new \App\Services\StorageService();
$hasAbonnement = !empty($abonnement);
$nbCommunautes = count($mesCommunautes ?? []);
?>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon tableau de bord</h1>
            <p class="text-gray-500 mt-1 text-sm">Gérez vos communautés</p>
        </div>
        <?php if ($hasAbonnement): ?>
        <a href="/app/communautes/creer"
           class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Créer une communauté
        </a>
        <?php else: ?>
        <a href="/abonnement"
           class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Créer une communauté
        </a>
        <?php endif; ?>
    </div>

    <!-- Banner abonnement si pas de plan payant -->
    <?php if (!$hasAbonnement && $nbCommunautes === 0): ?>
    <div class="bg-violet-50 border border-violet-200 p-6 mb-8 flex items-center gap-5">
        <div class="w-12 h-12 bg-violet-500 flex items-center justify-center flex-shrink-0">
            <i data-lucide="sparkles" class="w-6 h-6 text-white"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-gray-900">Créez votre propre communauté</h3>
            <p class="text-sm text-gray-600 mt-1">Un abonnement est requis pour créer une communauté. Rejoindre une communauté existante est gratuit.</p>
        </div>
        <a href="/abonnement" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 transition text-sm flex-shrink-0">
            Voir les plans
        </a>
    </div>
    <?php endif; ?>

    <!-- Info abonnement actuel -->
    <?php if ($hasAbonnement): ?>
    <div class="bg-white border border-gray-200 p-5 mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Plan <?= htmlspecialchars($abonnement['plan_nom']) ?></p>
                <p class="text-xs text-gray-500">Actif jusqu'au <?= date('d/m/Y', strtotime($abonnement['periode_fin'])) ?></p>
            </div>
        </div>
        <a href="/abonnement" class="text-sm text-violet-600 hover:text-violet-700 font-medium transition">
            Gérer l'abonnement
        </a>
    </div>
    <?php endif; ?>

    <!-- Mes communautés -->
    <?php if (!empty($mesCommunautes)): ?>
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Mes communautés</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php
        foreach ($mesCommunautes as $communaute):
            $cColor = $communaute['couleur_principale'] ?? '#7830E0';
            $cColorLight = $cColor . '20';
            $cLogo = !empty($communaute['logo']) ? $storage->url($communaute['logo']) : '';
            $cCover = !empty($communaute['image_couverture']) ? $storage->url($communaute['image_couverture']) : '';
        ?>
        <a href="/c/<?= htmlspecialchars($communaute['slug']) ?>/feed"
           class="group bg-white border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-200 overflow-hidden">
            <!-- Cover -->
            <div class="h-20 relative" style="background: <?= $cColor ?>;">
                <?php if ($cCover): ?>
                <img src="<?= htmlspecialchars($cCover) ?>" class="w-full h-full object-cover" alt="">
                <?php else: ?>
                <div class="w-full h-full" style="background: linear-gradient(135deg, <?= $cColor ?>, <?= $cColor ?>cc);"></div>
                <?php endif; ?>
            </div>

            <div class="p-5">
                <div class="flex items-center gap-3 -mt-8 mb-3">
                    <div class="w-12 h-12 bg-white border-2 border-white shadow-sm flex items-center justify-center">
                        <?php if ($cLogo): ?>
                        <img src="<?= htmlspecialchars($cLogo) ?>" class="w-10 h-10 object-cover" alt="">
                        <?php else: ?>
                        <span class="font-bold text-lg" style="color: <?= $cColor ?>;"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="font-semibold text-gray-900 transition text-sm"><?= htmlspecialchars($communaute['nom']) ?></h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center text-[10px] px-2 py-0.5 font-medium" style="background: <?= $cColorLight ?>; color: <?= $cColor ?>;">
                        <?= ucfirst(htmlspecialchars($communaute['role'])) ?>
                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty state -->
    <div class="bg-white border border-gray-200 p-16 text-center">
        <div class="w-14 h-14 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="layout-grid" class="w-7 h-7 text-violet-500"></i>
        </div>
        <h3 class="font-semibold text-gray-900 mb-2">Aucune communauté</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            <?php if ($hasAbonnement): ?>
            Créez votre première communauté ou rejoignez-en une existante.
            <?php else: ?>
            Rejoignez une communauté existante ou souscrivez à un plan pour créer la vôtre.
            <?php endif; ?>
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="/decouvrir" class="border border-gray-300 text-gray-700 font-semibold px-5 py-2.5 transition text-sm hover:bg-gray-50">
                Découvrir
            </a>
            <?php if ($hasAbonnement): ?>
            <a href="/app/communautes/creer" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 transition text-sm">
                Créer une communauté
            </a>
            <?php else: ?>
            <a href="/abonnement" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 transition text-sm">
                Voir les plans
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Paramètres du compte -->
    <div class="mt-8 bg-white border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Paramètres du compte</h2>
        <div class="flex flex-wrap gap-3">
            <a href="/app/compte/exporter" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 text-sm text-gray-700 hover:bg-gray-100 transition">
                <i data-lucide="download" class="w-4 h-4"></i>
                Exporter mes données
            </a>
            <a href="/app/compte/supprimer" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-sm text-red-600 hover:bg-red-100 transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                Supprimer mon compte
            </a>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
