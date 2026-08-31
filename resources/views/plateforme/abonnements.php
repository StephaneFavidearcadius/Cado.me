<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des abonnements</h1>
    <p class="text-gray-500 mt-1"><?= count($abonnements) ?> abonnement(s) récent(s)</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Communauté</th>
                    <th class="px-6 py-3 font-medium">Plan</th>
                    <th class="px-6 py-3 font-medium">Montant</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Période</th>
                    <th class="px-6 py-3 font-medium">Fournisseur</th>
                    <th class="px-6 py-3 font-medium">Créé le</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($abonnements)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <i data-lucide="credit-card" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>Aucun abonnement enregistré</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($abonnements as $abo): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="/c/<?= htmlspecialchars($abo['communaute_slug']) ?>" class="font-medium text-gray-900 hover:text-violet-600 transition">
                            <?= htmlspecialchars($abo['communaute_nom']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($abo['plan_nom']) ?></td>
                    <td class="px-6 py-4 font-semibold text-gray-900"><?= number_format($abo['prix_mensuel'], 0, ',', ' ') ?> F/mois</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $abo['statut'] === 'actif' ? 'bg-emerald-50 text-emerald-600' : ($abo['statut'] === 'annule' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') ?>">
                            <?= ucfirst(htmlspecialchars($abo['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">
                        <?= date('d/m/Y', strtotime($abo['periode_debut'])) ?> → <?= date('d/m/Y', strtotime($abo['periode_fin'])) ?>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= htmlspecialchars($abo['fournisseur'] ?? '—') ?></td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y', strtotime($abo['date_creation'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
