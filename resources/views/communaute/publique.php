<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($communaute['nom']) ?> - Cado.me</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: {
                            50: '#F3EAFF', 100: '#E8D5FF', 200: '#D1B3FF', 300: '#B88FFF',
                            400: '#9B5DEB', 500: '#7830E0', 600: '#6420C7', 700: '#5018A0',
                            800: '#3C1278', 900: '#280C50',
                        }
                    },
                    fontFamily: { 'sora': ['Sora', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'] }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<style>*, *::before, *::after { border-radius: 0 !important; }</style>
<body class="font-sora bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="<?= $estConnecte ?? false ? '/app' : '/' ?>" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <div class="flex items-center gap-3">
                    <?php if ($estConnecte ?? false): ?>
                        <a href="/app" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition">Mon espace</a>
                    <?php else: ?>
                        <a href="/connexion" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-violet-600 transition">Connexion</a>
                        <a href="/inscription" class="px-5 py-2.5 bg-violet-500 hover:bg-violet-600 text-white text-sm font-semibold transition">Rejoindre</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Cover -->
    <div class="h-48 sm:h-64 bg-gradient-to-r from-violet-500 to-violet-400 relative">
        <?php if (!empty($communaute['image_couverture'])): ?>
        <img src="<?= htmlspecialchars($communaute['image_couverture']) ?>" class="w-full h-full object-cover" alt="">
        <?php endif; ?>
    </div>

    <!-- Community Info -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative -mt-16 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-end gap-5">
                    <div class="w-24 h-24 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center">
                        <?php if (!empty($communaute['logo'])): ?>
                        <img src="<?= htmlspecialchars($communaute['logo']) ?>" class="w-22 h-22 rounded-xl object-cover" alt="">
                        <?php else: ?>
                        <span class="text-violet-600 font-bold text-3xl"><?= strtoupper(substr($communaute['nom'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($communaute['nom']) ?></h1>
                        <?php if (!empty($communaute['description'])): ?>
                        <p class="text-gray-500 mt-1 max-w-xl"><?= htmlspecialchars($communaute['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if (App\Core\Session::has('utilisateur_id')): ?>
                    <form method="POST" action="/c/<?= htmlspecialchars($communaute['slug']) ?>/rejoindre">
                        <?= App\Core\Csrf::field() ?>
                        <button type="submit"
                                class="px-6 py-3 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-xl transition shadow-lg shadow-violet-500/25">
                            Rejoindre
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
