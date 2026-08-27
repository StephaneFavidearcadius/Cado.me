<div class="max-w-3xl mx-auto">
    <!-- Header Feed -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Feed</h1>
    </div>

    <!-- Nouvelle publication -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6">
        <form method="POST" action="/c <?= htmlspecialchars($communaute['slug']) ?>/publications" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                </div>
                <textarea name="contenu" rows="3"
                          class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition resize-none placeholder-gray-400"
                          placeholder="Partagez quelque chose avec votre communauté..."></textarea>
            </div>
            <div class="flex items-center justify-between pl-13">
                <div class="flex items-center gap-2">
                    <button type="button" class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </button>
                    <button type="button" class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </button>
                    <button type="button" class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="paperclip" class="w-5 h-5"></i>
                    </button>
                </div>
                <button type="submit"
                        class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 rounded-xl text-sm transition shadow-md shadow-violet-500/20">
                    Publier
                </button>
            </div>
        </form>
    </div>

    <!-- Publications -->
    <?php if (!empty($publications)): ?>
    <div class="space-y-4">
        <?php foreach ($publications as $pub): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="{ showComments: false }">
            <!-- En-tête -->
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                    <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($pub['prenom'], 0, 1)) ?></span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($pub['prenom'] . ' ' . $pub['nom']) ?></p>
                    <p class="text-xs text-gray-400"><?= date('d M à H:i', strtotime($pub['date_creation'])) ?></p>
                </div>
            </div>

            <!-- Contenu -->
            <?php if (!empty($pub['contenu'])): ?>
            <p class="text-gray-700 mb-4 leading-relaxed"><?= nl2br(htmlspecialchars($pub['contenu'])) ?></p>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex items-center gap-6 pt-3 border-t border-gray-100">
                <form method="POST" action="/c <?= htmlspecialchars($communaute['slug']) ?>/publications/<?= $pub['id'] ?>/like">
                    <?= \App\Core\Csrf::field() ?>
                    <button type="submit" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        <span><?= $pub['nb_likes'] ?></span>
                    </button>
                </form>

                <button @click="showComments = !showComments" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span><?= $pub['nb_commentaires'] ?></span>
                </button>

                <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                </button>

                <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Commentaires (Alpine.js) -->
            <div x-show="showComments" x-cloak x-transition class="mt-4 pt-4 border-t border-gray-100">
                <form method="POST" action="/c <?= htmlspecialchars($communaute['slug']) ?>/publications/<?= $pub['id'] ?>/commentaires" class="flex gap-2">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="text" name="contenu" placeholder="Écrire un commentaire..."
                           class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    <button type="submit" class="px-4 py-2 bg-violet-500 text-white rounded-xl text-sm font-medium hover:bg-violet-600 transition">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($lastPage > 1): ?>
    <div class="flex items-center justify-center gap-1 mt-8">
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 transition">← Précédent</a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($lastPage, $page + 2); $i++): ?>
        <?php if ($i === $page): ?>
        <span class="px-3 py-2 rounded-lg text-sm font-medium bg-violet-500 text-white"><?= $i ?></span>
        <?php else: ?>
        <a href="?page=<?= $i ?>" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 transition"><?= $i ?></a>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 transition">Suivant →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty state -->
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="message-square" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune publication</h3>
        <p class="text-gray-500">Soyez le premier à publier quelque chose !</p>
    </div>
    <?php endif; ?>
</div>

<script>lucide.createIcons();</script>
