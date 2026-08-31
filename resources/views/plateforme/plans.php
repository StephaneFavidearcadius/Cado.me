<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Gestion des plans</h1>
        <p class="text-gray-500 mt-1"><?= count($plans) ?> plan(s)</p>
    </div>
    <button @click="$refs.modalPlan.showModal()" class="bg-violet-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-violet-700 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Créer un plan
    </button>
</div>

<!-- Plans grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <?php foreach ($plans as $plan): ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($plan['nom']) ?></h3>
            <span class="text-xs px-2 py-0.5 rounded-full <?= $plan['actif'] ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' ?>">
                <?= $plan['actif'] ? 'Actif' : 'Inactif' ?>
            </span>
        </div>

        <div class="mb-4">
            <p class="text-3xl font-bold text-gray-900"><?= number_format($plan['prix_mensuel'], 0, ',', ' ') ?> <span class="text-sm font-normal text-gray-500">F/mois</span></p>
            <?php if ($plan['prix_annuel'] > 0): ?>
            <p class="text-sm text-gray-400"><?= number_format($plan['prix_annuel'], 0, ',', ' ') ?> F/an</p>
            <?php endif; ?>
        </div>

        <?php if ($plan['description']): ?>
        <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($plan['description']) ?></p>
        <?php endif; ?>

        <div class="space-y-2 text-sm text-gray-600 mb-6">
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                <?= $plan['limite_membres'] ?> membre(s) max
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="hard-drive" class="w-4 h-4 text-gray-400"></i>
                <?= round($plan['limite_stockage'] / 1073741824) ?> Go stockage
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-gray-400"></i>
                <?= $plan['limite_formations'] ?> formation(s)
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="layout-grid" class="w-4 h-4 text-gray-400"></i>
                <?= $plan['limite_communautes'] ?> communauté(s)
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <span class="text-xs text-gray-400"><?= $plan['nb_abonnements'] ?> abonnement(s)</span>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 bottom-full mb-1 w-40 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10">
                    <button @click="$refs.modalEdit_<?= (int)$plan['id'] ?>.showModal(); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>Modifier
                    </button>
                    <?php if ($plan['nb_abonnements'] == 0): ?>
                    <form method="POST" action="/admin/plans/<?= (int)$plan['id'] ?>/supprimer" class="inline"
                          x-data x-on:submit="return confirm('Supprimer ce plan ?')">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <i data-lucide="trash" class="w-4 h-4 inline mr-2"></i>Supprimer
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal -->
    <dialog x-ref="modalEdit_<?= (int)$plan['id'] ?>" class="rounded-2xl shadow-2xl border border-gray-200 p-0 w-full max-w-lg">
        <form method="POST" action="/admin/plans/<?= (int)$plan['id'] ?>/modifier" class="p-6">
            <?= \App\Core\Csrf::field() ?>
            <h3 class="text-lg font-bold mb-4">Modifier le plan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($plan['nom']) ?>" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"><?= htmlspecialchars($plan['description'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix mensuel (F)</label>
                        <input type="number" name="prix_mensuel" value="<?= $plan['prix_mensuel'] ?>" step="100"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix annuel (F)</label>
                        <input type="number" name="prix_annuel" value="<?= $plan['prix_annuel'] ?>" step="100"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Membres max</label>
                        <input type="number" name="limite_membres" value="<?= $plan['limite_membres'] ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stockage (Go)</label>
                        <input type="number" name="limite_stockage" value="<?= round($plan['limite_stockage'] / 1073741824) ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Formations max</label>
                        <input type="number" name="limite_formations" value="<?= $plan['limite_formations'] ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Communautés max</label>
                        <input type="number" name="limite_communautes" value="<?= $plan['limite_communautes'] ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="actif" <?= $plan['actif'] ? 'checked' : '' ?> class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">Plan actif</span>
                </label>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="$refs.modalEdit_<?= (int)$plan['id'] ?>.close()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-xl transition">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm bg-violet-600 text-white rounded-xl hover:bg-violet-700 transition">Sauvegarder</button>
            </div>
        </form>
    </dialog>
    <?php endforeach; ?>
</div>

<!-- Create modal -->
<dialog x-ref="modalPlan" class="rounded-2xl shadow-2xl border border-gray-200 p-0 w-full max-w-lg">
    <form method="POST" action="/admin/plans/creer" class="p-6">
        <?= \App\Core\Csrf::field() ?>
        <h3 class="text-lg font-bold mb-4">Créer un nouveau plan</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du plan</label>
                <input type="text" name="nom" required placeholder="Ex: Premium"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Description du plan..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix mensuel (F)</label>
                    <input type="number" name="prix_mensuel" value="0" step="100"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix annuel (F)</label>
                    <input type="number" name="prix_annuel" value="0" step="100"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Membres max</label>
                    <input type="number" name="limite_membres" value="50"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stockage (Go)</label>
                    <input type="number" name="limite_stockage" value="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Formations max</label>
                    <input type="number" name="limite_formations" value="3"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Communautés max</label>
                    <input type="number" name="limite_communautes" value="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="actif" checked class="rounded border-gray-300">
                <span class="text-sm text-gray-700">Plan actif</span>
            </label>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="$refs.modalPlan.close()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-xl transition">Annuler</button>
            <button type="submit" class="px-4 py-2 text-sm bg-violet-600 text-white rounded-xl hover:bg-violet-700 transition">Créer le plan</button>
        </div>
    </form>
</dialog>
