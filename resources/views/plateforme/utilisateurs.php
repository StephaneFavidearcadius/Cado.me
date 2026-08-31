<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des utilisateurs</h1>
    <p class="text-gray-500 mt-1"><?= count($utilisateurs) ?> utilisateur(s)</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Utilisateur</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Identifiant</th>
                    <th class="px-6 py-3 font-medium">Rôle</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Inscrit le</th>
                    <th class="px-6 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $util): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-violet-100 rounded-full flex items-center justify-center">
                                <span class="text-violet-600 text-xs font-bold"><?= strtoupper(substr($util['prenom'], 0, 1)) ?></span>
                            </div>
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($util['prenom'] . ' ' . $util['nom']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($util['email']) ?></td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">@<?= htmlspecialchars($util['identifiant']) ?></td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-0.5 rounded-full <?= $util['role_plateforme'] === 'super_administrateur' ? 'bg-violet-50 text-violet-600 font-semibold' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $util['role_plateforme'] === 'super_administrateur' ? 'Super Admin' : ucfirst(htmlspecialchars($util['role_plateforme'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $util['statut'] === 'actif' ? 'bg-emerald-50 text-emerald-600' : ($util['statut'] === 'suspendu' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500') ?>">
                            <?= ucfirst(htmlspecialchars($util['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y', strtotime($util['date_creation'])) ?></td>
                    <td class="px-6 py-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition p-1">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10">
                                <?php if ($util['role_plateforme'] !== 'super_administrateur'): ?>
                                <form method="POST" action="/admin/utilisateurs/<?= (int)$util['id'] ?>/promouvoir" class="inline"
                                      x-data x-on:submit="return confirm('Promouvoir en super administrateur ?')">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-violet-600 hover:bg-violet-50 transition">
                                        <i data-lucide="shield" class="w-4 h-4 inline mr-2"></i>Super Admin
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="/admin/utilisateurs/<?= (int)$util['id'] ?>/retrograder" class="inline"
                                      x-data x-on:submit="return confirm('Retirer le rôle super admin ?')">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                                        <i data-lucide="shield-off" class="w-4 h-4 inline mr-2"></i>Retirer Admin
                                    </button>
                                </form>
                                <?php endif; ?>

                                <?php if ($util['statut'] === 'actif'): ?>
                                <form method="POST" action="/admin/utilisateurs/<?= (int)$util['id'] ?>/suspendre" class="inline"
                                      x-data x-on:submit="return confirm('Suspendre cet utilisateur ?')">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-amber-600 hover:bg-amber-50 transition">
                                        <i data-lucide="ban" class="w-4 h-4 inline mr-2"></i>Suspendre
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="/admin/utilisateurs/<?= (int)$util['id'] ?>/reactiver" class="inline">
                                    <?= \App\Core\Csrf::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                        <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>Réactiver
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
