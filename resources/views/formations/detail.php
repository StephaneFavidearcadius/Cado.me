<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/formations" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux formations
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($formation['titre']) ?></h1>
        <?php if (!empty($formation['description'])): ?>
        <p class="text-gray-500 mt-1"><?= htmlspecialchars($formation['description']) ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($lecons)): ?>
    <div class="space-y-3">
        <?php foreach ($lecons as $index => $lecon): ?>
        <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-10 h-10 bg-violet-100 rounded-full flex items-center justify-center flex-shrink-0">
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
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="book-open" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune leçon</h3>
        <p class="text-gray-500">Les leçons seront ajoutées prochainement.</p>
    </div>
    <?php endif; ?>
</div>
