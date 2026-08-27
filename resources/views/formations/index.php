<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Formations</h1>
            <p class="text-gray-500 mt-1"><?= count($formations) ?> formation(s)</p>
        </div>
    </div>

    <?php if (!empty($formations)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($formations as $formation): ?>
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>"
           class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition group">
            <div class="h-32 bg-gradient-to-br from-violet-500 to-violet-400 relative flex items-center justify-center">
                <i data-lucide="book-open" class="w-10 h-10 text-white/80"></i>
            </div>
            <div class="p-5">
                <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition"><?= htmlspecialchars($formation['titre']) ?></h3>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($formation['description']) ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs bg-violet-50 text-violet-600 px-2 py-1 rounded-full font-medium">
                        <?= $formation['nombre_lecons'] ?> leçon(s)
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full font-medium">
                        <?= ucfirst(htmlspecialchars($formation['statut'])) ?>
                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="book-open" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune formation</h3>
        <p class="text-gray-500">Les formations apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
