<?php $slug = htmlspecialchars($communaute['slug']); ?>

<!-- Main Feed -->
<div class="space-y-6">
    <!-- Publication Form -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <form method="POST" action="/c/<?= $slug ?>/publications" enctype="multipart/form-data" class="space-y-4">
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
                    <label class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600 cursor-pointer">
                        <i data-lucide="image" class="w-5 h-5"></i>
                        <input type="file" name="images[]" accept="image/*" multiple class="hidden">
                    </label>
                    <label class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600 cursor-pointer">
                        <i data-lucide="video" class="w-5 h-5"></i>
                        <input type="file" name="videos[]" accept="video/*" class="hidden">
                    </label>
                    <label class="p-2 rounded-lg hover:bg-violet-50 transition text-gray-400 hover:text-violet-600 cursor-pointer">
                        <i data-lucide="paperclip" class="w-5 h-5"></i>
                        <input type="file" name="fichiers[]" accept=".pdf,.zip,.doc,.docx" class="hidden">
                    </label>
                </div>
                <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 rounded-xl text-sm transition">
                    Publier
                </button>
            </div>
        </form>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="sparkles" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Bienvenue !</h3>
        <p class="text-gray-500">Commencez par publier du contenu pour votre communauté.</p>
    </div>
</div>

<script>lucide.createIcons();</script>
