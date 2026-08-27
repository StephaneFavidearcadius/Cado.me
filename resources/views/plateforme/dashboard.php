<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord plateforme</h1>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-violet-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Utilisateurs</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['utilisateurs']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                <i data-lucide="layout-grid" class="w-6 h-6 text-violet-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Communautés actives</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['communautes']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                <i data-lucide="message-square" class="w-6 h-6 text-violet-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Publications</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['publications']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Dernières communautés -->
<div class="bg-white rounded-2xl border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-900">Dernières communautés</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Nom</th>
                    <th class="px-6 py-3 font-medium">Propriétaire</th>
                    <th class="px-6 py-3 font-medium">Membres</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Créée le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($communautes as $comm): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="/c/<?= htmlspecialchars($comm['slug']) ?>" class="font-medium text-gray-900 hover:text-violet-600 transition">
                            <?= htmlspecialchars($comm['nom']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($comm['prenom'] . ' ' . $comm['proprietaire_nom']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= $comm['nombre_membres'] ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full <?= $comm['statut'] === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                            <?= ucfirst(htmlspecialchars($comm['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400"><?= date('d/m/Y', strtotime($comm['date_creation'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
