<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-3xl mx-auto" x-data="{ showNew: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
            <p class="text-gray-500 mt-1"><?= count($conversations) ?> conversation(s)</p>
        </div>
        <button @click="showNew = !showNew" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            <span x-show="!showNew">+ Nouveau message</span>
            <span x-show="showNew">Annuler</span>
        </button>
    </div>

    <!-- Nouvelle conversation -->
    <div x-show="showNew" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Nouvelle conversation</h2>
        <form method="POST" action="/c/<?= $slug ?>/messages" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionner un membre</label>
                <div class="max-h-48 overflow-y-auto border border-gray-200 bg-gray-50 p-2 space-y-1">
                    <?php if (!empty($membres)): ?>
                    <?php foreach ($membres as $membre): ?>
                    <?php if ((int)$membre['utilisateur_id'] !== (int)($_SESSION['utilisateur_id'] ?? 0)): ?>
                    <label class="flex items-center gap-3 p-2 hover:bg-white transition cursor-pointer">
                        <input type="checkbox" name="participants[]" value="<?= $membre['utilisateur_id'] ?>" class="w-4 h-4 text-violet-600">
                        <div class="w-8 h-8 bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-violet-600 text-xs font-bold"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?></p>
                            <p class="text-xs text-gray-400">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                        </div>
                    </label>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-sm text-gray-500 p-2">Aucun membre disponible.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Premier message *</label>
                <textarea name="premier_message" rows="3" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none" placeholder="Bonjour..."></textarea>
            </div>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2 text-sm transition">Envoyer</button>
        </form>
    </div>

    <!-- Liste conversations -->
    <?php if (!empty($conversations)): ?>
    <div class="space-y-2">
        <?php foreach ($conversations as $conv): ?>
        <?php
            // Trouver le nom de l'autre participant
            $autreNom = 'Inconnu';
            if (!empty($conv['participants'])) {
                foreach ($conv['participants'] as $p) {
                    if ((int)$p['utilisateur_id'] !== (int)($_SESSION['utilisateur_id'] ?? 0)) {
                        $autreNom = $p['prenom'] . ' ' . $p['nom'];
                        break;
                    }
                }
            }
        ?>
        <a href="/c/<?= $slug ?>/messages/<?= $conv['id'] ?>" class="bg-white border border-gray-100 p-4 hover:shadow-md transition cursor-pointer flex items-center gap-4 block">
            <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="text-violet-600 font-bold text-sm"><?= strtoupper(substr($autreNom, 0, 1)) ?></span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($autreNom) ?></p>
                    <?php if ($conv['non_lus'] > 0): ?>
                    <span class="bg-violet-500 text-white text-xs font-bold px-2 py-1">
                        <?= $conv['non_lus'] ?>
                    </span>
                    <?php endif; ?>
                </div>
                <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($conv['dernier_message'] ?? 'Aucun message') ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="message-circle" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune conversation</h3>
        <p class="text-gray-500">Commencez à discuter avec les membres.</p>
    </div>
    <?php endif; ?>
</div>
