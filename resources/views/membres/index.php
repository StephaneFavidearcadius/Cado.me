<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Membres</h1>
        <p class="text-gray-500 mt-1"><?= count($membres) ?> membre(s) dans cette communauté</p>
    </div>

    <?php if (!empty($membres)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($membres as $membre): ?>
        <a href="/c/<?= $slug ?>/membres/<?= htmlspecialchars($membre['identifiant']) ?>"
           class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 font-bold"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 text-sm group-hover:text-violet-600 transition truncate">
                        <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>
                    </p>
                    <p class="text-xs text-gray-400">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                    <span class="inline-block mt-1 text-xs bg-violet-50 text-violet-600 px-2 py-0.5 rounded-full font-medium">
                        <?= ucfirst(htmlspecialchars($membre['role'])) ?>
                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <p class="text-gray-500">Aucun membre pour le moment.</p>
    </div>
    <?php endif; ?>
</div>
