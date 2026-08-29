<?php
$slug = htmlspecialchars($communaute['slug']);
$estAdmin = in_array(($_SESSION['communaute_courante']['role'] ?? ''), ['proprietaire', 'administrateur']);
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mes favoris</h1>
        <p class="text-gray-500 mt-1 text-sm"><?= count($publications) ?> publication(s) sauvegardée(s)</p>
    </div>

    <?php if (!empty($publications)): ?>
    <div class="space-y-3">
        <?php foreach ($publications as $pub): ?>
        <div class="bg-white border border-gray-100 p-5">
            <!-- Author header -->
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($pub['prenom'], 0, 1)) ?></span>
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

            <?php if (!empty($pub['contenu'])): ?>
            <p class="text-gray-800 mb-3 leading-relaxed text-[15px]"><?= nl2br(htmlspecialchars($pub['contenu'])) ?></p>
            <?php endif; ?>

            <?php if (!empty($pub['medias'])): ?>
            <div class="mb-3">
                <?php foreach ($pub['medias'] as $media): ?>
                    <?php if ($media['type'] === 'image'): ?>
                    <img src="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" alt="" class="w-full max-h-[500px] object-cover border border-gray-100">
                    <?php elseif ($media['type'] === 'video'): ?>
                    <video controls class="w-full max-h-[500px] border border-gray-100">
                        <source src="/<?= htmlspecialchars(ltrim($media['chemin'], '/')) ?>" type="video/mp4">
                    </video>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="flex items-center gap-4 pt-3 border-t border-gray-100 text-sm text-gray-500">
                <span class="flex items-center gap-1.5"><i data-lucide="heart" class="w-4 h-4"></i> <?= $pub['nb_likes'] ?? 0 ?></span>
                <span class="flex items-center gap-1.5"><i data-lucide="message-circle" class="w-4 h-4"></i> <?= $pub['nb_commentaires'] ?? 0 ?></span>
                <a href="/c/<?= $slug ?>/feed" class="ml-auto text-violet-500 hover:text-violet-600 font-medium transition text-xs">Voir dans le feed →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
            <i data-lucide="bookmark" class="w-8 h-8 text-violet-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun favori</h3>
        <p class="text-gray-500">Enregistrez des publications pour les retrouver ici.</p>
    </div>
    <?php endif; ?>
</div>

<script>lucide.createIcons();</script>
