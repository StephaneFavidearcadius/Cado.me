<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-4xl mx-auto">
    <!-- Header avec boutons filtre -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Membres</h1>
            <span class="bg-violet-100 text-violet-600 text-xs font-bold px-2.5 py-1">
                <?= count($membres) ?>
            </span>
        </div>
        <?php if ($estAdmin): ?>
        <button class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            INVITER
        </button>
        <?php endif; ?>
    </div>

    <!-- Filtres -->
    <div class="flex items-center gap-2 mb-6">
        <span class="px-3 py-1.5 text-sm font-medium bg-gray-900 text-white">
            Tous (<?= count($membres) ?>)
        </span>
    </div>

    <?php if (!empty($membres)): ?>
    <div class="space-y-0">
        <?php foreach ($membres as $membre): ?>
        <div class="bg-white border border-gray-100 p-5 flex items-center gap-4">
            <!-- Avatar -->
            <a href="/c/<?= $slug ?>/membres/<?= htmlspecialchars($membre['identifiant']) ?>" class="flex-shrink-0">
                <?php if (!empty($membre['avatar'])): ?>
                <img src="<?= htmlspecialchars($membre['avatar']) ?>" class="w-12 h-12 object-cover" alt="">
                <?php else: ?>
                <div class="w-12 h-12 bg-violet-100 flex items-center justify-center">
                    <span class="text-violet-600 font-bold"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                </div>
                <?php endif; ?>
            </a>

            <!-- Infos -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <a href="/c/<?= $slug ?>/membres/<?= htmlspecialchars($membre['identifiant']) ?>" class="font-semibold text-gray-900 text-sm hover:text-violet-600 transition">
                        <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>
                    </a>
                    <?php if ($membre['role'] === 'proprietaire'): ?>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 text-white" style="background: var(--comm-color);">ADMIN</span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-gray-400">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                <?php if (!empty($membre['biographie'])): ?>
                <p class="text-sm text-gray-600 mt-1 line-clamp-1"><?= htmlspecialchars($membre['biographie']) ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-400"></span> En ligne
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        Rejoint <?= date('M Y', strtotime($membre['date_adhesion'] ?? 'now')) ?>
                    </span>
                </div>
            </div>

            <!-- Bouton CHAT -->
            <?php if ((int)$membre['utilisateur_id'] !== (int)($_SESSION['utilisateur_id'] ?? 0)): ?>
            <a href="/c/<?= $slug ?>/membres/<?= $membre['utilisateur_id'] ?>/chat" class="flex items-center gap-1.5 px-4 py-2 border border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-400 transition flex-shrink-0">
                DISCUTER
                <i data-lucide="message-circle" class="w-4 h-4"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="users" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun membre</h3>
        <p class="text-gray-500">Les membres apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
