<div class="max-w-3xl mx-auto" x-data="feedApp()">

    <!-- Nouvelle publication -->
    <div class="bg-white border border-gray-100 p-5 mb-6">
        <form id="pubForm" method="POST" action="/c/<?= htmlspecialchars($communaute['slug']) ?>/publications"
              enctype="multipart/form-data" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                </div>
                <textarea name="contenu" rows="3"
                          class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition resize-none placeholder-gray-400"
                          placeholder="Partagez quelque chose avec votre communauté..."></textarea>
            </div>

            <!-- File inputs (hidden, using x-ref) -->
            <input type="file" name="images[]" x-ref="imageInput" accept="image/*" multiple class="hidden" @change="previewFiles($event, 'images')">
            <input type="file" name="videos[]" x-ref="videoInput" accept="video/*" multiple class="hidden" @change="previewFiles($event, 'videos')">
            <input type="file" name="fichiers[]" x-ref="fileInput" multiple class="hidden" @change="previewFiles($event, 'fichiers')">

            <!-- File previews -->
            <div x-show="previews.length > 0" class="flex flex-wrap gap-2 pl-13">
                <template x-for="(file, idx) in previews" :key="idx">
                    <div class="relative group">
                        <template x-if="file.type === 'image'">
                            <img :src="file.url" class="w-20 h-20 object-cover border border-gray-200">
                        </template>
                        <template x-if="file.type === 'video'">
                            <video :src="file.url" class="w-20 h-20 object-cover border border-gray-200"></video>
                        </template>
                        <template x-if="file.type === 'file'">
                            <div class="w-20 h-20 bg-gray-50 border border-gray-200 flex items-center justify-center p-1">
                                <span class="text-[10px] text-gray-500 text-center truncate" x-text="file.name"></span>
                            </div>
                        </template>
                        <button type="button" @click="removePreview(idx)"
                                class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                            &times;
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-between pl-13">
                <div class="flex items-center gap-2">
                    <button type="button" @click="$refs.imageInput.click()" class="p-2 hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </button>
                    <button type="button" @click="$refs.videoInput.click()" class="p-2 hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </button>
                    <button type="button" @click="$refs.fileInput.click()" class="p-2 hover:bg-violet-50 transition text-gray-400 hover:text-violet-600">
                        <i data-lucide="paperclip" class="w-5 h-5"></i>
                    </button>
                </div>
                <button type="submit" :disabled="publishing"
                        class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition disabled:opacity-50">
                    <span x-show="!publishing">Publier</span>
                    <span x-show="publishing">Publication...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Publications -->
    <?php if (!empty($publications)): ?>
    <div class="space-y-4" id="publications-list">
        <?php foreach ($publications as $pub): ?>
        <div class="bg-white border border-gray-100 p-5" x-data="publication(<?= (int)$pub['id'] ?>, <?= (int)$pub['nb_likes'] ?>, <?= (int)$pub['nb_commentaires'] ?>, '<?= htmlspecialchars(addslashes($communaute['slug'])) ?>')" id="pub-<?= $pub['id'] ?>">
            <!-- En-tête -->
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
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

            <!-- Médias -->
            <?php if (!empty($pub['medias'])): ?>
            <div class="mb-4 flex flex-wrap gap-2">
                <?php foreach ($pub['medias'] as $media): ?>
                    <?php if ($media['type'] === 'image'): ?>
                    <img src="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" alt="" class="max-w-full max-h-96 object-cover border border-gray-100">
                    <?php elseif ($media['type'] === 'video'): ?>
                    <video controls class="max-w-full max-h-96 border border-gray-100">
                        <source src="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" type="video/mp4">
                    </video>
                    <?php else: ?>
                    <a href="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" target="_blank"
                       class="flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-700 hover:bg-gray-100 transition">
                        <i data-lucide="file" class="w-4 h-4"></i>
                        <?= htmlspecialchars($media['nom_fichier'] ?? 'Fichier') ?>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex items-center gap-6 pt-3 border-t border-gray-100">
                <!-- Like AJAX -->
                <button @click="toggleLike()" class="flex items-center gap-1.5 text-sm transition"
                        :class="liked ? 'text-violet-600 font-medium' : 'text-gray-500 hover:text-violet-600'">
                    <i data-lucide="heart" class="w-4 h-4" :class="liked ? 'fill-violet-600' : ''"></i>
                    <span x-text="likeCount"></span>
                </button>

                <!-- Comment toggle -->
                <button @click="showComments = !showComments; if (showComments && comments.length === 0 && commentCount > 0) loadComments()" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span x-text="commentCount"></span>
                </button>

                <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                </button>

                <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Commentaires -->
            <div x-show="showComments" x-cloak x-transition class="mt-4 pt-4 border-t border-gray-100">
                <!-- Liste commentaires chargés -->
                <div class="space-y-3 mb-3">
                    <template x-for="c in comments" :key="c.id">
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-violet-600 text-[10px] font-bold" x-text="c.prenom.charAt(0)"></span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 px-3 py-2 flex-1">
                                <p class="text-xs font-semibold text-gray-900" x-text="c.prenom + ' ' + c.nom"></p>
                                <p class="text-sm text-gray-700" x-text="c.contenu"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Formulaire commentaire AJAX -->
                <form @submit.prevent="sendComment()" class="flex gap-2">
                    <input type="text" x-model="newComment" placeholder="Écrire un commentaire..."
                           class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                    <button type="submit" :disabled="!newComment.trim()"
                            class="px-4 py-2 bg-violet-500 text-white text-sm font-medium hover:bg-violet-600 transition disabled:opacity-50">
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
        <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-violet-50 transition">&larr; Précédent</a>
        <?php endif; ?>
        <?php for ($i = max(1, $page - 2); $i <= min($lastPage, $page + 2); $i++): ?>
        <?php if ($i === $page): ?>
        <span class="px-3 py-2 text-sm font-medium bg-violet-500 text-white"><?= $i ?></span>
        <?php else: ?>
        <a href="?page=<?= $i ?>" class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-violet-50 transition"><?= $i ?></a>
        <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-violet-50 transition">Suivant &rarr;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="message-square" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune publication</h3>
        <p class="text-gray-500">Soyez le premier à publier quelque chose !</p>
    </div>
    <?php endif; ?>
