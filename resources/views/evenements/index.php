<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-5xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Événements</h1>
            <p class="text-gray-500 mt-1"><?= count($evenements) ?> événement(s)</p>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            <span x-show="!showForm">+ Nouvel événement</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer un événement</h2>
        <form method="POST" action="/c/<?= $slug ?>/evenements" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                        <option value="webinaire">Webinaire</option>
                        <option value="meetup">Meetup</option>
                        <option value="atelier">Atelier</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
                    <input type="datetime-local" name="date_debut" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="datetime-local" name="date_fin" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien (optionnel)</label>
                <input type="url" name="lien" placeholder="https://..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2 text-sm transition">Créer</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($evenements)): ?>
    <div class="space-y-4">
        <?php foreach ($evenements as $evenement): ?>
        <div class="bg-white border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-violet-100 flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 text-lg font-bold"><?= date('d', strtotime($evenement['date_debut'])) ?></span>
                    <span class="text-violet-500 text-xs font-medium"><?= date('M', strtotime($evenement['date_debut'])) ?></span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($evenement['titre']) ?></h3>
                    <?php if (!empty($evenement['description'])): ?>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($evenement['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-3 mt-3 text-sm text-gray-400">
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <?= date('H:i', strtotime($evenement['date_debut'])) ?>
                            <?php if ($evenement['date_fin']): ?>
                            — <?= date('H:i', strtotime($evenement['date_fin'])) ?>
                            <?php endif; ?>
                        </span>
                        <span class="bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">
                            <?= ucfirst(htmlspecialchars($evenement['type'])) ?>
                        </span>
                        <?php if (!empty($evenement['lien'])): ?>
                        <a href="<?= htmlspecialchars($evenement['lien']) ?>" target="_blank" class="flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-medium hover:bg-emerald-100 transition">
                            <i data-lucide="video" class="w-3.5 h-3.5"></i>
                            Rejoindre
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="calendar" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun événement</h3>
        <p class="text-gray-500">Les événements à venir apparaîtront ici.</p>
    </div>
    <?php endif; ?>
</div>
