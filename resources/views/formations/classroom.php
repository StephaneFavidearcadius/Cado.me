<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-6xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Classe</h1>
            <p class="text-gray-500 mt-1"><?= count($formations) ?> cours</p>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            <span x-show="!showForm">+ Nouveau cours</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer un cours</h2>
        <form method="POST" action="/c/<?= $slug ?>/formations" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                    <input type="number" name="ordre" value="0" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2 text-sm transition">Créer</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($formations)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($formations as $formation): ?>
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>"
           class="bg-white border border-gray-100 overflow-hidden hover:shadow-lg transition group">
            <!-- Cover area with colored background -->
            <div class="h-36 relative flex items-center justify-center overflow-hidden"
                 style="background: linear-gradient(135deg, var(--comm-color)22, var(--comm-color)44);">
                <i data-lucide="book-open" class="w-12 h-12" style="color: var(--comm-color); opacity: 0.6;"></i>
            </div>
            <div class="p-5">
                <h3 class="font-bold text-gray-900 group-hover:text-violet-600 transition mb-1">
                    <?= htmlspecialchars_decode($formation['titre']) ?>
                </h3>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-sm text-gray-500 line-clamp-2 mb-3"><?= htmlspecialchars_decode($formation['description']) ?></p>
                <?php endif; ?>
                <!-- Progress bar -->
                <div class="w-full bg-gray-100 h-1.5 mt-2">
                    <div class="h-1.5" style="width: 0%; background: var(--comm-color);"></div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-gray-400"><?= $formation['nombre_lecons'] ?> leçon(s)</span>
                    <span class="text-xs font-medium" style="color: var(--comm-color);">0%</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="book-open" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun cours</h3>
        <p class="text-gray-500">Les cours apparaîtront ici une fois créés.</p>
    </div>
    <?php endif; ?>
</div>
