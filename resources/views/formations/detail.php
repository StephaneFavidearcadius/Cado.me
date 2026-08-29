<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-3xl mx-auto" x-data="{ showForm: false }">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/formations" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux formations
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($formation['titre']) ?></h1>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-gray-500 mt-1"><?= htmlspecialchars($formation['description']) ?></p>
                <?php endif; ?>
            </div>
            <?php if ($estAdmin): ?>
            <button @click="showForm = !showForm" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-4 py-2 text-sm transition">
                <span x-show="!showForm">+ Leçon</span>
                <span x-show="showForm">Annuler</span>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulaire création leçon -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Ajouter une leçon</h2>
        <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/lecons" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contenu (HTML supporté)</label>
                <textarea name="contenu" rows="5" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none font-mono text-xs"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL vidéo (optionnel)</label>
                <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <div class="flex items-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                    <input type="number" name="ordre" value="0" class="w-20 px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2.5 text-sm transition mt-5">Ajouter</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($lecons)): ?>
    <div class="space-y-3">
        <?php foreach ($lecons as $index => $lecon): ?>
        <div class="bg-white border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="text-violet-600 font-bold text-sm"><?= $index + 1 ?></span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($lecon['titre']) ?></h3>
                <?php if (!empty($lecon['description'])): ?>
                <p class="text-xs text-gray-500 mt-0.5 truncate"><?= htmlspecialchars($lecon['description']) ?></p>
                <?php endif; ?>
            </div>
            <?php if (!empty($lecon['video_url'])): ?>
            <div class="flex items-center gap-1 text-xs text-gray-400">
                <i data-lucide="play-circle" class="w-4 h-4"></i> Vidéo
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="book-open" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune leçon</h3>
        <p class="text-gray-500">Ajoutez des leçons à cette formation.</p>
    </div>
    <?php endif; ?>
</div>
