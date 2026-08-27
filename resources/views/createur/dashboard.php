<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon tableau de bord</h1>
            <p class="text-gray-500 mt-1 text-sm">Gérez vos communautés</p>
        </div>
        <a href="/app/communautes/creer"
           class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Créer une communauté
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="layout-grid" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?= count($mesCommunautes ?? []) ?></div>
                    <div class="text-xs text-gray-500">Communautés</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">—</div>
                    <div class="text-xs text-gray-500">Total membres</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">—</div>
                    <div class="text-xs text-gray-500">Publications</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes communautés -->
    <?php if (!empty($mesCommunautes)): ?>
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Mes communautés</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($mesCommunautes as $communaute): ?>
        <a href="/c/<?= htmlspecialchars($communaute['slug']) ?>/app"
           class="group bg-white rounded-2xl border border-gray-100 hover:border-violet-200 hover:shadow-md transition-all duration-200 overflow-hidden">
            <!-- Cover -->
            <div class="h-20 bg-gradient-to-r from-violet-500 to-violet-400 relative">
                <?php if (!empty($communaute['image_couverture'])): ?>
                <img src="<?= htmlspecialchars($communaute['image_couverture']) ?>" class="w-full h-full object-cover" alt="">
                <?php endif; ?>
            </div>

            <div class="p-5">
                <div class="flex items-center gap-3 -mt-8 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-white border-2 border-white shadow-sm flex items-center justify-center">
                        <?php if (!empty($communaute['logo'])): ?>
                        <img src="<?= htmlspecialchars($communaute['logo']) ?>" class="w-10 h-10 rounded-lg object-cover" alt="">
                        <?php else: ?>
                        <span class="text-violet-600 font-bold text-lg"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition text-sm"><?= htmlspecialchars($communaute['nom']) ?></h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center text-[10px] bg-violet-50 text-violet-600 px-2 py-0.5 rounded-full font-medium">
                        <?= ucfirst(htmlspecialchars($communaute['role'])) ?>
                    </span>
                    <span class="text-[10px] text-gray-400">•</span>
                    <span class="text-[10px] text-gray-400">Activé</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty state -->
    <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
        <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="layout-grid" class="w-7 h-7 text-violet-500"></i>
        </div>
        <h3 class="font-semibold text-gray-900 mb-2">Aucune communauté</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Créez votre première communauté ou rejoignez-en une existante.</p>
        <a href="/app/communautes/creer" class="inline-flex items-center gap-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-3 rounded-xl transition text-sm shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Créer ma première communauté
        </a>
    </div>
    <?php endif; ?>
</div>

<script>lucide.createIcons();</script>
