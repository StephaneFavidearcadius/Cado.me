<?php
$estConnecte = !empty($_SESSION['utilisateur_id'] ?? null);
$storage = new \App\Services\StorageService();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrir les communautés - Cado.me</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { violet: { 50:'#F3EAFF',100:'#E8D5FF',200:'#D1B3FF',300:'#B88FFF',400:'#9B5DEB',500:'#7830E0',600:'#6420C7',700:'#5018A0',800:'#3C1278',900:'#280C50' }},
                fontFamily: { 'sora': ['Sora','sans-serif'] }
            }}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>*, *::before, *::after { border-radius: 0 !important; }</style>
</head>
<body class="font-sora bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="<?= $estConnecte ? '/app' : '/' ?>" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <?php if ($estConnecte): ?>
                <a href="/app" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">Mon espace</a>
                <?php else: ?>
                <div class="flex items-center gap-3">
                    <a href="/connexion" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">Commencer</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ search: '' }">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Découvrir les communautés</h1>
                <p class="text-gray-500 mt-2">Trouvez et rejoignez la communauté qui vous correspond</p>
            </div>
            <!-- Barre de recherche -->
            <div class="relative w-full md:w-80">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" x-model="search" placeholder="Rechercher une communauté..."
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
        </div>

        <?php if (!empty($communautes)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($communautes as $comm):
                $commColor = $comm['couleur_principale'] ?? '#7830E0';
                $coverUrl = !empty($comm['image_couverture']) ? $storage->url($comm['image_couverture']) : '';
                $logoUrl = !empty($comm['logo']) ? $storage->url($comm['logo']) : '';
            ?>
            <div x-data x-show="search === '' || '<?= htmlspecialchars(strtolower($comm['nom'])) ?>'.includes(search.toLowerCase())">
                <a href="/c/<?= htmlspecialchars($comm['slug']) ?>"
                   class="bg-white border border-gray-100 overflow-hidden hover:shadow-lg transition group block h-full">
                    <!-- Cover -->
                    <div class="h-36 relative" style="background: linear-gradient(135deg, <?= $commColor ?>33, <?= $commColor ?>66);">
                        <?php if ($coverUrl): ?>
                        <img src="<?= htmlspecialchars($coverUrl) ?>" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-10 h-10" style="color: <?= $commColor ?>55;"></i>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-5">
                        <!-- Logo -->
                        <div class="flex items-end gap-3 -mt-12 mb-3">
                            <div class="w-16 h-16 bg-white border-4 border-white shadow-md flex items-center justify-center flex-shrink-0">
                                <?php if ($logoUrl): ?>
                                <img src="<?= htmlspecialchars($logoUrl) ?>" class="w-16 h-16 object-cover" alt="">
                                <?php else: ?>
                                <span class="text-xl font-bold" style="color: <?= $commColor ?>;"><?= strtoupper(substr($comm['nom'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Nom -->
                        <h3 class="font-bold text-gray-900 group-hover:text-violet-600 transition text-lg"><?= htmlspecialchars($comm['nom']) ?></h3>

                        <?php if (!empty($comm['description'])): ?>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($comm['description']) ?></p>
                        <?php endif; ?>

                        <!-- Meta -->
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                <?= number_format($comm['nombre_membres'] ?? 0) ?> membre(s)
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="<?= $comm['visibilite'] === 'privee' ? 'lock' : 'globe' ?>" class="w-3.5 h-3.5"></i>
                                <?= $comm['visibilite'] === 'privee' ? 'Privée' : 'Publique' ?>
                            </span>
                        </div>

                        <!-- Bouton Rejoindre (connecté seulement) -->
                        <?php if ($estConnecte): ?>
                        <form method="POST" action="/c/<?= htmlspecialchars($comm['slug']) ?>/rejoindre" class="mt-4"
                              onclick="event.stopPropagation(); this.submit();">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit"
                                    class="w-full py-2.5 text-sm font-semibold text-white transition"
                                    style="background: <?= $commColor ?>;">
                                Rejoindre
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white border border-gray-100 p-16 text-center">
            <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-5">
                <i data-lucide="search" class="w-8 h-8 text-violet-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune communauté publique</h3>
            <p class="text-gray-500 mb-6">Soyez le premier à créer une communauté !</p>
            <a href="/inscription" class="inline-block px-8 py-3 bg-violet-500 hover:bg-violet-600 text-white font-semibold transition">
                Créer une communauté
            </a>
        </div>
        <?php endif; ?>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
