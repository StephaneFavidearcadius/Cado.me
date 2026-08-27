<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/app" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Créer une communauté</h1>
        <p class="text-gray-500 mt-1">Configurez votre espace communautaire</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <form method="POST" action="/app/communautes" class="space-y-6">
            <?= \App\Core\Csrf::field() ?>

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom de la communauté *</label>
                <input type="text" id="nom" name="nom" required
                       value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition"
                       placeholder="Ma Super Communauté">
                <?php if (!empty($errors['nom'])): ?>
                <p class="mt-1 text-xs text-red-500"><?= $errors['nom'] ?></p>
                <?php endif; ?>
                <p class="mt-1 text-xs text-gray-400">Le slug sera généré automatiquement depuis le nom</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition resize-none"
                          placeholder="Décrivez l'objectif de votre communauté..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <!-- Visibilité -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Visibilité</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative">
                        <input type="radio" name="visibilite" value="publique" class="peer sr-only" <?= ($old['visibilite'] ?? 'privee') === 'publique' ? 'checked' : '' ?>>
                        <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer transition peer-checked:border-violet-500 peer-checked:bg-violet-50">
                            <div class="flex items-center gap-3">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-400 peer-checked:text-violet-500"></i>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Publique</p>
                                    <p class="text-xs text-gray-500">Visible par tous</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" name="visibilite" value="privee" class="peer sr-only" <?= ($old['visibilite'] ?? 'privee') === 'privee' ? 'checked' : '' ?>>
                        <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer transition peer-checked:border-violet-500 peer-checked:bg-violet-50">
                            <div class="flex items-center gap-3">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Privée</p>
                                    <p class="text-xs text-gray-500">Sur invitation</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Couleur principale -->
            <div>
                <label for="couleur_principale" class="block text-sm font-semibold text-gray-700 mb-2">Couleur principale</label>
                <div class="flex items-center gap-3">
                    <input type="color" id="couleur_principale" name="couleur_principale"
                           value="<?= htmlspecialchars($old['couleur_principale'] ?? '#7830E0') ?>"
                           class="w-12 h-12 rounded-xl border-2 border-gray-200 cursor-pointer">
                    <span class="text-sm text-gray-500">#7830E0 (violet Cado.me)</span>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-6 rounded-xl transition shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40">
                    Créer ma communauté
                </button>
            </div>
        </form>
    </div>
</div>
