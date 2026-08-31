<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Modération</h1>
    <p class="text-gray-500 mt-1">Gérer le contenu de la plateforme</p>
</div>

<!-- Tabs -->
<div x-data="{ tab: 'publications' }" class="space-y-6">
    <div class="flex gap-2 border-b border-gray-200">
        <button @click="tab = 'publications'" :class="tab === 'publications' ? 'border-violet-600 text-violet-600' : 'border-transparent text-gray-500'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition">
            Publications (<?= count($publications) ?>)
        </button>
        <button @click="tab = 'commentaires'" :class="tab === 'commentaires' ? 'border-violet-600 text-violet-600' : 'border-transparent text-gray-500'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition">
            Commentaires (<?= count($commentaires) ?>)
        </button>
    </div>

    <!-- Publications -->
    <div x-show="tab === 'publications'" class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Auteur</th>
                        <th class="px-6 py-3 font-medium">Contenu</th>
                        <th class="px-6 py-3 font-medium">Communauté</th>
                        <th class="px-6 py-3 font-medium">Statut</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publications as $pub): ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($pub['prenom'] . ' ' . $pub['nom']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                            <?= htmlspecialchars(mb_substr($pub['contenu'] ?? '', 0, 80)) ?>
                            <?php if (!empty($pub['fichier'])): ?>
                            <i data-lucide="paperclip" class="w-3.5 h-3.5 inline text-gray-400 ml-1"></i>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <a href="/c/<?= htmlspecialchars($pub['communaute_slug']) ?>" class="text-violet-600 hover:text-violet-700 text-xs">
                                <?= htmlspecialchars($pub['communaute_nom']) ?>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $pub['statut'] === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                                <?= ucfirst(htmlspecialchars($pub['statut'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y H:i', strtotime($pub['date_creation'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($pub['statut'] === 'active'): ?>
                            <form method="POST" action="/admin/publications/<?= (int)$pub['id'] ?>/supprimer" class="inline"
                                  x-data x-on:submit="return confirm('Supprimer cette publication ?')">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="text-xs text-red-600 hover:text-red-700 transition">
                                    <i data-lucide="trash" class="w-4 h-4 inline"></i> Supprimer
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">Supprimée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commentaires -->
    <div x-show="tab === 'commentaires'" class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Auteur</th>
                        <th class="px-6 py-3 font-medium">Commentaire</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commentaires as $comment): ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($comment['prenom'] . ' ' . $comment['nom']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-md truncate">
                            <?= htmlspecialchars(mb_substr($comment['contenu'], 0, 120)) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y H:i', strtotime($comment['date_creation'])) ?></td>
                        <td class="px-6 py-4">
                            <form method="POST" action="/admin/commentaires/<?= (int)$comment['id'] ?>/supprimer" class="inline"
                                  x-data x-on:submit="return confirm('Supprimer ce commentaire ?')">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="text-xs text-red-600 hover:text-red-700 transition">
                                    <i data-lucide="trash" class="w-4 h-4 inline"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
