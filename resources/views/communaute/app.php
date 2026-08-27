<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="flex gap-8">
    <!-- Sidebar Navigation (Desktop) -->
    <aside class="hidden lg:block w-64 flex-shrink-0">
        <div class="sticky top-24 space-y-1">
            <!-- Communauté info -->
            <div class="flex items-center gap-3 px-3 py-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <?php if (!empty($communaute['logo'])): ?>
                    <img src="<?= htmlspecialchars($communaute['logo']) ?>" class="w-10 h-10 rounded-xl object-cover" alt="">
                    <?php else: ?>
                    <span class="text-violet-600 font-bold"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($communaute['nom']) ?></p>
                    <p class="text-xs text-gray-400"><?= ucfirst(htmlspecialchars($communaute['visibilite'])) ?></p>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="/c/<?= $slug ?>/app" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-violet-50 text-violet-700 font-medium text-sm transition">
                    <i data-lucide="home" class="w-5 h-5"></i> Accueil
                </a>
                <a href="/c/<?= $slug ?>/membres" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="users" class="w-5 h-5"></i> Membres
                </a>
                <a href="/c/<?= $slug ?>/formations" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="book-open" class="w-5 h-5"></i> Formations
                </a>
                <a href="/c/<?= $slug ?>/ressources" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="folder" class="w-5 h-5"></i> Ressources
                </a>
                <a href="/c/<?= $slug ?>/evenements" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="calendar" class="w-5 h-5"></i> Événements
                </a>
                <a href="/c/<?= $slug ?>/messages" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="message-circle" class="w-5 h-5"></i> Messages
                </a>
                <a href="/c/<?= $slug ?>/notifications" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="bell" class="w-5 h-5"></i> Notifications
                </a>

                <hr class="my-3 border-gray-100">

                <a href="/c/<?= $slug ?>/gestion" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition">
                    <i data-lucide="settings" class="w-5 h-5"></i> Gestion
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Feed -->
    <div class="flex-1 min-w-0">
        <!-- Inclure le feed via un iframe ou rediriger -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6">
            <form method="POST" action="/c/<?= $slug ?>/publications" class="space-y-4">
                <?= \App\Core\Csrf::field() ?>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                    </div>
                    <textarea name="contenu" rows="2"
                              class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition resize-none placeholder-gray-400"
                              placeholder="Partagez quelque chose..."></textarea>
                </div>
                <div class="flex items-center justify-between pl-13">
                    <div class="flex items-center gap-1">
                        <button type="button" class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                            <i data-lucide="image" class="w-5 h-5"></i>
                        </button>
                        <button type="button" class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                            <i data-lucide="video" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 rounded-xl text-sm transition">
                        Publier
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Activity Placeholder -->
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="sparkles" class="w-8 h-8 text-violet-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bienvenue !</h3>
            <p class="text-gray-500">Commencez par publier du contenu pour votre communauté.</p>
        </div>
    </div>

    <!-- Right Sidebar (Desktop) -->
    <aside class="hidden xl:block w-72 flex-shrink-0">
        <div class="sticky top-24 space-y-6">
            <!-- Membres récents -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 text-sm mb-4">Membres récents</h3>
                <div class="text-center text-sm text-gray-400 py-4">
                    <p>Les membres apparaîtront ici</p>
                </div>
            </div>

            <!-- Événements à venir -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 text-sm mb-4">Événements à venir</h3>
                <div class="text-center text-sm text-gray-400 py-4">
                    <p>Aucun événement prévu</p>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>lucide.createIcons();</script>
