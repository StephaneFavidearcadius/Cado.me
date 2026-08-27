<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <!-- Avatar -->
        <div class="flex items-center gap-5 mb-8">
            <div class="w-20 h-20 rounded-full bg-violet-100 flex items-center justify-center">
                <?php if (!empty($utilisateur['avatar'])): ?>
                <img src="<?= htmlspecialchars($utilisateur['avatar']) ?>" class="w-20 h-20 rounded-full object-cover" alt="">
                <?php else: ?>
                <span class="text-violet-600 font-bold text-2xl"><?= strtoupper(substr($utilisateur['prenom'], 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="font-semibold text-gray-900 text-lg"><?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?></h2>
                <p class="text-gray-500 text-sm">@<?= htmlspecialchars($utilisateur['identifiant']) ?></p>
            </div>
        </div>

        <form method="POST" action="/c/<?= $slug ?>/profil" class="space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Biographie</label>
                <textarea name="biographie" rows="3"
                          class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"
                          placeholder="Parlez-nous de vous..."><?= htmlspecialchars($utilisateur['biographie'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" disabled
                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500">
                <p class="text-xs text-gray-400 mt-1">L'email ne peut pas être modifié pour le moment.</p>
            </div>

            <button type="submit"
                    class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-violet-500/25">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</div>
