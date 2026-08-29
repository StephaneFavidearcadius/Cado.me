<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-3xl mx-auto" x-data="{ showLeconForm: false, showModuleForm: false }">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/classroom" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la classe
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars_decode($formation['titre']) ?></h1>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-gray-500 mt-1"><?= htmlspecialchars_decode($formation['description']) ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                    <span><?= count($modules) ?> module(s)</span>
                    <span><?= count($lecons) + array_sum(array_map(fn($m) => count($m['lecons']), $modules)) ?> leçon(s)</span>
                </div>
            </div>
            <?php if ($estAdmin): ?>
            <div class="flex items-center gap-2">
                <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modifier" class="border border-gray-200 hover:border-violet-300 text-gray-700 font-medium px-4 py-2 text-sm transition">
                    Modifier
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modules avec leçons -->
    <?php if (!empty($modules)): ?>
    <div class="space-y-6">
        <?php foreach ($modules as $modIndex => $module): ?>
        <div class="bg-white border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-violet-100 flex items-center justify-center">
                        <span class="text-violet-600 font-bold text-sm"><?= $modIndex + 1 ?></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900"><?= htmlspecialchars_decode($module['titre']) ?></h3>
                        <?php if (!empty($module['description'])): ?>
                        <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars_decode($module['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="text-xs text-gray-400"><?= $module['nb_lecons'] ?> leçon(s)</span>
            </div>

            <?php if (!empty($module['lecons'])): ?>
            <div class="divide-y divide-gray-50">
                <?php foreach ($module['lecons'] as $leconIndex => $lecon): ?>
                <div class="px-6 py-3.5 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-500 font-medium text-xs"><?= $leconIndex + 1 ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars_decode($lecon['titre']) ?></p>
                            <?php if (!empty($lecon['description'])): ?>
                            <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars_decode($lecon['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php if (!empty($lecon['video_fichier'])): ?>
                    <span class="flex items-center gap-1 text-xs text-violet-500">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i> Vidéo uploadée
                    </span>
                    <?php elseif (!empty($lecon['video_url'])): ?>
                    <span class="flex items-center gap-1 text-xs text-gray-400">
                        <i data-lucide="play-circle" class="w-3.5 h-3.5"></i> Lien vidéo
                    </span>
                    <?php endif; ?>
                    </div>
                    <?php if (!empty($lecon['video_fichier'])): ?>
                    <div class="mt-3 ml-10">
                        <video controls preload="none" class="w-full max-w-lg bg-black">
                            <source src="/<?= htmlspecialchars($lecon['video_fichier']) ?>" type="video/<?= pathinfo($lecon['video_fichier'], PATHINFO_EXTENSION) ?>">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    </div>
                    <?php elseif (!empty($lecon['video_url'])): ?>
                    <div class="mt-3 ml-10">
                        <a href="<?= htmlspecialchars($lecon['video_url']) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-violet-600 hover:text-violet-700 font-medium">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Voir la vidéo externe
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="px-6 py-4 text-center">
                <p class="text-sm text-gray-400">Aucune leçon</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php elseif (!empty($lecons)): ?>
    <!-- Leçons sans module -->
    <div class="space-y-3">
        <?php foreach ($lecons as $index => $lecon): ?>
        <div class="bg-white border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="text-violet-600 font-bold text-sm"><?= $index + 1 ?></span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-gray-900 text-sm"><?= htmlspecialchars_decode($lecon['titre']) ?></h3>
                <?php if (!empty($lecon['description'])): ?>
                <p class="text-xs text-gray-500 mt-0.5 truncate"><?= htmlspecialchars_decode($lecon['description']) ?></p>
                <?php endif; ?>
            </div>
            <?php if (!empty($lecon['video_url'])): ?>
            <span class="flex items-center gap-1 text-xs text-gray-400">
                <i data-lucide="play-circle" class="w-4 h-4"></i> Vidéo
            </span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="book-open" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun contenu</h3>
        <p class="text-gray-500">Ce cours est vide pour le moment.</p>
        <?php if ($estAdmin): ?>
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modifier" class="inline-block mt-4 bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            Ajouter du contenu
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
