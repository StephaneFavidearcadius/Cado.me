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
</head>
<style>*, *::before, *::after { border-radius: 0 !important; }</style>
<body class="font-sora bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="/connexion" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 rounded-xl transition">Commencer</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Découvrir les communautés</h1>
            <p class="text-gray-500 mt-2">Trouvez la communauté qui vous correspond</p>
        </div>

        <?php if (!empty($communautes)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($communautes as $comm): ?>
            <a href="/c/<?= htmlspecialchars($comm['slug']) ?>"
               class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition group">
                <div class="h-32 bg-gradient-to-r from-violet-500 to-violet-400 relative">
                    <?php if (!empty($comm['image_couverture'])): ?>
                    <img src="<?= htmlspecialchars($comm['image_couverture']) ?>" class="w-full h-full object-cover" alt="">
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-3 -mt-10 mb-3">
                        <div class="w-14 h-14 rounded-xl bg-white border-2 border-white shadow-md flex items-center justify-center">
                            <?php if (!empty($comm['logo'])): ?>
                            <img src="<?= htmlspecialchars($comm['logo']) ?>" class="w-12 h-12 rounded-lg object-cover" alt="">
                            <?php else: ?>
                            <span class="text-violet-600 font-bold text-xl"><?= strtoupper(substr($comm['nom'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition"><?= htmlspecialchars($comm['nom']) ?></h3>
                    <?php if (!empty($comm['description'])): ?>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($comm['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            <?= $comm['nombre_membres'] ?? 0 ?> membre(s)
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="search" class="w-8 h-8 text-violet-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune communauté publique</h3>
            <p class="text-gray-500">Soyez le premier à créer une communauté !</p>
            <a href="/inscription" class="inline-block mt-4 px-6 py-3 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-xl transition">Créer une communauté</a>
        </div>
        <?php endif; ?>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
