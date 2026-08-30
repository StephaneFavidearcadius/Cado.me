<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        </div>
        <?php if (!empty($notifications)): ?>
        <form method="POST" action="/c/<?= $slug ?>/notifications/tout-lu">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="text-sm font-medium transition" style="color: var(--comm-color);">
                Tout marquer comme lu
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if (!empty($notifications)): ?>
    <div class="space-y-2">
        <?php foreach ($notifications as $notif): ?>
        <div class="bg-white border border-gray-100 p-4 flex items-center gap-4" style="<?= !$notif['lue'] ? 'border-left: 4px solid var(--comm-color);' : '' ?>">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                <i data-lucide="bell" class="w-5 h-5" style="color: var(--comm-color);"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($notif['titre']) ?></p>
                <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($notif['message']) ?></p>
            </div>
            <span class="text-xs text-gray-400 whitespace-nowrap"><?= date('d/m H:i', strtotime($notif['date_creation'])) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>        <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-5" style="background: var(--comm-color-light);">
            <i data-lucide="bell" class="w-8 h-8" style="color: var(--comm-color);"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune notification</h3>
        <p class="text-gray-500">Vous êtes à jour !</p>
    </div>
    <?php endif; ?>
</div>
