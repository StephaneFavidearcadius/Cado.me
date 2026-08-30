<?php
$slug = htmlspecialchars($communaute['slug']);
$userId = $_SESSION['utilisateur_id'] ?? 0;
$estAdmin = in_array(($_SESSION['communaute_courante']['role'] ?? ''), ['proprietaire', 'administrateur']);

// Compter les membres en ligne (activité récente)
$db = \App\Core\Database::getInstance();
$stmt = $db->prepare('SELECT COUNT(*) FROM membres_communautes WHERE communaute_id = :cid AND statut = :s');
$stmt->execute(['cid' => $communaute['id'], 's' => 'actif']);
$nbMembres = $stmt->fetchColumn();

$stmt2 = $db->prepare('SELECT COUNT(*) FROM membres_communautes WHERE communaute_id = :cid AND statut = :s AND date_derniere_activite > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
$stmt2->execute(['cid' => $communaute['id'], 's' => 'actif']);
$nbOnline = $stmt2->fetchColumn();

// Derniers membres (pour les avatars)
$stmt3 = $db->prepare('SELECT u.prenom, u.nom, u.avatar FROM membres_communautes mc JOIN utilisateurs u ON u.id = mc.utilisateur_id WHERE mc.communaute_id = :cid AND mc.statut = :s ORDER BY mc.date_adhesion DESC LIMIT 10');
$stmt3->execute(['cid' => $communaute['id'], 's' => 'actif']);
$derniersMembres = $stmt3->fetchAll();
?>

<div class="flex gap-6 max-w-6xl mx-auto" x-data="feedApp()">

    <!-- ===== FIL D'ACTUALITÉ (gauche) ===== -->
    <div class="flex-1 min-w-0">

        <!-- Nouvelle publication -->
        <div class="bg-white border border-gray-100 p-4 mb-4">
            <form id="pubForm" method="POST" action="/c/<?= $slug ?>/publications"
                  enctype="multipart/form-data" class="space-y-3">
                <?= \App\Core\Csrf::field() ?>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                        <span class="text-sm font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                    </div>
                    <textarea name="contenu" rows="2"
                              class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none transition resize-none placeholder-gray-400" style="--tw-ring-color: var(--comm-color);"
                              placeholder="Écrivez quelque chose..."></textarea>
                </div>

                <!-- File inputs -->
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

                <!-- Upload confirmation -->
                <div x-show="previews.length > 0" class="pl-13 flex items-center gap-2 text-xs text-emerald-600 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="previews.length + ' fichier(s) prêt(s) à publier'"></span>
                </div>

                <div class="flex items-center justify-between pl-13 pt-1 border-t border-gray-50">
                    <div class="flex items-center gap-1">
                        <button type="button" @click="$refs.imageInput.click()" class="p-2 transition text-gray-400" title="Image" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''">
                            <i data-lucide="image" class="w-5 h-5"></i>
                        </button>
                        <button type="button" @click="$refs.videoInput.click()" class="p-2 transition text-gray-400" title="Vidéo" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''">
                            <i data-lucide="video" class="w-5 h-5"></i>
                        </button>
                        <button type="button" @click="$refs.fileInput.click()" class="p-2 transition text-gray-400" title="Fichier" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''">
                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                        </button>
                        <!-- Emoji -->
                        <div class="relative" x-data="{ emojiOpen: false }">
                            <button type="button" @click="emojiOpen = !emojiOpen" class="p-2 transition text-gray-400" title="Emoji" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            <div x-show="emojiOpen" @click.outside="emojiOpen = false" x-cloak
                                 class="absolute bottom-full left-0 mb-2 bg-white border border-gray-200 shadow-lg p-3 w-72 z-50">
                                <div class="grid grid-cols-8 gap-1 max-h-48 overflow-y-auto">
                                    <?php
                                    $emojis = ['😀','😂','😍','🥰','😎','🤩','🥳','😇','🤗','🤔','😏','😢','😤','😱','🥺','😴','🤡','💀','👻','❤️','🧡','💛','💚','💙','💜','🖤','🤍','💯','🔥','✨','🎉','🎊','💪','👍','👎','👏','🙌','🤝','🙏','💕','💖','💝','🏆','⭐','🌟','💫','🎯','🚀','💡','📌','✅','❌','⚡','💎','🎵','🎶','📸','🎬','🎓','💻','📱','☕','🍕','🍔'];
                                    foreach ($emojis as $emoji):
                                    ?>
                                    <button type="button" onclick="insertEmojiPub('<?= $emoji ?>'); this.closest('[x-data]').__x.$data.emojiOpen = false"
                                            class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition text-lg"><?= $emoji ?></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" :disabled="publishing"
                            class="text-white font-semibold px-5 py-2 text-sm transition disabled:opacity-50" style="background: var(--comm-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        <span x-show="!publishing">Publier</span>                            <span x-show="publishing">Publication en cours...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Publications -->
        <?php if (!empty($publications)): ?>
        <div class="space-y-3" id="publications-list">
            <?php foreach ($publications as $pub): ?>
            <div class="bg-white border border-gray-100 p-5" x-data="publication(<?= (int)$pub['id'] ?>, <?= (int)$pub['nb_likes'] ?>, <?= (int)$pub['nb_commentaires'] ?>, '<?= htmlspecialchars(addslashes($communaute['slug'])) ?>')" id="pub-<?= $pub['id'] ?>">
                <!-- Author header -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                        <span class="text-sm font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($pub['prenom'], 0, 1)) ?></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($pub['prenom'] . ' ' . $pub['nom']) ?></p>
                            <?php if (($pub['role'] ?? '') === 'proprietaire'): ?>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 text-white" style="background: var(--comm-color);">ADMIN</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-gray-400"><?= date('d M', strtotime($pub['date_creation'])) ?> · <?= htmlspecialchars($communaute['nom']) ?></p>
                    </div>
                </div>

                <!-- Contenu -->
                <?php if (!empty($pub['contenu'])): ?>
                <p class="text-gray-800 mb-3 leading-relaxed text-[15px]"><?= nl2br(htmlspecialchars($pub['contenu'])) ?></p>
                <?php endif; ?>

                <!-- Médias -->
                <?php if (!empty($pub['medias'])): ?>
                <div class="mb-3">
                    <?php foreach ($pub['medias'] as $media): ?>
                        <?php if ($media['type'] === 'image'): ?>
                        <img src="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" alt="" class="w-full max-h-[500px] object-cover border border-gray-100">
                        <?php elseif ($media['type'] === 'video'): ?>
                        <video controls class="w-full max-h-[500px] border border-gray-100">
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

                <!-- Épinglé badge -->
                <?php if (!empty($pub['epinglee'])): ?>
                <div class="flex items-center gap-1.5 text-xs font-medium text-amber-600 mb-2">
                    <i data-lucide="pin" class="w-3.5 h-3.5"></i> Épinglée
                </div>
                <?php endif; ?>

                <!-- Actions bar -->
                <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
                    <!-- Like -->
                    <button @click="toggleLike()" class="flex items-center gap-1.5 text-sm transition"
                            :style="liked ? 'color: var(--comm-color); font-weight: 500' : 'color: #6B7280'">
                        <i data-lucide="heart" class="w-4 h-4" :style="liked ? 'fill: var(--comm-color)' : ''"></i>
                        <span x-text="likeCount"></span>
                    </button>

                    <!-- Comments -->
                    <button @click="showComments = !showComments; if (showComments && comments.length === 0 && commentCount > 0) loadComments()"
                            class="flex items-center gap-1.5 text-sm transition" style="color: #6B7280;">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span x-text="commentCount"></span>
                    </button>

                    <!-- Share -->
                    <button @click="sharePub()" class="flex items-center gap-1.5 text-sm transition" style="color: #6B7280;">
                        <i data-lucide="share-2" class="w-4 h-4"></i>
                    </button>

                    <!-- Bookmark (Favori) -->
                    <button @click="toggleFavori()" class="flex items-center gap-1.5 text-sm transition"
                            :class="isFavori ? 'text-amber-500 font-medium' : 'text-gray-500 hover:text-amber-500'">
                        <i data-lucide="bookmark" class="w-4 h-4" :class="isFavori ? 'fill-amber-500' : ''"></i>
                    </button>

                    <?php if ($estAdmin): ?>
                    <!-- Épingler -->
                    <button @click="toggleEpingle()" class="flex items-center gap-1.5 text-sm transition"
                            :class="isEpinglee ? 'text-amber-600 font-medium' : 'text-gray-500 hover:text-amber-600'">
                        <i data-lucide="pin" class="w-4 h-4"></i>
                    </button>

                    <!-- Supprimer -->
                    <form method="POST" action="/c/<?= $slug ?>/publications/<?= $pub['id'] ?>/supprimer" class="ml-auto" onsubmit="return confirm('Supprimer cette publication ?')">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="flex items-center gap-1.5 text-sm text-red-400 hover:text-red-600 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

                <!-- Comments section -->
                <div x-show="showComments" x-cloak x-transition class="mt-3 pt-3 border-t border-gray-100">
                    <div class="space-y-2 mb-3">
                        <template x-for="c in comments" :key="c.id">
                            <div class="flex items-start gap-2">
                                <div class="w-7 h-7 flex items-center justify-center flex-shrink-0" style="background: var(--comm-color-light);">
                                    <span class="text-[10px] font-bold" style="color: var(--comm-color);" x-text="c.prenom.charAt(0)"></span>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 px-3 py-2 flex-1">
                                    <p class="text-xs font-semibold text-gray-900" x-text="c.prenom + ' ' + c.nom"></p>
                                    <p class="text-sm text-gray-700" x-text="c.contenu"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <form @submit.prevent="sendComment()" class="flex gap-2">
                        <input type="text" x-model="newComment" placeholder="Écrire un commentaire..."
                               class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:border-transparent outline-none" style="--tw-ring-color: var(--comm-color);">
                        <button type="submit" :disabled="!newComment.trim()"
                                class="px-4 py-2 text-white text-sm font-medium hover:opacity-90 transition disabled:opacity-50" style="background: var(--comm-color);">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($lastPage > 1): ?>
        <div class="flex items-center justify-center gap-1 mt-8 mb-8">
            <?php if ($page > 1): ?>
            <a href="/c/<?= $slug ?>/feed?page=<?= $page - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-500 transition">&larr; Précédent</a>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($lastPage, $page + 2); $i++): ?>
            <?php if ($i === $page): ?>
            <span class="px-3 py-2 text-sm font-medium text-white" style="background: var(--comm-color);"><?= $i ?></span>
            <?php else: ?>
            <a href="/c/<?= $slug ?>/feed?page=<?= $i ?>" class="px-3 py-2 text-sm font-medium text-gray-500 transition"><?= $i ?></a>
            <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $lastPage): ?>
            <a href="/c/<?= $slug ?>/feed?page=<?= $page + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-500 transition">Suivant &rarr;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="bg-white border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-5" style="background: var(--comm-color-light);">
                <i data-lucide="message-square" class="w-8 h-8" style="color: var(--comm-color);"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune publication</h3>
            <p class="text-gray-500">Soyez le premier à publier quelque chose !</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- ===== SIDEBAR DROITE (Info communauté) ===== -->
    <aside class="hidden lg:block w-72 flex-shrink-0">
        <div class="sticky top-24 space-y-0">

            <!-- Community card -->
            <div class="bg-white border border-gray-100 overflow-hidden">
                <!-- Cover -->
                <?php if (!empty($communaute['image_couverture'])): ?>
                <div class="h-32 bg-gray-200">
                    <img src="/<?= htmlspecialchars(ltrim($communaute['image_couverture'], '/')) ?>" class="w-full h-full object-cover" alt="">
                </div>
                <?php else: ?>
                <div class="h-32" style="background: var(--comm-color);"></div>
                <?php endif; ?>

                <div class="px-5 pb-5 -mt-10 relative">
                    <!-- Logo -->
                    <?php if (!empty($communaute['logo'])): ?>
                    <img src="/<?= htmlspecialchars(ltrim($communaute['logo'], '/')) ?>" class="w-20 h-20 border-4 border-white object-cover mb-3" alt="">
                    <?php else: ?>
                    <div class="w-20 h-20 border-4 border-white flex items-center justify-center mb-3" style="background: var(--comm-color);">
                        <span class="text-white font-bold text-2xl"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                    </div>
                    <?php endif; ?>

                    <h3 class="font-bold text-gray-900 text-lg"><?= htmlspecialchars($communaute['nom']) ?></h3>
                    <p class="text-xs text-gray-400 mb-3">cado.me/<?= htmlspecialchars($communaute['slug']) ?></p>

                    <?php if (!empty($communaute['description'])): ?>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed"><?= htmlspecialchars(mb_strimwidth($communaute['description'], 0, 200, '...')) ?></p>
                    <?php endif; ?>

                    <!-- Stats -->
                    <div class="flex items-center gap-4 mb-4 text-center">
                        <div class="flex-1">
                            <p class="font-bold text-gray-900"><?= $nbMembres ?></p>
                            <p class="text-[11px] text-gray-400">Membres</p>
                        </div>
                        <div class="flex-1 border-x border-gray-100">
                            <p class="font-bold text-gray-900"><?= $nbOnline ?></p>
                            <p class="text-[11px] text-gray-400">En ligne</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">1</p>
                            <p class="text-[11px] text-gray-400">Admin</p>
                        </div>
                    </div>

                    <!-- Member avatars -->
                    <div class="flex -space-x-2 mb-4">
                        <?php foreach (array_slice($derniersMembres, 0, 8) as $m): ?>
                        <?php if (!empty($m['avatar'])): ?>
                        <img src="/<?= htmlspecialchars(ltrim($m['avatar'], '/')) ?>" class="w-8 h-8 border-2 border-white object-cover">
                        <?php else: ?>
                        <div class="w-8 h-8 border-2 border-white flex items-center justify-center" style="background: var(--comm-color-light);">
                            <span class="text-[10px] font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($m['prenom'], 0, 1)) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($nbMembres > 8): ?>
                        <div class="w-8 h-8 border-2 border-white bg-gray-100 flex items-center justify-center">
                            <span class="text-gray-500 text-[10px] font-bold">+<?= $nbMembres - 8 ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($estAdmin): ?>
                    <a href="/c/<?= $slug ?>/gestion/parametres" class="block w-full text-center py-2.5 border text-sm font-semibold text-white hover:opacity-90 transition" style="background: var(--comm-color);">
                        PARAMÈTRES
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </aside>

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
        isFavori: false,
        isEpinglee: <?= !empty($pub['epinglee']) ? 'true' : 'false' ?>,
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
        },

        async toggleFavori() {
            try {
                const csrfEl = document.querySelector('input[name="_token"]');
                const csrfToken = csrfEl ? csrfEl.value : '';
                const resp = await fetch('/c/' + this.slug + '/publications/' + this.id + '/favori', {
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
                    this.isFavori = data.action === 'add';
                }
            } catch(err) {}
        },

        sharePub() {
            const url = window.location.origin + '/c/' + this.slug + '/feed#pub-' + this.id;
            if (navigator.share) {
                navigator.share({ title: 'Publication Cado.me', url: url });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('Lien copié !');
                });
            }
        },

        async toggleEpingle() {
            try {
                const csrfEl = document.querySelector('input[name="_token"]');
                const csrfToken = csrfEl ? csrfEl.value : '';
                const resp = await fetch('/c/' + this.slug + '/publications/' + this.id + '/epingle', {
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
                    this.isEpinglee = data.epinglee === 1;
                }
            } catch(err) {}
        }
    }
}

function insertEmojiPub(emoji) {
    const textarea = document.querySelector('#pubForm textarea[name="contenu"]');
    if (textarea) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
        textarea.focus();
    }
}
</script>
