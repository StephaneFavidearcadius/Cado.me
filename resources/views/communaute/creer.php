<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/app" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Créer une communauté</h1>
        <p class="text-gray-500 mt-1">Configurez votre espace communautaire</p>
    </div>

    <form method="POST" action="/app/communautes" enctype="multipart/form-data" class="space-y-6">
        <?= \App\Core\Csrf::field() ?>

        <!-- Cover Photo -->
        <div x-data="{ coverPreview: null }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Photo de couverture</label>
            <div class="relative w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-violet-400 transition overflow-hidden"
                 @click="$refs.coverInput.click()"
                 @dragover.prevent="dragover=true"
                 @dragleave.prevent="dragover=false"
                 @drop.prevent="dragover=false; if($event.dataTransfer.files.length){$refs.coverInput.files=$event.dataTransfer.files; previewFiles($event.dataTransfer.files[0], 'cover')}">
                <template x-if="!coverPreview">
                    <div class="text-center">
                        <i data-lucide="image-plus" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-500">Cliquez ou glissez une image</p>
                        <p class="text-xs text-gray-400 mt-1">1200×400 recommandé</p>
                    </div>
                </template>
                <template x-if="coverPreview">
                    <img :src="coverPreview" class="w-full h-full object-cover" alt="">
                </template>
            </div>
            <input type="file" x-ref="coverInput" name="image_couverture" accept="image/*" class="hidden"
                   @change="previewFiles($event.target.files[0], 'cover')">
        </div>

        <!-- Logo -->
        <div x-data="{ logoPreview: null }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Logo de la communauté</label>
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-violet-400 transition overflow-hidden"
                     @click="$refs.logoInput.click()">
                    <template x-if="!logoPreview">
                        <div class="text-center">
                            <i data-lucide="camera" class="w-6 h-6 text-gray-400 mx-auto"></i>
                            <p class="text-xs text-gray-400 mt-1">Logo</p>
                        </div>
                    </template>
                    <template x-if="logoPreview">
                        <img :src="logoPreview" class="w-full h-full object-cover" alt="">
                    </template>
                </div>
                <input type="file" x-ref="logoInput" name="logo" accept="image/*" class="hidden"
                       @change="previewFiles($event.target.files[0], 'logo')">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Photo de profil</p>
                    <p class="text-xs text-gray-400">200×200 recommandé, carré</p>
                </div>
            </div>
        </div>

        <!-- Nom -->
        <div>
            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom de la communauté *</label>
            <input type="text" id="nom" name="nom" required
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                   class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none transition"
                   placeholder="Ma Super Communauté"
                   style="focus-ring-color: var(--comm-color, #7830E0);"
                   onfocus="this.style.boxShadow='0 0 0 2px var(--comm-color, #7830E0)'"
                   onblur="this.style.boxShadow='none'">
            <?php if (!empty($errors['nom'])): ?>
            <p class="mt-1 text-xs text-red-500"><?= $errors['nom'] ?></p>
            <?php endif; ?>
            <p class="mt-1 text-xs text-gray-400">Le slug sera généré automatiquement depuis le nom</p>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none transition resize-none"
                      placeholder="Décrivez l'objectif de votre communauté..."
                      onfocus="this.style.boxShadow='0 0 0 2px var(--comm-color, #7830E0)'"
                      onblur="this.style.boxShadow='none'"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        </div>

        <!-- Visibilité -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">Visibilité</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative">
                    <input type="radio" name="visibilite" value="publique" class="peer sr-only" <?= ($old['visibilite'] ?? 'privee') === 'publique' ? 'checked' : '' ?>>
                    <div class="border-2 border-gray-200 p-4 cursor-pointer transition peer-checked:border-violet-500 peer-checked:bg-violet-50">
                        <div class="flex items-center gap-3">
                            <i data-lucide="globe" class="w-5 h-5 text-gray-400 peer-checked:text-violet-500"></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Publique</p>
                                <p class="text-xs text-gray-500">Visible par tous</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative">
                    <input type="radio" name="visibilite" value="privee" class="peer sr-only" <?= ($old['visibilite'] ?? 'privee') === 'privee' ? 'checked' : '' ?>>
                    <div class="border-2 border-gray-200 p-4 cursor-pointer transition peer-checked:border-violet-500 peer-checked:bg-violet-50">
                        <div class="flex items-center gap-3">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Privée</p>
                                <p class="text-xs text-gray-500">Sur invitation</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Couleur principale + Submit -->
        <div x-data="{ color: '<?= htmlspecialchars($old['couleur_principale'] ?? '#7830E0') ?>' }">
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Couleur principale</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="couleur_principale" x-model="color"
                           class="w-12 h-12 border-2 border-gray-200 cursor-pointer" style="border-radius: 0;">
                    <span class="text-sm text-gray-500" x-text="color"></span>
                </div>
                <div class="flex gap-2">
                    <?php foreach (['#7830E0', '#2563EB', '#059669', '#DC2626', '#D97706', '#0891B2', '#7C3AED', '#BE185D'] as $preset): ?>
                    <button type="button" @click="color = '<?= $preset ?>'" class="w-8 h-8 border-2 border-gray-200 hover:border-gray-400 transition" style="background: <?= $preset ?>; border-radius: 0;"></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="pt-4">
                <button type="submit"
                        class="w-full text-white font-semibold py-3 px-6 transition shadow-lg hover:opacity-90"
                        :style="'background:' + color">
                    Créer ma communauté
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function previewFiles(file, type) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const el = document.querySelector(`[x-ref="${type}Input"]`);
        if (el) {
            const scope = el.closest('[x-data]');
            if (scope && scope._x_dataStack) {
                scope._x_dataStack[0][type + 'Preview'] = e.target.result;
            }
        }
    };
    reader.readAsDataURL(file);
}
lucide.createIcons();
</script>
