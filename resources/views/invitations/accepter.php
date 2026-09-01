<?php
/**
 * Page publique d'acceptation d'invitation
 * Affichée quand un utilisateur connecté suit le lien d'invitation
 */
$commColor = $invitation['couleur_principale'] ?? '#7830E0';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation - <?= htmlspecialchars($invitation['communaute_nom']) ?> - Cado.me</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { violet: { 50: '#F3EAFF', 100: '#E8D5FF', 200: '#D1B3FF', 300: '#B88FFF', 400: '#9B5DEB', 500: '#7830E0', 600: '#6420C7', 700: '#5018A0', 800: '#3C1278', 900: '#280C50' } },
                    fontFamily: { 'sora': ['Sora', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'] }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>*, *::before, *::after { border-radius: 0 !important; }</style>
</head>
<body class="font-sora bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Carte d'invitation -->
        <div class="bg-white border border-gray-100 overflow-hidden shadow-sm">
            <!-- Header -->
            <div class="px-8 py-10 text-center" style="background: <?= $commColor ?>;">
                <?php if (!empty($invitation['logo'])): ?>
                <img src="<?= htmlspecialchars((new \App\Services\StorageService())->url($invitation['logo'])) ?>"
                     class="w-16 h-16 object-cover mx-auto mb-4 border-4 border-white" alt="">
                <?php else: ?>
                <div class="w-16 h-16 bg-white/20 flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-white"><?= strtoupper(substr($invitation['communaute_nom'], 0, 1)) ?></span>
                </div>
                <?php endif; ?>
                <h1 class="text-xl font-bold text-white mb-2">Vous êtes invité !</h1>
                <p class="text-white/80 text-sm">Rejoignez une communauté sur Cado.me</p>
            </div>

            <!-- Contenu -->
            <div class="px-8 py-8">
                <div class="text-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($invitation['communaute_nom']) ?></h2>
                    <?php if (!empty($invitation['communaute_description'])): ?>
                    <p class="text-sm text-gray-500 leading-relaxed"><?= htmlspecialchars(mb_strimwidth($invitation['communaute_description'], 0, 150, '...')) ?></p>
                    <?php endif; ?>
                </div>

                <div class="bg-gray-50 p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5 text-violet-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Rôle attribué : <span class="text-violet-600"><?= ucfirst(htmlspecialchars($invitation['role'])) ?></span>
                            </p>
                            <p class="text-xs text-gray-500">
                                Expires le <?= date('d/m/Y à H:i', strtotime($invitation['expire_le'])) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Accepter -->
                    <form method="POST" action="/invitation/<?= htmlspecialchars($token) ?>/accepter">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit"
                                class="w-full py-3 text-white font-semibold text-sm hover:opacity-90 transition flex items-center justify-center gap-2"
                                style="background: <?= $commColor ?>;">
                            <i data-lucide="check" class="w-5 h-5"></i>
                            Accepter l'invitation
                        </button>
                    </form>

                    <!-- Refuser -->
                    <form method="POST" action="/invitation/<?= htmlspecialchars($token) ?>/refuser"
                          onsubmit="return confirm('Refuser cette invitation ?')">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit"
                                class="w-full py-3 border border-gray-200 text-gray-600 font-medium text-sm hover:bg-gray-50 transition">
                            Refuser
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">&copy; <?= date('Y') ?> Cado.me — Plateforme communautaire</p>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
