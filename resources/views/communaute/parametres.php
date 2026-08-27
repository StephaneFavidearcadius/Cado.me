<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/gestion" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la gestion
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Paramètres</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <form method="POST" action="/c/<?= $slug ?>/gestion/parametres" class="space-y-6">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($communaute['nom']) ?>"
                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"><?= htmlspecialchars($communaute['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Visibilité</label>
                <select name="visibilite" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    <option value="publique" <?= $communaute['visibilite'] === 'publique' ? 'selected' : '' ?>>Publique</option>
                    <option value="privee" <?= $communaute['visibilite'] === 'privee' ? 'selected' : '' ?>>Privée</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Couleur principale</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="couleur_principale"
                           value="<?= htmlspecialchars($communaute['couleur_principale'] ?? '#7830E0') ?>"
                           class="w-12 h-12 rounded-xl border-2 border-gray-200 cursor-pointer">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-violet-500/25">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
