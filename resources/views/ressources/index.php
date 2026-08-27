<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Ressources</h1>
        <p class="text-gray-500 mt-1"><?= count($ressources) ?> ressource(s)</p>
    </div>

    <?php if (!empty($ressources)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($ressources as $ressource): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 text-sm truncate"><?= htmlspecialchars($ressource['titre']) ?></p>
                    <p class="text-xs text-gray-400"><?= ucfirst(htmlspecialchars($ressource['type'])) ?></p>
                </div>
            </div>
            <?php if (!empty($ressource['description'])): ?>
            <p class="text-sm text-gray-500 line-clamp-2"><?= htmlspecialchars($ressource['description']) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="folder" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune ressource</h3>
        <p class="text-gray-500">Les ressources partagées apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
