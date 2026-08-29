<?php
$slug = htmlspecialchars($communaute['slug']);
$userId = $_SESSION['utilisateur_id'] ?? 0;
$otherName = '';
foreach ($participants as $p) {
    if ((int)$p['utilisateur_id'] !== $userId) {
        $otherName = $p['prenom'] . ' ' . $p['nom'];
        break;
    }
}

// Charger les médias pour chaque message
$db = \App\Core\Database::getInstance();
$messageIds = array_column($messages, 'id');
$mediasParMessage = [];
if (!empty($messageIds)) {
    $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
    $stmt = $db->prepare("SELECT * FROM medias_messages WHERE message_id IN ($placeholders) ORDER BY date_creation ASC");
    $stmt->execute($messageIds);
    foreach ($stmt->fetchAll() as $media) {
        $mediasParMessage[$media['message_id']][] = $media;
    }
}
?>

<div class="max-w-3xl mx-auto flex flex-col" style="height: calc(100vh - 140px);" x-data="chatApp()" x-init="init()">

    <!-- Header conversation -->
    <div class="bg-white border border-gray-100 px-6 py-4 flex items-center gap-4 flex-shrink-0">
        <a href="/c/<?= $slug ?>/messages" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
            <span class="text-violet-600 font-bold text-sm"><?= strtoupper(substr($otherName, 0, 1)) ?></span>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($otherName ?: 'Conversation') ?></h2>
            <p class="text-xs text-gray-400"><?= count($participants) ?> participant(s)</p>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50" id="messagesContainer">
        <?php if (empty($messages)): ?>
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-gray-500 text-sm">Pas encore de messages. Envoyez le premier !</p>
        </div>
        <?php else: ?>
        <?php foreach ($messages as $msg): ?>
        <?php $isMine = ((int)$msg['utilisateur_id'] === $userId); ?>
        <div class="flex <?= $isMine ? 'justify-end' : 'justify-start' ?>">
            <div class="max-w-sm lg:max-w-md">
                <?php if (!$isMine): ?>
                <p class="text-xs text-gray-400 mb-1 ml-1"><?= htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']) ?></p>
                <?php endif; ?>

                <?php if (!empty($mediasParMessage[$msg['id']])): ?>
                <?php foreach ($mediasParMessage[$msg['id']] as $media): ?>
                <?php if ($media['type_media'] === 'image'): ?>
                <div class="mb-1 <?= $isMine ? 'text-right' : '' ?>">
                    <img src="/<?= htmlspecialchars($media['chemin']) ?>" alt="<?= htmlspecialchars($media['nom_original']) ?>"
                         class="max-w-full max-h-64 object-cover cursor-pointer hover:opacity-90 transition"
                         onclick="window.open(this.src, '_blank')">
                </div>
                <?php elseif ($media['type_media'] === 'video'): ?>
                <div class="mb-1 <?= $isMine ? 'text-right' : '' ?>">
                    <video controls class="max-w-full max-h-64" preload="metadata">
                        <source src="/<?= htmlspecialchars($media['chemin']) ?>" type="video/<?= pathinfo($media['nom_original'], PATHINFO_EXTENSION) ?>">
                    </video>
                </div>
                <?php else: ?>
                <div class="mb-1 <?= $isMine ? 'justify-end' : '' ?> flex">
                    <a href="/<?= htmlspecialchars($media['chemin']) ?>" target="_blank"
                       class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-100 text-sm hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span class="text-gray-700 truncate max-w-[150px]"><?= htmlspecialchars($media['nom_original']) ?></span>
                        <span class="text-xs text-gray-400">(<?= $media['taille'] > 1048576 ? round($media['taille'] / 1048576, 1) . ' MB' : round($media['taille'] / 1024) . ' KB' ?>)</span>
                    </a>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty(trim($msg['contenu']))): ?>
                <div class="<?= $isMine ? 'bg-violet-500 text-white' : 'bg-white border border-gray-100 text-gray-900' ?> px-4 py-2.5 text-sm">
                    <?= nl2br(htmlspecialchars_decode($msg['contenu'])) ?>
                </div>
                <?php endif; ?>

                <p class="text-xs text-gray-400 mt-1 <?= $isMine ? 'text-right mr-1' : 'ml-1' ?>">
                    <?= date('H:i', strtotime($msg['date_creation'])) ?>
                    <?php if ($isMine && $msg['lu']): ?>
                    <span class="text-violet-400">✓✓</span>
                    <?php elseif ($isMine): ?>
                    <span class="text-gray-300">✓</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Input envoi -->
    <div class="bg-white border border-gray-100 px-4 py-3 flex-shrink-0">
        <!-- Preview des fichiers sélectionnés -->
        <div x-show="selectedFiles.length > 0" class="flex gap-2 mb-3 overflow-x-auto pb-2">
            <template x-for="(file, index) in selectedFiles" :key="index">
                <div class="relative flex-shrink-0">
                    <template x-if="file.type.startsWith('image/')">
                        <img :src="file.preview" class="w-20 h-20 object-cover border border-gray-200">
                    </template>
                    <template x-if="file.type.startsWith('video/')">
                        <div class="w-20 h-20 bg-gray-100 border border-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        </div>
                    </template>
                    <template x-if="!file.type.startsWith('image/') && !file.type.startsWith('video/')">
                        <div class="w-20 h-20 bg-gray-100 border border-gray-200 flex items-center justify-center p-1">
                            <span class="text-xs text-gray-500 text-center truncate" x-text="file.name"></span>
                        </div>
                    </template>
                    <button @click="removeFile(index)" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white flex items-center justify-center text-xs hover:bg-red-600">✕</button>
                </div>
            </template>
        </div>

        <form id="chatForm" method="POST" action="/c/<?= $slug ?>/messages/<?= $conversation_id ?>"
              enctype="multipart/form-data" class="flex items-end gap-2" @submit.prevent="sendMessage">
            <?= \App\Core\Csrf::field() ?>

            <!-- Boutons média + emoji -->
            <div class="flex items-center gap-1 flex-shrink-0 pb-1">
                <label class="p-2 hover:bg-gray-100 transition cursor-pointer text-gray-400 hover:text-violet-600" title="Image">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <input type="file" name="fichiers[]" accept="image/*" multiple class="hidden" @change="addFiles($event)">
                </label>
                <label class="p-2 hover:bg-gray-100 transition cursor-pointer text-gray-400 hover:text-violet-600" title="Vidéo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <input type="file" name="fichiers[]" accept="video/*" class="hidden" @change="addFiles($event)">
                </label>
                <label class="p-2 hover:bg-gray-100 transition cursor-pointer text-gray-400 hover:text-violet-600" title="Fichier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <input type="file" name="fichiers[]" accept=".pdf,.zip,.doc,.docx,.ppt,.pptx,.xls,.xlsx" multiple class="hidden" @change="addFiles($event)">
                </label>

                <!-- Emoji picker -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="p-2 hover:bg-gray-100 transition text-gray-400 hover:text-violet-600" title="Emoji">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute bottom-full left-0 mb-2 bg-white border border-gray-200 shadow-lg p-3 w-72 z-50">
                        <div class="grid grid-cols-8 gap-1 max-h-48 overflow-y-auto">
                            <?php
                            $emojis = ['😀','😂','😍','🥰','😎','🤩','🥳','😇','🤗','🤔','😏','😢','😤','😱','🥺','😴','🤮','🤡','💀','👻','❤️','🧡','💛','💚','💙','💜','🖤','🤍','💯','🔥','✨','🎉','🎊','💪','👍','👎','👏','🙌','🤝','🙏','💕','💖','💝','🏆','⭐','🌟','💫','🎯','🚀','💡','📌','✅','❌','⚡','🔥','💎','🎵','🎶','📸','🎬','🏆','🎓','💻','📱','☕','🍕','🍔','🍟','🌮','🍣','🍰','🍩','🍪'];
                            foreach ($emojis as $emoji):
                            ?>
                            <button type="button" @click="insertEmoji('<?= $emoji ?>'); open = false"
                                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition text-lg"><?= $emoji ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input texte -->
            <div class="flex-1 relative">
                <textarea name="contenu" x-model="newMessage" x-ref="msgInput" @keydown.enter.prevent="sendMessage"
                          rows="1" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none overflow-hidden"
                          placeholder="Écrivez un message..." @input="autoResize($event)"></textarea>
            </div>

            <!-- Bouton envoyer -->
            <button type="submit" :disabled="!newMessage.trim() && selectedFiles.length === 0"
                    class="p-2.5 transition flex-shrink-0 disabled:opacity-30 disabled:cursor-not-allowed"
                    :class="newMessage.trim() || selectedFiles.length > 0 ? 'text-violet-600 hover:bg-violet-50' : 'text-gray-300'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>
</div>

<script>
function chatApp() {
    return {
        newMessage: '',
        selectedFiles: [],
        init() {
            this.scrollToBottom();
        },
        addFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                const preview = URL.createObjectURL(file);
                this.selectedFiles.push({ file, name: file.name, type: file.type, preview });
            });
            event.target.value = '';
        },
        removeFile(index) {
            URL.revokeObjectURL(this.selectedFiles[index].preview);
            this.selectedFiles.splice(index, 1);
        },
        insertEmoji(emoji) {
            this.newMessage += emoji;
            this.$refs.msgInput.focus();
        },
        autoResize(event) {
            const el = event.target;
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        },
        async sendMessage() {
            if (!this.newMessage.trim() && this.selectedFiles.length === 0) return;

            const form = new FormData(document.getElementById('chatForm'));
            const csrfToken = document.querySelector('input[name="_token"]');
            if (csrfToken) form.append('_token', csrfToken.value);
            form.append('contenu', this.newMessage);

            // Ajouter les fichiers
            this.selectedFiles.forEach(f => {
                form.append('fichiers[]', f.file);
            });

            // Sauvegarder le message et les fichiers pour affichage optimiste
            const msgText = this.newMessage;
            const filesSnapshot = [...this.selectedFiles];
            this.newMessage = '';
            this.selectedFiles = [];

            try {
                const resp = await fetch(window.location.pathname, {
                    method: 'POST',
                    body: form,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await resp.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (e) {
                document.getElementById('chatForm').submit();
            }
        },
        scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            if (container) container.scrollTop = container.scrollHeight;
        }
    }
}
</script>
