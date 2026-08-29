<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-5xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ressources</h1>
            <p class="text-gray-500 mt-1"><?= count($ressources) ?> ressource(s)</p>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            <span x-show="!showForm">+ Nouvelle ressource</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Ajouter une ressource</h2>
        <form method="POST" action="/c/<?= $slug ?>/ressources" enctype="multipart/form-data" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                        <option value="fichier">Fichier</option>
                        <option value="lien">Lien</option>
                        <option value="document">Document</option>
                        <option value="image">Image</option>
                        <option value="video">Vidéo</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL (si lien externe)</label>
                <input type="url" name="url" placeholder="https://..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ou uploader un fichier</label>
                <input type="file" name="fichier" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2 text-sm transition">Ajouter</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($ressources)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($ressources as $ressource): ?>
        <div class="bg-white border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                    <i data-lucide="<?= $ressource['type'] === 'lien' ? 'external-link' : 'file-text' ?>" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 text-sm truncate"><?= htmlspecialchars($ressource['titre']) ?></p>
                    <p class="text-xs text-gray-400"><?= ucfirst(htmlspecialchars($ressource['type'])) ?></p>
                </div>
            </div>
            <?php if (!empty($ressource['description'])): ?>
            <p class="text-sm text-gray-500 line-clamp-2"><?= htmlspecialchars($ressource['description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($ressource['url'])): ?>
            <a href="<?= htmlspecialchars($ressource['url']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-violet-600 hover:text-violet-700 mt-2">
                <i data-lucide="external-link" class="w-3 h-3"></i> Ouvrir le lien
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="folder" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune ressource</h3>
        <p class="text-gray-500">Les ressources partagées apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
