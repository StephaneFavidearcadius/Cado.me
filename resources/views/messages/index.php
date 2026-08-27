<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
    </div>

    <?php if (!empty($conversations)): ?>
    <div class="space-y-2">
        <?php foreach ($conversations as $conv): ?>
        <div class="bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition cursor-pointer flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="message-circle" class="w-6 h-6 text-violet-500"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm">Conversation #<?= $conv['id'] ?></p>
                <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($conv['dernier_message'] ?? 'Aucun message') ?></p>
            </div>
            <?php if ($conv['non_lus'] > 0): ?>
            <span class="bg-violet-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                <?= $conv['non_lus'] ?>
            </span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="message-circle" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune conversation</h3>
        <p class="text-gray-500">Commencez à discuter avec les membres.</p>
    </div>
    <?php endif; ?>
</div>
