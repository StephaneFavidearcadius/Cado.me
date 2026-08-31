<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord plateforme</h1>
    <p class="text-gray-500 mt-1">Vue d'ensemble de votre plateforme Cado.me</p>
</div>

<!-- Stats principales -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Utilisateurs</p>
                <p class="text-xl font-bold text-gray-900"><?= number_format($stats['utilisateurs']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                <i data-lucide="layout-grid" class="w-5 h-5 text-violet-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Communautés</p>
                <p class="text-xl font-bold text-gray-900"><?= number_format($stats['communautes']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <i data-lucide="message-square" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Publications</p>
                <p class="text-xl font-bold text-gray-900"><?= number_format($stats['publications']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <i data-lucide="credit-card" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Revenus/mois</p>
                <p class="text-xl font-bold text-gray-900"><?= number_format($stats['revenus'], 0, ',', ' ') ?> F</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats secondaires -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Formations</p>
                <p class="text-lg font-bold text-gray-900"><?= number_format($stats['formations']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-pink-50 rounded-lg flex items-center justify-center">
                <i data-lucide="mail" class="w-4 h-4 text-pink-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Messages</p>
                <p class="text-lg font-bold text-gray-900"><?= number_format($stats['messages']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center">
                <i data-lucide="repeat" class="w-4 h-4 text-teal-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Abonnements</p>
                <p class="text-lg font-bold text-gray-900"><?= number_format($stats['abonnements']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Dernières communautés -->
    <div class="bg-white rounded-2xl border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Dernières communautés</h2>
            <a href="/admin/communautes" class="text-sm text-violet-600 hover:text-violet-700">Voir tout →</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php foreach ($communautes as $comm): ?>
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background: <?= htmlspecialchars($comm['couleur_principale'] ?? '#7830E0') ?>">
                        <?= strtoupper(substr($comm['nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <a href="/c/<?= htmlspecialchars($comm['slug']) ?>" class="text-sm font-medium text-gray-900 hover:text-violet-600"><?= htmlspecialchars($comm['nom']) ?></a>
                        <p class="text-xs text-gray-400"><?= $comm['nombre_membres'] ?> membre(s)</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full <?= $comm['statut'] === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                    <?= ucfirst(htmlspecialchars($comm['statut'])) ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Derniers abonnements -->
    <div class="bg-white rounded-2xl border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Derniers abonnements</h2>
            <a href="/admin/abonnements" class="text-sm text-violet-600 hover:text-violet-700">Voir tout →</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if (empty($abonnementsRecents)): ?>
            <div class="px-6 py-8 text-center text-gray-400 text-sm">Aucun abonnement</div>
            <?php else: ?>
            <?php foreach ($abonnementsRecents as $abo): ?>
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($abo['communaute_nom']) ?></p>
                    <p class="text-xs text-gray-400"><?= htmlspecialchars($abo['plan_nom']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900"><?= number_format($abo['prix_mensuel'], 0, ',', ' ') ?> F</p>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $abo['statut'] === 'actif' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                        <?= ucfirst(htmlspecialchars($abo['statut'])) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
