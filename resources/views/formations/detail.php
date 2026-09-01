<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-6xl mx-auto" x-data="{ showLeconForm: false, showModuleForm: false, activeTab: 'cours' }">
    <div class="mb-6">
        <a href="/c/<?= $slug ?>/classroom" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-3">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la classe
        </a>
    </div>

    <div class="flex gap-6">
        <!-- SIDEBAR GAUCHE : Liste des leçons -->
        <div class="w-72 flex-shrink-0">
            <div class="bg-white border border-gray-100 sticky top-20">
                <!-- En-tête cours -->
                <div class="p-5 border-b border-gray-100">
                    <h2 class="font-bold text-gray-900 text-sm leading-tight"><?= htmlspecialchars($formation['titre']) ?></h2>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-full bg-gray-100 h-1.5 flex-1">
                            <div class="h-1.5 transition-all duration-500" style="width: <?= $pourcentage ?>%; background: var(--comm-color, #7830E0);"></div>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--comm-color);"><?= $pourcentage ?>%</span>
                    </div>
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                        <span><?= count($modules) ?> module(s)</span>
                        <span>•</span>
                        <span><?= count($lecons) + array_sum(array_map(fn($m) => count($m['lecons']), $modules)) ?> leçon(s)</span>
                    </div>
                </div>

                <!-- Liste des modules/leçons -->
                <div class="max-h-[60vh] overflow-y-auto">
                    <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $modIndex => $module): ?>
                    <div class="border-b border-gray-50">
                        <!-- Titre module (cliquable) -->
                        <button @click="$el.closest('.border-b').querySelector('.module-content').classList.toggle('hidden')" class="w-full px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition text-left">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-5 h-5 flex items-center justify-center text-xs font-bold flex-shrink-0" style="color: var(--comm-color, #7830E0);"><?= $modIndex + 1 ?></span>
                                <span class="text-xs font-semibold text-gray-900 truncate"><?= htmlspecialchars($module['titre']) ?></span>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <span class="text-[10px] text-gray-400"><?= $module['nb_lecons'] ?></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400"></i>
                            </div>
                        </button>
                        <!-- Leçons du module -->
                        <div class="module-content">
                            <?php if (!empty($module['lecons'])): ?>
                            <?php foreach ($module['lecons'] as $leconIndex => $lecon): ?>
                            <?php $estTerminee = in_array($lecon['id'], $leconsTerminees ?? []); ?>
                            <div class="pl-10 pr-5 py-2.5 flex items-center gap-2 hover:bg-gray-50 transition cursor-pointer border-l-2 <?= $estTerminee ? 'border-emerald-400' : 'border-transparent hover:border-violet-300' ?>">
                                <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/lecons/<?= $lecon['id'] ?>/<?= $estTerminee ? 'annuler' : 'terminer' ?>"
                                      class="inline" x-data @submit.prevent="async (e) => {
                                          const form = e.target;
                                          const resp = await fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': form.querySelector('input[name=_token]').value } });
                                          const data = await resp.json();
                                          if (data.success) location.reload();
                                      }">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-5 h-5 flex items-center justify-center flex-shrink-0 <?= $estTerminee ? 'bg-emerald-500 text-white' : 'bg-gray-100 hover:bg-violet-100' ?>">
                                        <?php if ($estTerminee): ?>
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <?php else: ?>
                                        <span class="text-gray-400 text-[10px]"><?= $leconIndex + 1 ?></span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                                <span class="text-xs <?= $estTerminee ? 'text-emerald-600 font-medium' : 'text-gray-600' ?> truncate flex-1"><?= htmlspecialchars($lecon['titre']) ?></span>
                                <?php if (!empty($lecon['video_url']) || !empty($lecon['video_fichier'])): ?>
                                <i data-lucide="play-circle" class="w-3 h-3 text-gray-300 flex-shrink-0"></i>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div class="pl-10 pr-5 py-2">
                                <span class="text-[10px] text-gray-300 italic">Aucune leçon</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php elseif (!empty($lecons)): ?>
                    <?php foreach ($lecons as $leconIndex => $lecon): ?>
                    <?php $estTerminee2 = in_array($lecon['id'], $leconsTerminees ?? []); ?>
                    <div class="pl-5 pr-5 py-2.5 flex items-center gap-2 hover:bg-gray-50 transition cursor-pointer border-l-2 <?= $estTerminee2 ? 'border-emerald-400' : 'border-transparent hover:border-violet-300' ?>">
                        <form method="POST" action="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/lecons/<?= $lecon['id'] ?>/<?= $estTerminee2 ? 'annuler' : 'terminer' ?>"
                              class="inline" x-data @submit.prevent="async (e) => { const f=e.target; const r=await fetch(f.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-Token':f.querySelector('input[name=_token]').value}}); const d=await r.json(); if(d.success)location.reload(); }">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit" class="w-5 h-5 flex items-center justify-center flex-shrink-0 <?= $estTerminee2 ? 'bg-emerald-500 text-white' : 'bg-gray-100 hover:bg-violet-100' ?>">
                                <?php if ($estTerminee2): ?><i data-lucide="check" class="w-3 h-3"></i><?php else: ?><span class="text-gray-400 text-[10px]"><?= $leconIndex + 1 ?></span><?php endif; ?>
                            </button>
                        </form>
                        <span class="text-xs <?= $estTerminee2 ? 'text-emerald-600 font-medium' : 'text-gray-600' ?> truncate flex-1"><?= htmlspecialchars($lecon['titre']) ?></span>
                        <?php if (!empty($lecon['video_url']) || !empty($lecon['video_fichier'])): ?>
                        <i data-lucide="play-circle" class="w-3 h-3 text-gray-300 flex-shrink-0"></i>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="p-5 text-center">
                        <p class="text-xs text-gray-400">Aucune leçon</p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($estAdmin): ?>
                <div class="p-4 border-t border-gray-100">
                    <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modifier" class="block text-center text-white font-semibold px-4 py-2 text-xs transition" style="background: var(--comm-color);">
                        Modifier le cours
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 min-w-0">
            <?php if (!empty($modules) && !empty($modules[0]['lecons'])): ?>
                <!-- Afficher la première leçon du premier module par défaut -->
                <?php $premiereLecon = $modules[0]['lecons'][0]; ?>
                <div class="bg-white border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                            <span><?= htmlspecialchars($modules[0]['titre']) ?></span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            <span class="text-violet-600 font-medium"><?= htmlspecialchars($premiereLecon['titre']) ?></span>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($premiereLecon['titre']) ?></h1>
                    </div>

                    <!-- Vidéo -->
                    <?php if (!empty($premiereLecon['video_fichier'])): ?>
                    <div class="bg-black">
                        <video controls preload="metadata" class="w-full max-h-[500px]">
                            <source src="/<?= htmlspecialchars($premiereLecon['video_fichier']) ?>" type="video/<?= pathinfo($premiereLecon['video_fichier'], PATHINFO_EXTENSION) ?>">
                        </video>
                    </div>
                    <?php elseif (!empty($premiereLecon['video_url'])): ?>
                    <div class="bg-black">
                        <iframe src="<?= htmlspecialchars($premiereLecon['video_url']) ?>" class="w-full aspect-video" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <?php endif; ?>

                    <!-- Contenu -->
                    <?php if (!empty($premiereLecon['contenu'])): ?>
                    <div class="p-6 prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($premiereLecon['contenu'])) ?>
                    </div>
                    <?php elseif (!empty($premiereLecon['description'])): ?>
                    <div class="p-6">
                        <p class="text-gray-600 leading-relaxed"><?= htmlspecialchars($premiereLecon['description']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Autres leçons -->
                <?php $leconCount = 0; ?>
                <?php foreach ($modules as $mod): ?>
                <?php foreach ($mod['lecons'] as $leconIndex => $lecon): ?>
                <?php if ($leconCount++ === 0) continue; // Skip première leçon déjà affichée ?>
                <div class="bg-white border border-gray-100 mt-4">
                    <div class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-500 font-medium text-xs"><?= $leconCount ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400"><?= htmlspecialchars($mod['titre']) ?></span>
                                <i data-lucide="chevron-right" class="w-3 h-3 text-gray-300"></i>
                            </div>
                            <h3 class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($lecon['titre']) ?></h3>
                        </div>
                        <?php if (!empty($lecon['video_fichier']) || !empty($lecon['video_url'])): ?>
                        <span class="flex items-center gap-1 text-xs text-violet-500">
                            <i data-lucide="play-circle" class="w-3.5 h-3.5"></i> Vidéo
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endforeach; ?>

            <?php else: ?>
            <div class="bg-white border border-gray-100 p-16 text-center">
                <div class="w-20 h-20 flex items-center justify-center mx-auto mb-6" style="background: var(--comm-color-light);">
                    <i data-lucide="book-open" class="w-10 h-10" style="color: var(--comm-color); opacity: 0.6;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun contenu</h3>
                <p class="text-gray-500 mb-6">Ajoutez des modules et leçons pour structurer ce cours.</p>
                <?php if ($estAdmin): ?>
                <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>/modifier" class="inline-block text-white font-semibold px-6 py-2.5 text-sm transition" style="background: var(--comm-color);">
                    Ajouter du contenu
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
