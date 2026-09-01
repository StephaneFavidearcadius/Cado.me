<div class="max-w-lg mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Supprimer mon compte</h1>
        <p class="text-gray-500 mt-1">Cette action est irréversible.</p>
    </div>

    <!-- Avertissements -->
    <div class="bg-red-50 border border-red-200 p-6 mb-6">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-semibold text-red-800 mb-2">Attention</h3>
                <ul class="text-sm text-red-700 space-y-1.5">
                    <li>• Votre profil sera anonymisé et désactivé</li>
                    <li>• Vos likes et favoris seront supprimés</li>
                    <li>• Vous serez retiré de toutes vos communautés</li>
                    <li>• Cette action ne peut pas être annulée</li>
                </ul>
            </div>
        </div>
    </div>

    <?php if ($nbCommunautesPossedees > 0): ?>
    <div class="bg-amber-50 border border-amber-200 p-6 mb-6">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-semibold text-amber-800 mb-1">Propriétaire de communautés</h3>
                <p class="text-sm text-amber-700">
                    Vous êtes propriétaire de <strong><?= $nbCommunautesPossedees ?></strong> communauté(s).
                    Vous devez transférer la propriété ou supprimer vos communautés avant de pouvoir supprimer votre compte.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Export option -->
    <div class="bg-white border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-3 mb-3">
            <i data-lucide="download" class="w-5 h-5 text-violet-600"></i>
            <h3 class="font-semibold text-gray-900">Avant de partir...</h3>
        </div>
        <p class="text-sm text-gray-600 mb-4">Vous pouvez exporter toutes vos données avant la suppression.</p>
        <a href="/app/compte/exporter" class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 text-violet-600 text-sm font-medium hover:bg-violet-100 transition">
            <i data-lucide="download" class="w-4 h-4"></i>
            Exporter mes données (JSON)
        </a>
    </div>

    <!-- Formulaire de suppression -->
    <div class="bg-white border border-red-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Confirmer la suppression</h3>
        <form method="POST" action="/app/compte/supprimer" onsubmit="return confirm('Êtes-vous ABSOLUMENT sûr ? Cette action est irréversible.')">
            <?= \App\Core\Csrf::field() ?>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Votre mot de passe</label>
                <input type="password" name="mot_de_passe" required
                       class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none"
                       placeholder="Confirmez avec votre mot de passe">
            </div>

            <button type="submit"
                    class="w-full py-3 bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition
                    <?= $nbCommunautesPossedees > 0 ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    <?= $nbCommunautesPossedees > 0 ? 'disabled' : '' ?>>
                Supprimer définitivement mon compte
            </button>
        </form>
    </div>
</div>
