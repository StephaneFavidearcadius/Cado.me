<?php
$hasAbonnement = !empty($abonnement);
?>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Choisissez votre plan</h1>
        <p class="text-gray-500 max-w-xl mx-auto">
            Un abonnement est requis pour créer votre propre communauté. Rejoindre une communauté existante est gratuit.
        </p>
    </div>

    <!-- Abonnement actuel -->
    <?php if ($hasAbonnement): ?>
    <div class="bg-emerald-50 border border-emerald-200 p-5 mb-8 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-500 flex items-center justify-center flex-shrink-0">
            <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-gray-900">Abonnement actif : Plan <?= htmlspecialchars($abonnement['plan_nom']) ?></p>
            <p class="text-sm text-gray-600">Valide jusqu'au <?= date('d/m/Y', strtotime($abonnement['periode_fin'])) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Plans payants uniquement -->
    <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
        <?php foreach ($plans as $plan): ?>
        <?php
            $isPremium = ($plan['prix_mensuel'] >= 97);
            $isCurrentPlan = ($hasAbonnement && $abonnement['plan_id'] == $plan['id']);
        ?>
        <div class="<?= $isPremium ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200' ?> p-8 relative">
            <?php if ($isPremium): ?>
            <div class="absolute top-4 right-4 bg-white/20 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-wide">Populaire</div>
            <?php endif; ?>

            <div class="text-xs font-semibold uppercase tracking-wide mb-3 <?= $isPremium ? 'text-violet-200' : 'text-gray-400' ?>">
                <?= htmlspecialchars($plan['nom']) ?>
            </div>
            <div class="text-4xl font-bold mb-1"><?= number_format($plan['prix_mensuel'], 0, ',', ' ') ?>€</div>
            <div class="text-sm <?= $isPremium ? 'text-violet-200' : 'text-gray-500' ?> mb-6">par mois</div>

            <ul class="space-y-3 mb-8">
                <li class="flex items-center gap-3 text-sm <?= $isPremium ? 'text-violet-100' : 'text-gray-600' ?>">
                    <i data-lucide="check" class="w-4 h-4 <?= $isPremium ? 'text-white' : 'text-violet-500' ?> flex-shrink-0"></i>
                    <?= $plan['limite_communautes'] >= 999 ? 'Communautés illimitées' : 'Jusqu\'à ' . $plan['limite_communautes'] . ' communauté(s)' ?>
                </li>
                <li class="flex items-center gap-3 text-sm <?= $isPremium ? 'text-violet-100' : 'text-gray-600' ?>">
                    <i data-lucide="check" class="w-4 h-4 <?= $isPremium ? 'text-white' : 'text-violet-500' ?> flex-shrink-0"></i>
                    <?= $plan['limite_membres'] >= 99999 ? 'Membres illimités' : 'Jusqu\'à ' . number_format($plan['limite_membres']) . ' membres' ?>
                </li>
                <li class="flex items-center gap-3 text-sm <?= $isPremium ? 'text-violet-100' : 'text-gray-600' ?>">
                    <i data-lucide="check" class="w-4 h-4 <?= $isPremium ? 'text-white' : 'text-violet-500' ?> flex-shrink-0"></i>
                    <?= $plan['limite_formations'] >= 999 ? 'Formations illimitées' : $plan['limite_formations'] . ' formation(s)' ?>
                </li>
                <li class="flex items-center gap-3 text-sm <?= $isPremium ? 'text-violet-100' : 'text-gray-600' ?>">
                    <i data-lucide="check" class="w-4 h-4 <?= $isPremium ? 'text-white' : 'text-violet-500' ?> flex-shrink-0"></i>
                    <?= round($plan['limite_stockage'] / 1073741824) ?> Go de stockage
                </li>
                <?php if ($isPremium): ?>
                <li class="flex items-center gap-3 text-sm text-violet-100">
                    <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                    Support prioritaire
                </li>
                <?php endif; ?>
            </ul>

            <?php if ($isCurrentPlan): ?>
            <div class="block w-full text-center py-3 <?= $isPremium ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' ?> font-semibold text-sm">
                Plan actuel
            </div>
            <?php else: ?>
            <form method="POST" action="/abonnement/souscrire">
                <?= \App\Core\Csrf::field() ?>
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                <button type="submit" class="block w-full text-center py-3 <?= $isPremium ? 'bg-white text-violet-600 hover:bg-violet-50' : 'bg-violet-500 text-white hover:bg-violet-600' ?> font-semibold text-sm transition">
                    Choisir <?= htmlspecialchars($plan['nom']) ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Note simulation -->
    <p class="text-center text-xs text-gray-400 mt-6">Mode simulation — aucun paiement réel ne sera effectué</p>

    <!-- Retour -->
    <div class="text-center mt-4">
        <a href="/app" class="text-sm text-gray-500 hover:text-gray-700 transition">← Retour au tableau de bord</a>
    </div>
</div>

<script>lucide.createIcons();</script>
