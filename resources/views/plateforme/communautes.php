<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des communautés</h1>
    <p class="text-gray-500 mt-1"><?= count($communautes) ?> communauté(s)</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Communauté</th>
                    <th class="px-6 py-3 font-medium">Propriétaire</th>
                    <th class="px-6 py-3 font-medium">Membres</th>
                    <th class="px-6 py-3 font-medium">Visibilité</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Créée le</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($communautes as $comm): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background: <?= htmlspecialchars($comm['couleur_principale'] ?? '#7830E0') ?>">
                                <?= strtoupper(substr($comm['nom'], 0, 1)) ?>
                            </div>
                            <div>
                                <a href="/c/<?= htmlspecialchars($comm['slug']) ?>" class="font-medium text-gray-900 hover:text-violet-600 transition">
                                    <?= htmlspecialchars($comm['nom']) ?>
                                </a>
                                <p class="text-xs text-gray-400 font-mono">/<?= htmlspecialchars($comm['slug']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($comm['prenom'] . ' ' . $comm['proprietaire_nom']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= $comm['nombre_membres'] ?></td>
                    <td class="px-6 py-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full"><?= ucfirst(htmlspecialchars($comm['visibilite'])) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $comm['statut'] === 'active' ? 'bg-emerald-50 text-emerald-600' : ($comm['statut'] === 'suspendue' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') ?>">
                            <?= ucfirst(htmlspecialchars($comm['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y', strtotime($comm['date_creation'])) ?></td>
                    <td class="px-6 py-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition p-1">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10">
                                <?php if ($comm['statut'] === 'active'): ?>
                                <form method="POST" action="/admin/communautes/<?= (int)$comm['id'] ?>/suspendre" class="inline">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-amber-600 hover:bg-amber-50 transition">
                                        <i data-lucide="pause" class="w-4 h-4 inline mr-2"></i>Suspendre
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="/admin/communautes/<?= (int)$comm['id'] ?>/activer" class="inline">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                        <i data-lucide="play" class="w-4 h-4 inline mr-2"></i>Activer
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" action="/admin/communautes/<?= (int)$comm['id'] ?>/supprimer" class="inline"
                                      x-data x-on:submit="return confirm('Archiver cette communauté ?')">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <i data-lucide="archive" class="w-4 h-4 inline mr-2"></i>Archiver
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
