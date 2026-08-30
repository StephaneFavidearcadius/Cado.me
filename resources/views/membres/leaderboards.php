<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Classement</h1>
        <p class="text-gray-500 mt-1">Les membres les plus actifs</p>
    </div>

    <?php if (!empty($classement)): ?>
    <div class="space-y-2">
        <?php foreach ($classement as $index => $membre): ?>
        <div class="bg-white border border-gray-100 p-4 flex items-center gap-4 <?= $index < 3 ? 'border-l-4' : '' ?>"
             style="<?= $index === 0 ? 'border-left-color: #FFD700;' : ($index === 1 ? 'border-left-color: #C0C0C0;' : ($index === 2 ? 'border-left-color: #CD7F32;' : '')) ?>">
            <!-- Rang -->
            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                <?php if ($index === 0): ?>
                <span class="text-lg">🥇</span>
                <?php elseif ($index === 1): ?>
                <span class="text-lg">🥈</span>
                <?php elseif ($index === 2): ?>
                <span class="text-lg">🥉</span>
                <?php else: ?>
                <span class="text-sm font-bold text-gray-400"><?= $index + 1 ?></span>
                <?php endif; ?>
            </div>

            <!-- Avatar + Nom -->
            <a href="/c/<?= $slug ?>/membres/<?= htmlspecialchars($membre['identifiant']) ?>" class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                    <?php if (!empty($membre['avatar'])): ?>
                    <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-10 h-10 object-cover" alt="">
                    <?php else: ?>
                    <span class="font-bold text-sm" style="color: var(--comm-color);"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?></p>
                    <p class="text-xs text-gray-400">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                </div>
            </a>

            <!-- Points -->
            <div class="text-right flex-shrink-0">
                <p class="font-bold text-lg" style="color: var(--comm-color);"><?= $membre['points'] ?? 0 ?></p>
                <p class="text-[10px] text-gray-400 uppercase">points</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-5" style="background: var(--comm-color-light);">
            <i data-lucide="trophy" class="w-8 h-8" style="color: var(--comm-color);"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pas encore de classement</h3>
        <p class="text-gray-500">Le classement apparaîtra quand les membres seront actifs.</p>
    </div>
    <?php endif; ?>
</div>
