<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Événements</h1>
        <p class="text-gray-500 mt-1"><?= count($evenements) ?> événement(s)</p>
    </div>

    <?php if (!empty($evenements)): ?>
    <div class="space-y-4">
        <?php foreach ($evenements as $evenement): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-start gap-4">
                <!-- Date -->
                <div class="w-16 h-16 bg-violet-100 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 text-lg font-bold"><?= date('d', strtotime($evenement['date_debut'])) ?></span>
                    <span class="text-violet-500 text-xs font-medium"><?= date('M', strtotime($evenement['date_debut'])) ?></span>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($evenement['titre']) ?></h3>
                    <?php if (!empty($evenement['description'])): ?>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($evenement['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-3 mt-3 text-sm text-gray-400">
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <?= date('H:i', strtotime($evenement['date_debut'])) ?>
                            <?php if ($evenement['date_fin']): ?>
                            — <?= date('H:i', strtotime($evenement['date_fin'])) ?>
                            <?php endif; ?>
                        </span>
                        <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">
                            <?= ucfirst(htmlspecialchars($evenement['type'])) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="calendar" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun événement</h3>
        <p class="text-gray-500">Les événements à venir apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
