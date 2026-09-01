<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-4xl mx-auto" x-data="gestionInvitations()">

    <!-- En-tête -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="/c/<?= $slug ?>/gestion" class="text-sm text-gray-500 hover:text-gray-700 transition flex items-center gap-1 mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la gestion
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Invitations</h1>
            <p class="text-gray-500 mt-1"><?= $nbEnAttente ?> invitation(s) en attente</p>
        </div>
    </div>

    <!-- Formulaire d'envoi -->
    <div class="bg-white border border-gray-100 p-6 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                <i data-lucide="mail-plus" class="w-5 h-5 text-violet-600"></i>
            </div>
            <div>
                <h2 class="font-semibold text-gray-900">Inviter un membre</h2>
                <p class="text-sm text-gray-500">Envoyez une invitation par email</p>
            </div>
        </div>

        <form method="POST" action="/c/<?= $slug ?>/gestion/invitations/envoyer" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                    <input type="email" name="email" required placeholder="exemple@email.com"
                           class="w-full px-4 py-2.5 border border-gray-200 outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                </div>
                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" class="w-full px-4 py-2.5 border border-gray-200 outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                        <option value="membre">Membre</option>
                        <option value="moderateur">Modérateur</option>
                        <option value="administrateur">Administrateur</option>
                    </select>
                </div>
                <div class="sm:self-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-violet-500 text-white font-medium hover:bg-violet-600 transition flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Envoyer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Envoi en masse -->
    <div class="bg-white border border-gray-100 p-6 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                <i data-lucide="mails" class="w-5 h-5 text-violet-600"></i>
            </div>
            <div>
                <h2 class="font-semibold text-gray-900">Envoi en masse</h2>
                <p class="text-sm text-gray-500">Séparez les emails par virgules, espaces ou retours à la ligne</p>
            </div>
            <button @click="masseOuverte = !masseOuverte" class="ml-auto p-2 hover:bg-gray-100 transition">
                <i :data-lucide="masseOuverte ? 'chevron-up' : 'chevron-down'" class="w-5 h-5 text-gray-400"></i>
            </button>
        </div>

        <div x-show="masseOuverte" x-cloak x-transition>
            <form method="POST" action="/c/<?= $slug ?>/gestion/invitations/envoyer-masse" class="space-y-4">
                <?= \App\Core\Csrf::field() ?>

                <div>
                    <textarea name="emails" rows="4" required
                              placeholder="alice@email.com&#10;bob@email.com&#10;charlie@email.com"
                              class="w-full px-4 py-2.5 border border-gray-200 outline-none focus:ring-2 focus:ring-violet-500 font-mono text-sm resize-none"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    <div class="sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle pour tous</label>
                        <select name="role" class="w-full px-4 py-2.5 border border-gray-200 outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                            <option value="membre">Membre</option>
                            <option value="moderateur">Modérateur</option>
                            <option value="administrateur">Administrateur</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-violet-500 text-white font-medium hover:bg-violet-600 transition flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Envoyer à tous
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filtres -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        <a href="/c/<?= $slug ?>/gestion/invitations"
           class="px-4 py-2 text-sm font-medium transition <?= !$filtre ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Toutes
        </a>
        <a href="/c/<?= $slug ?>/gestion/invitations?filtre=en_attente"
           class="px-4 py-2 text-sm font-medium transition whitespace-nowrap <?= ($filtre === 'en_attente') ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            En attente
        </a>
        <a href="/c/<?= $slug ?>/gestion/invitations?filtre=acceptee"
           class="px-4 py-2 text-sm font-medium transition <?= ($filtre === 'acceptee') ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Acceptées
        </a>
        <a href="/c/<?= $slug ?>/gestion/invitations?filtre=expiree"
           class="px-4 py-2 text-sm font-medium transition <?= ($filtre === 'expiree') ? 'bg-violet-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
            Expirées
        </a>
    </div>

    <!-- Liste des invitations -->
    <?php if (empty($invitations)): ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="mail" class="w-8 h-8 text-gray-400"></i>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Aucune invitation</h3>
        <p class="text-sm text-gray-500">Invitez des personnes à rejoindre votre communauté.</p>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            <?php foreach ($invitations as $invitation):
                $estExpiree = strtotime($invitation['expire_le']) < time() && $invitation['acceptee'] === null;
                $estAcceptee = $invitation['acceptee'] == 1;
                $estRefusee = $invitation['acceptee'] == 0;
            ?>
            <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                <div class="flex items-center gap-4 min-w-0">
                    <!-- Avatar / Icône -->
                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center
                        <?= $estAcceptee ? 'bg-emerald-100' : ($estExpiree ? 'bg-gray-100' : ($estRefusee ? 'bg-red-100' : 'bg-violet-100')) ?>">
                        <i data-lucide="<?= $estAcceptee ? 'check-circle' : ($estExpiree ? 'clock' : ($estRefusee ? 'x-circle' : 'mail')) ?>"
                           class="w-5 h-5 <?= $estAcceptee ? 'text-emerald-600' : ($estExpiree ? 'text-gray-400' : ($estRefusee ? 'text-red-500' : 'text-violet-600')) ?>"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($invitation['email']) ?></p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs px-2 py-0.5
                                <?= $invitation['role'] === 'administrateur' ? 'bg-violet-100 text-violet-700' :
                                    ($invitation['role'] === 'moderateur' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') ?>">
                                <?= ucfirst(htmlspecialchars($invitation['role'])) ?>
                            </span>
                            <?php if ($estAcceptee): ?>
                                <span class="text-xs text-emerald-600 font-medium">Acceptée</span>
                            <?php elseif ($estExpiree): ?>
                                <span class="text-xs text-gray-400">Expirée le <?= date('d/m/Y', strtotime($invitation['expire_le'])) ?></span>
                            <?php elseif ($estRefusee): ?>
                                <span class="text-xs text-red-500">Refusée</span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Expire le <?= date('d/m/Y', strtotime($invitation['expire_le'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1 flex-shrink-0">
                    <?php if (!$estAcceptee && !$estRefusee): ?>
                    <!-- Renvoyer -->
                    <form method="POST" action="/c/<?= $slug ?>/gestion/invitations/<?= $invitation['id'] ?>/renvoyer" class="inline">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" title="Renvoyer l'invitation"
                                class="p-2 hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </button>
                    </form>
                    <?php endif; ?>

                    <!-- Supprimer -->
                    <form method="POST" action="/c/<?= $slug ?>/gestion/invitations/<?= $invitation['id'] ?>/supprimer"
                          class="inline" onsubmit="return confirm('Supprimer cette invitation ?')">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" title="Supprimer"
                                class="p-2 hover:bg-red-50 text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function gestionInvitations() {
    return {
        masseOuverte: false,
        init() {
            this.$nextTick(() => lucide.createIcons());
        }
    }
}
</script>
