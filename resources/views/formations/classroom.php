<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']); ?>

<div class="max-w-6xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Classe</h1>
            <p class="text-gray-500 mt-1"><?= count($formations) ?> cours</p>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="text-white font-semibold px-5 py-2 text-sm transition" style="background: var(--comm-color);">
            <span x-show="!showForm">+ Nouveau cours</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer un cours</h2>
        <form method="POST" action="/c/<?= $slug ?>/formations" enctype="multipart/form-data" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <!-- Image couverture -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de couverture</label>
                <div class="relative" x-data="{ preview: null }">
                    <input type="file" name="image_couverture" accept="image/*" class="hidden" id="coverInput"
                           onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>document.getElementById('coverPreview').src=e.target.result;document.getElementById('coverPreview').style.display='block';document.getElementById('coverPlaceholder').style.display='none';}">
                    <label for="coverInput" class="block cursor-pointer border-2 border-dashed border-gray-200 hover:border-violet-400 transition p-4 text-center">
                        <img id="coverPreview" class="w-full h-40 object-cover hidden">
                        <div id="coverPlaceholder" class="py-6">
                            <i data-lucide="image-plus" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-400">Cliquer pour ajouter une image de couverture</p>
                            <p class="text-xs text-gray-300 mt-1">JPG, PNG, WebP — max 5 Mo</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" placeholder="Ex: Fondamentaux de l'IA">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                    <input type="number" name="ordre" value="<?= count($formations) + 1 ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none" placeholder="Décrivez ce que les membres vont apprendre..."></textarea>
            </div>
            <button type="submit" class="text-white font-semibold px-6 py-2 text-sm transition" style="background: var(--comm-color);">Créer le cours</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($formations)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($formations as $formation): ?>
        <a href="/c/<?= $slug ?>/formations/<?= htmlspecialchars($formation['slug']) ?>"
           class="bg-white border border-gray-100 overflow-hidden hover:shadow-lg transition group">
            <!-- Cover image -->
            <div class="h-44 relative overflow-hidden bg-gray-100">
                <?php if (!empty($formation['image_couverture'])): ?>
                <img src="/<?= htmlspecialchars($formation['image_couverture']) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--comm-color, #7830E0)15, var(--comm-color, #7830E0)35);">
                    <i data-lucide="book-open" class="w-12 h-12 opacity-40" style="color: var(--comm-color, #7830E0);"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="p-5">
                <h3 class="font-bold text-gray-900 group-hover:text-violet-600 transition mb-1 line-clamp-1">
                    <?= htmlspecialchars($formation['titre']) ?>
                </h3>
                <?php if (!empty($formation['description'])): ?>
                <p class="text-sm text-gray-500 line-clamp-2 mb-3"><?= htmlspecialchars($formation['description']) ?></p>
                <?php endif; ?>
                <!-- Progress bar -->
                <div class="w-full bg-gray-100 h-1.5 mt-3">
                    <div class="h-1.5" style="width: 0%; background: var(--comm-color, #7830E0);"></div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-gray-400"><?= $formation['nombre_lecons'] ?> leçon(s)</span>
                    <span class="text-xs font-medium" style="color: var(--comm-color, #7830E0);">0%</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-16 text-center">
        <div class="w-20 h-20 flex items-center justify-center mx-auto mb-6" style="background: var(--comm-color-light);">
            <i data-lucide="book-open" class="w-10 h-10" style="color: var(--comm-color); opacity: 0.6;"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun cours</h3>
        <p class="text-gray-500 mb-6">Créez votre premier cours pour structurer l'apprentissage de vos membres.</p>
        <?php if ($estAdmin): ?>
        <button @click="showForm = true" class="text-white font-semibold px-6 py-2.5 text-sm transition" style="background: var(--comm-color);">
            + Créer un cours
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
