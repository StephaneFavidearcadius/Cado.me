<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon tableau de bord</h1>
            <p class="text-gray-500 mt-1">Gérez vos communautés</p>
        </div>
        <a href="/app/communautes/creer"
           class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-lg shadow-violet-500/25">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Créer une communauté
        </a>
    </div>

    <!-- Mes communautés -->
    <?php if (!empty($mesCommunautes)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($mesCommunautes as $communaute): ?>
        <a href="/c <?= htmlspecialchars($communaute['slug']) ?>/app"
           class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
            <!-- Couverture -->
            <div class="h-24 bg-gradient-to-r from-violet-500 to-violet-400 relative">
                <?php if (!empty($communaute['image_couverture'])): ?>
                <img src="<?= htmlspecialchars($communaute['image_couverture']) ?>" class="w-full h-full object-cover" alt="">
                <?php endif; ?>
            </div>

            <div class="p-5">
                <div class="flex items-center gap-3 -mt-10 mb-3">
                    <div class="w-14 h-14 rounded-xl bg-white border-2 border-white shadow-md flex items-center justify-center">
                        <?php if (!empty($communaute['logo'])): ?>
                        <img src="<?= htmlspecialchars($communaute['logo']) ?>" class="w-12 h-12 rounded-lg object-cover" alt="">
                        <?php else: ?>
                        <span class="text-violet-600 font-bold text-xl"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition"><?= htmlspecialchars($communaute['nom']) ?></h3>
                <p class="text-sm text-gray-500 mt-1">Vous êtes <?= ucfirst(htmlspecialchars($communaute['role'])) ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty state -->
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="users" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune communauté</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Créez votre première communauté ou rejoignez-en une existante.</p>
        <a href="/app/communautes/creer" class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-3 rounded-xl transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Créer ma première communauté
        </a>
    </div>
    <?php endif; ?>
</div>
