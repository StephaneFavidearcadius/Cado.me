<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estMoi = ((int)($_SESSION['utilisateur_id'] ?? 0) === (int)($membre['id'] ?? 0)); ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/membres" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux membres
        </a>
    </div>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <!-- Cover -->
        <div class="h-32" style="background: var(--comm-color);"></div>

        <div class="p-8 -mt-12">
            <div class="flex items-end gap-5 mb-6">
                <!-- Photo profil -->
                <div class="relative group">
                    <div class="w-24 h-24 bg-white border-4 border-white shadow-lg flex items-center justify-center">
                        <?php $photo = $membre['photo_profil'] ?? $membre['avatar'] ?? ''; ?>
                        <?php if (!empty($photo)): ?>
                        <img src="<?= htmlspecialchars($photo) ?>" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                        <span class="font-bold text-3xl" style="color: var(--comm-color);"><?= strtoupper(substr($membre['prenom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($estMoi): ?>
                    <label class="absolute bottom-0 right-0 w-8 h-8 text-white flex items-center justify-center cursor-pointer shadow-md opacity-0 group-hover:opacity-100 transition" style="background: var(--comm-color);">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                        <input type="file" name="photo_profil" accept="image/*" class="hidden" onchange="this.closest('form').submit()">
                    </label>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?></h1>
                    <p class="text-gray-500">@<?= htmlspecialchars($membre['identifiant']) ?></p>
                </div>
            </div>

            <?php if ($estMoi): ?>
            <!-- Formulaire modification -->
            <form method="POST" action="/c/<?= $slug ?>/profil" enctype="multipart/form-data" class="space-y-5">
                <?= \App\Core\Csrf::field() ?>
                <input type="file" name="photo_profil" class="hidden" id="photoInput">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom</label>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($membre['prenom']) ?>" required
                               class="block w-full px-4 py-2.5 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($membre['nom']) ?>" required
                               class="block w-full px-4 py-2.5 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio</label>
                    <textarea name="biographie" rows="3"
                              class="block w-full px-4 py-2.5 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"
                              placeholder="Parlez de vous..."><?= htmlspecialchars($membre['biographie'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Numéro WhatsApp</label>
                    <input type="tel" name="whatsapp" value="<?= htmlspecialchars($membre['whatsapp'] ?? '') ?>"
                           class="block w-full px-4 py-2.5 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none"
                           placeholder="+225 07 08 09 10">
                </div>

                <?php if (!empty($membre['photo_profil']) || !empty($membre['avatar'])): ?>
                <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                    <input type="checkbox" name="supprimer_photo" value="1" class="w-4 h-4 text-violet-600">
                    Supprimer la photo de profil
                </label>
                <?php endif; ?>

                <button type="submit" class="text-white font-semibold px-6 py-2.5 text-sm transition" style="background: var(--comm-color);">
                    Enregistrer
                </button>
            </form>
            <?php else: ?>
            <!-- Affichage read-only pour les autres -->
            <?php if (!empty($membre['biographie'])): ?>
            <div class="mb-6">
                <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($membre['biographie'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($membre['whatsapp'])): ?>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <i data-lucide="message-circle" class="w-4 h-4 text-emerald-500"></i>
                <?= htmlspecialchars($membre['whatsapp']) ?>
            </div>
            <?php endif; ?>

            <div class="text-sm text-gray-400">
                Membre depuis <?= date('d/m/Y', strtotime($membre['date_creation'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
