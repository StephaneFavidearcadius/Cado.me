<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/gestion" class="text-sm text-gray-500 hover:text-gray-700 transition flex items-center gap-1 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la gestion
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modération</h1>
        <p class="text-gray-500 mt-1"><?= $nbEnAttente ?> signalement(s) en attente</p>
    </div>

    <!-- Filtres -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        <a href="/c/<?= $slug ?>/gestion/moderation"
           class="px-4 py-2 text-sm font-medium transition <?= !$filtre ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Tous
        </a>
        <a href="/c/<?= $slug ?>/gestion/moderation?filtre=en_attente"
           class="px-4 py-2 text-sm font-medium transition whitespace-nowrap <?= ($filtre === 'en_attente') ? 'bg-amber-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            En attente (<?= $nbEnAttente ?>)
        </a>
        <a href="/c/<?= $slug ?>/gestion/moderation?filtre=traite"
           class="px-4 py-2 text-sm font-medium transition <?= ($filtre === 'traite') ? 'bg-emerald-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Traités
        </a>
        <a href="/c/<?= $slug ?>/gestion/moderation?filtre=rejete"
           class="px-4 py-2 text-sm font-medium transition <?= ($filtre === 'rejete') ? 'bg-gray-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Rejetés
        </a>
    </div>

    <!-- Liste des signalements -->
    <?php if (empty($signalements)): ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="shield-check" class="w-8 h-8 text-gray-400"></i>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Aucun signalement</h3>
        <p class="text-sm text-gray-500">La communauté est tranquille pour le moment.</p>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($signalements as $signalement):
            $estEnAttente = $signalement['statut'] === 'en_attente';
            $estTraite = $signalement['statut'] === 'traite';
            $estCiblePub = !empty($signalement['publication_id']);
        ?>
        <div class="bg-white border border-gray-100 p-6">
            <!-- En-tête du signalement -->
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center
                        <?= $estTraite ? 'bg-emerald-100' : ($estEnAttente ? 'bg-amber-100' : 'bg-gray-100') ?>">
                        <i data-lucide="<?= $estTraite ? 'check-circle' : ($estEnAttente ? 'alert-triangle' : 'x-circle') ?>"
                           class="w-5 h-5 <?= $estTraite ? 'text-emerald-600' : ($estEnAttente ? 'text-amber-600' : 'text-gray-400') ?>"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            Signalé par <span class="text-violet-600"><?= htmlspecialchars($signalement['signalant_prenom'] . ' ' . $signalement['signalant_nom']) ?></span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <?= date('d/m/Y à H:i', strtotime($signalement['date_creation'])) ?>
                            · <?= $estCiblePub ? 'Publication' : 'Commentaire' ?>
                        </p>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 font-medium
                    <?= $estTraite ? 'bg-emerald-100 text-emerald-700' : ($estEnAttente ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') ?>">
                    <?= ucfirst($signalement['statut']) ?>
                </span>
            </div>

            <!-- Motif -->
            <div class="bg-gray-50 p-4 mb-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Motif</p>
                <p class="text-sm text-gray-700"><?= htmlspecialchars($signalement['motif']) ?></p>
            </div>

            <!-- Contenu signalé -->
            <div class="border-l-4 border-red-300 pl-4 py-2 mb-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Contenu signalé</p>
                <?php if ($estCiblePub): ?>
                <p class="text-sm text-gray-700">
                    <span class="text-gray-500">Publié par</span>
                    <?= htmlspecialchars($signalement['pub_auteur_prenom'] ?? '' . ' ' . $signalement['pub_auteur_nom'] ?? '') ?> :
                    <?= htmlspecialchars(mb_strimwidth($signalement['publication_contenu'] ?? '', 0, 200, '...')) ?>
                </p>
                <?php else: ?>
                <p class="text-sm text-gray-700">
                    <span class="text-gray-500">Commenté par</span>
                    <?= htmlspecialchars($signalement['com_auteur_prenom'] ?? '' . ' ' . $signalement['com_auteur_nom'] ?? '') ?> :
                    <?= htmlspecialchars(mb_strimwidth($signalement['commentaire_contenu'] ?? '', 0, 200, '...')) ?>
                </p>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <?php if ($estEnAttente): ?>
            <div class="flex items-center gap-3 pt-2">
                <!-- Masquer le contenu -->
                <form method="POST" action="/c/<?= $slug ?>/gestion/moderation/<?= $signalement['id'] ?>/traiter"
                      class="inline">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="hidden" name="decision" value="traite">
                    <button type="submit"
                            class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100 transition flex items-center gap-2">
                        <i data-lucide="eye-off" class="w-4 h-4"></i> Masquer le contenu
                    </button>
                </form>

                <!-- Rejeter le signalement -->
                <form method="POST" action="/c/<?= $slug ?>/gestion/moderation/<?= $signalement['id'] ?>/traiter"
                      class="inline">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="hidden" name="decision" value="rejete">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-50 text-gray-600 text-sm font-medium hover:bg-gray-100 transition flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i> Rejeter
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
