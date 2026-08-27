<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des communautés</h1>
    <p class="text-gray-500 mt-1"><?= count($communautes) ?> communauté(s)</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Nom</th>
                    <th class="px-6 py-3 font-medium">Slug</th>
                    <th class="px-6 py-3 font-medium">Propriétaire</th>
                    <th class="px-6 py-3 font-medium">Membres</th>
                    <th class="px-6 py-3 font-medium">Visibilité</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Créée le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($communautes as $comm): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($comm['nom']) ?></td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs"><?= htmlspecialchars($comm['slug']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($comm['prenom'] . ' ' . $comm['proprietaire_nom']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= $comm['nombre_membres'] ?></td>
                    <td class="px-6 py-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full"><?= ucfirst(htmlspecialchars($comm['visibilite'])) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $comm['statut'] === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                            <?= ucfirst(htmlspecialchars($comm['statut'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?= date('d/m/Y', strtotime($comm['date_creation'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
