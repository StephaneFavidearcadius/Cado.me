<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des utilisateurs</h1>
    <p class="text-gray-500 mt-1"><?= count($utilisateurs) ?> utilisateur(s)</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Nom</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Identifiant</th>
                    <th class="px-6 py-3 font-medium">Rôle</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Inscrit le</th>
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
                            <?= ucfirst(htmlspecialchars($util['role_plateforme'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $util['statut'] === 'actif' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                            <?= ucfirst(htmlspecialchars($util['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y', strtotime($util['date_creation'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
