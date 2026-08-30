<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-4xl mx-auto" x-data="{ showNewModule: false, showNewLecon: null }">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour au cours
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Modifier : <?= htmlspecialchars($formation['titre']) ?></h1>
                <p class="text-gray-500 mt-1"><?= count($modules) ?> module(s)</p>
            </div>
            <?php if ($estAdmin): ?>
            <button @click="showNewModule = !showNewModule" class="text-white font-semibold px-5 py-2 text-sm transition" style="background: var(--comm-color);">
                <span x-show="!showNewModule">+ Nouveau module</span>
                <span x-show="showNewModule">Annuler</span>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulaire création module -->
    <?php if ($estAdmin): ?>
    <div x-show="showNewModule" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer un module</h2>
        <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modules" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre du module *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                    <input type="number" name="ordre" value="<?= count($modules) + 1 ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="text-white font-semibold px-6 py-2 text-sm transition" style="background: var(--comm-color);">Créer le module</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Liste des modules -->
    <?php if (!empty($modules)): ?>
    <div class="space-y-6">
        <?php foreach ($modules as $modIndex => $module): ?>
        <div class="bg-white border border-gray-100 overflow-hidden">
            <!-- En-tête module -->
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                        <span class="font-bold text-sm" style="color: var(--comm-color);"><?= $modIndex + 1 ?></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900"><?= htmlspecialchars($module['titre']) ?></h3>
                        <?php if (!empty($module['description'])): ?>
                        <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($module['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400"><?= $module['nb_lecons'] ?> leçon(s)</span>
                    <?php if ($estAdmin): ?>
                    <button @click="showNewLecon = showNewLecon === <?= $module['id'] ?> ? null : <?= $module['id'] ?>" class="text-xs text-white px-3 py-1 font-medium transition" style="background: var(--comm-color);">
                        + Leçon
                    </button>
                    <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modules/<?= $module['id'] ?>/supprimer" class="inline" onsubmit="return confirm('Supprimer ce module et ses leçons ?')">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition">Supprimer</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulaire ajout leçon (par module) -->
            <?php if ($estAdmin): ?>
            <div x-show="showNewLecon === <?= $module['id'] ?>" x-cloak x-transition class="border-b border-gray-100 px-6 py-4 bg-violet-50">
                <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/lecons" enctype="multipart/form-data" class="space-y-3">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Titre de la leçon *</label>
                        <input type="text" name="titre" required class="w-full px-3 py-2 bg-white border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Upload vidéo</label>
                            <div class="relative">
                                <input type="file" name="video_fichier" accept="video/*" class="hidden" id="videoFile<?= $module['id'] ?>" onchange="this.nextElementSibling.textContent = this.files[0] ? this.files[0].name : 'Choisir un fichier vidéo...'">
                                <label for="videoFile<?= $module['id'] ?>" class="flex items-center gap-2 w-full px-3 py-2 bg-white border border-gray-200 border-dashed text-sm text-gray-500 cursor-pointer hover:border-violet-400 hover:bg-violet-50 transition">
                                    <i data-lucide="upload-cloud" class="w-4 h-4 text-violet-400"></i>
                                    <span>Choisir un fichier vidéo...</span>
                                </label>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">MP4, WebM, MOV — max 100 Mo</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Ou lien vidéo externe</label>
                            <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full px-3 py-2 bg-white border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">YouTube, Vimeo, Dailymotion...</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 bg-white border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Contenu de la leçon</label>
                        <textarea name="contenu" rows="4" class="w-full px-3 py-2 bg-white border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none font-mono text-xs" placeholder="Contenu texte, HTML, ou notes..."></textarea>
                    </div>
                    <button type="submit" class="text-white font-semibold px-4 py-1.5 text-xs transition" style="background: var(--comm-color);">Ajouter la leçon</button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Leçons du module -->
            <?php if (!empty($module['lecons'])): ?>
            <div class="divide-y divide-gray-50">
                <?php foreach ($module['lecons'] as $leconIndex => $lecon): ?>
                <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition">
                    <div class="w-7 h-7 bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-500 font-medium text-xs"><?= $leconIndex + 1 ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($lecon['titre']) ?></p>
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
                    <span class="text-xs <?= $lecon['statut'] === 'active' ? 'text-emerald-500' : 'text-gray-400' ?>">
                        <?= $lecon['statut'] === 'active' ? 'Publiée' : 'Brouillon' ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="px-6 py-6 text-center">
                <p class="text-sm text-gray-400">Aucune leçon dans ce module. Cliquez sur "+ Leçon" pour en ajouter.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-5" style="background: var(--comm-color-light);">
            <i data-lucide="layers" class="w-8 h-8" style="color: var(--comm-color);"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun module</h3>
        <p class="text-gray-500 mb-4">Créez des modules pour structurer votre cours.</p>
        <?php if ($estAdmin): ?>
        <button @click="showNewModule = true" class="text-white font-semibold px-5 py-2 text-sm transition" style="background: var(--comm-color);">
            + Créer un module
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
