<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Paramètres plateforme</h1>
    <p class="text-gray-500 mt-1">Configuration générale de Cado.me</p>
</div>

<form method="POST" action="/admin/parametres" class="max-w-2xl">
    <?= \App\Core\Csrf::field() ?>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-4">Informations générales</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la plateforme</label>
                <input type="text" name="nom_plateforme" value="<?= htmlspecialchars($parametres['nom_plateforme'] ?? 'Cado.me') ?>"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description_plateforme" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"><?= htmlspecialchars($parametres['description_plateforme'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                <input type="email" name="email_contact" value="<?= htmlspecialchars($parametres['email_contact'] ?? '') ?>"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-4">Maintenance</h2>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="maintenance" <?= !empty($parametres['maintenance']) ? 'checked' : '' ?>
                   class="w-5 h-5 rounded border-gray-300 text-violet-600 focus:ring-violet-500">
            <div>
                <p class="text-sm font-medium text-gray-900">Mode maintenance</p>
                <p class="text-xs text-gray-500">Désactive l'accès public à la plateforme. Seuls les super admins pourront se connecter.</p>
            </div>
        </label>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-violet-700 transition">
            Sauvegarder les paramètres
        </button>
    </div>
</form>
