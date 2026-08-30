<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-5xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Formations</h1>
            <p class="text-gray-500 mt-1"><?= count($formations) ?> formation(s)</p>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="text-white font-semibold px-5 py-2 text-sm transition" style="background: var(--comm-color);">
            <span x-show="!showForm">+ Nouvelle formation</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer une formation</h2>
        <form method="POST" action="/c/<?= $slug ?>/formations" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="text-white font-semibold px-6 py-2 text-sm transition" style="background: var(--comm-color);">Créer</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($formations)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($formations as $formation): ?>
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>"
           class="bg-white border border-gray-100 overflow-hidden hover:shadow-lg transition group">
            <div class="h-32 relative flex items-center justify-center" style="background: var(--comm-color);">
                <i data-lucide="book-open" class="w-10 h-10 text-white/80"></i>
            </div>
            <div class="p-5">
                <h3 class="font-semibold text-gray-900 transition" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''"  ><?= htmlspecialchars($formation['titre']) ?></h3>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($formation['description']) ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs bg-violet-50 text-violet-600 px-2 py-1 font-medium">
                        <?= $formation['nombre_lecons'] ?> leçon(s)
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 font-medium">
                        <?= ucfirst(htmlspecialchars($formation['statut'])) ?>
                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-5" style="background: var(--comm-color-light);">
            <i data-lucide="book-open" class="w-8 h-8" style="color: var(--comm-color);"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune formation</h3>
        <p class="text-gray-500">Les formations apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