</div>

<script>
function feedApp() {
    return {
        previews: [],
        publishing: false,

        init() {
            document.getElementById('pubForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                this.publishing = true;
                const formData = new FormData(e.target);
                try {
                    const resp = await fetch(e.target.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await resp.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.errors ? data.errors[0] : 'Erreur lors de la publication.');
                    }
                } catch(err) {
                    alert('Erreur réseau.');
                }
                this.publishing = false;
            });
        },

        previewFiles(event, type) {
            const files = Array.from(event.target.files);
            files.forEach(f => {
                const url = URL.createObjectURL(f);
                this.previews.push({
                    file: f,
                    url: url,
                    name: f.name,
                    type: f.type.startsWith('image/') ? 'image' : f.type.startsWith('video/') ? 'video' : 'file',
                    inputType: type
                });
            });
        },

        removePreview(idx) {
            this.previews.splice(idx, 1);
        }
    }
}

function publication(pubId, nbLikes, nbComments, pubSlug) {
    return {
        id: pubId,
        slug: pubSlug,
        liked: false,
        likeCount: nbLikes,
        commentCount: nbComments,
        showComments: false,
        comments: [],
        newComment: '',

        async toggleLike() {
            try {
                const csrfEl = document.querySelector('input[name="_token"]');
                const csrfToken = csrfEl ? csrfEl.value : '';
                const resp = await fetch('/c/' + this.slug + '/publications/' + this.id + '/like', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: '_token=' + encodeURIComponent(csrfToken)
                });
                const data = await resp.json();
                if (data.success) {
                    if (data.action === 'like') {
                        this.liked = true;
                        this.likeCount++;
                    } else {
                        this.liked = false;
                        if (this.likeCount > 0) this.likeCount--;
                    }
                }
            } catch(err) {
                console.error('Like error:', err);
            }
        },

        async sendComment() {
            if (!this.newComment.trim()) return;
            try {
                const csrfEl = document.querySelector('input[name="_token"]');
                const csrfToken = csrfEl ? csrfEl.value : '';
                const body = 'contenu=' + encodeURIComponent(this.newComment) + '&_token=' + encodeURIComponent(csrfToken);
                const resp = await fetch('/c/' + this.slug + '/publications/' + this.id + '/commentaires', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: body
                });
                const data = await resp.json();
                if (data.success) {
                    const prenom = <?= json_encode($_SESSION['utilisateur_prenom'] ?? 'U') ?>;
                    const nom = <?= json_encode($_SESSION['utilisateur_nom'] ?? '') ?>;
                    this.comments.push({
                        id: data.commentaire_id,
                        prenom: prenom,
                        nom: nom,
                        contenu: this.newComment
                    });
                    this.commentCount++;
                    this.newComment = '';
                } else {
                    console.error('Comment failed:', data);
                }
            } catch(err) {
                console.error('Comment error:', err);
            }
        },

        async loadComments() {
            try {
                const resp = await fetch('/c/' + this.slug + '/publications/' + this.id + '/commentaires', {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (resp.ok) {
                    const data = await resp.json();
                    if (Array.isArray(data)) {
                        this.comments = data;
                    }
                }
            } catch(err) {
                console.error('Load comments error:', err);
            }
        }
    }
}
</script>
