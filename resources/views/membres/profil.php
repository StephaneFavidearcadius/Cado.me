<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/membres" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux membres
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <!-- Cover -->
        <div class="h-32 bg-gradient-to-r from-violet-500 to-violet-400"></div>

        <div class="p-8 -mt-12">
            <div class="flex items-end gap-5 mb-6">
                <div class="w-24 h-24 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center">
                    <?php if (!empty($membre['avatar'])): ?>
                    <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-22 h-22 rounded-full object-cover" alt="">
                    <?php else: ?>
                    <span class="text-violet-600 font-bold text-3xl"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?></h1>
                    <p class="text-gray-500">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                </div>
            </div>

            <?php if (!empty($membre['biographie'])): ?>
            <div class="mb-6">
                <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($membre['biographie'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="text-sm text-gray-400">
                Membre depuis <?= date('d/m/Y', strtotime($membre['date_creation'])) ?>
            </div>
        </div>
    </div>
</div>
