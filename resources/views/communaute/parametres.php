<?php $slug = htmlspecialchars($communaute['slug']); ?>
<?php $storage = new \App\Services\StorageService(); ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/c/<?= $slug ?>/gestion" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la gestion
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Paramètres</h1>
    </div>

    <form method="POST" action="/c/<?= $slug ?>/gestion/parametres" enctype="multipart/form-data" class="space-y-6">
        <?= \App\Core\Csrf::field() ?>

        <!-- Cover Photo -->
        <div x-data="{ coverPreview: '<?= !empty($communaute['image_couverture']) ? htmlspecialchars($storage->url($communaute['image_couverture'])) : '' ?>' }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Photo de couverture</label>
            <div class="relative w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-violet-400 transition overflow-hidden"
                 @click="$refs.coverInput.click()">
                <template x-if="!coverPreview">
                    <div class="text-center">
                        <i data-lucide="image-plus" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-500">Ajouter une photo de couverture</p>
                    </div>
                </template>
                <template x-if="coverPreview">
                    <div class="relative w-full h-full">
                        <img :src="coverPreview" class="w-full h-full object-cover" alt="">
                        <button type="button" @click="coverPreview = null; $refs.coverInput.value = ''"
                                class="absolute top-2 right-2 bg-black/50 text-white p-1.5 hover:bg-black/70 transition">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </template>
            </div>
            <input type="file" x-ref="coverInput" name="image_couverture" accept="image/*" class="hidden"
                   @change="const f=$event.target.files[0]; if(f){const r=new FileReader(); r.onload=e=>coverPreview=e.target.result; r.readAsDataURL(f);}">
        </div>

        <!-- Logo -->
        <div x-data="{ logoPreview: '<?= !empty($communaute['logo']) ? htmlspecialchars($storage->url($communaute['logo'])) : '' ?>' }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Logo</label>
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-violet-400 transition overflow-hidden"
                     @click="$refs.logoInput.click()">
                    <template x-if="!logoPreview">
                        <div class="text-center">
                            <i data-lucide="camera" class="w-6 h-6 text-gray-400 mx-auto"></i>
                        </div>
                    </template>
                    <template x-if="logoPreview">
                        <img :src="logoPreview" class="w-full h-full object-cover" alt="">
                    </template>
                </div>
                <input type="file" x-ref="logoInput" name="logo" accept="image/*" class="hidden"
                       @change="const f=$event.target.files[0]; if(f){const r=new FileReader(); r.onload=e=>logoPreview=e.target.result; r.readAsDataURL(f);}">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Photo de profil</p>
                    <p class="text-xs text-gray-400">200×200 recommandé</p>
                </div>
            </div>
        </div>

        <!-- Nom -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($communaute['nom']) ?>"
                   class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none"
                   onfocus="this.style.boxShadow='0 0 0 2px var(--comm-color, #7830E0)'"
                   onblur="this.style.boxShadow='none'">
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="5"
                      class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none resize-none"
                      onfocus="this.style.boxShadow='0 0 0 2px var(--comm-color, #7830E0)'"
                      onblur="this.style.boxShadow='none'"><?= htmlspecialchars($communaute['description'] ?? '') ?></textarea>
        </div>

        <!-- Visibilité -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Visibilité</label>
            <select name="visibilite" class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none">
                <option value="publique" <?= $communaute['visibilite'] === 'publique' ? 'selected' : '' ?>>Publique</option>
                <option value="privee" <?= $communaute['visibilite'] === 'privee' ? 'selected' : '' ?>>Privée</option>
            </select>
        </div>

        <!-- Couleur -->
        <div x-data="{ color: '<?= htmlspecialchars($communaute['couleur_principale'] ?? '#7830E0') ?>' }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Couleur principale</label>
            <div class="flex items-center gap-3">
                <input type="color" name="couleur_principale" x-model="color"
                       class="w-12 h-12 border-2 border-gray-200 cursor-pointer" style="border-radius: 0;">
                <span class="text-sm text-gray-500" x-text="color"></span>
            </div>
            <div class="flex gap-2 mt-2">
                <?php foreach (['#7830E0', '#2563EB', '#059669', '#DC2626', '#D97706', '#0891B2', '#7C3AED', '#BE185D'] as $preset): ?>
                <button type="button" @click="color = '<?= $preset ?>'" class="w-8 h-8 border-2 border-gray-200 hover:border-gray-400 transition" style="background: <?= $preset ?>; border-radius: 0;"></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4">
            <button type="submit"
                    class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-3 transition shadow-lg">
                Enregistrer
            </button>
        </div>
    </form>
</div>

<script>lucide.createIcons();</script>
