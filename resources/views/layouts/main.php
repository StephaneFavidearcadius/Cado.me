<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Cado.me' ?> - Cado.me</title>

    <!-- Sora Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: {
                            50: '#F3EAFF',
                            100: '#E8D5FF',
                            200: '#D1B3FF',
                            300: '#B88FFF',
                            400: '#9B5DEB',
                            500: '#7830E0',
                            600: '#6420C7',
                            700: '#5018A0',
                            800: '#3C1278',
                            900: '#280C50',
                        }
                    },
                    fontFamily: {
                        'sora': ['Sora', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <base href="/">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sora bg-gray-50 text-gray-900 min-h-screen">

    <!-- Flash Messages -->
    <?php if (!empty($flash['success'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-all duration-300">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span><?= $flash['success'] ?></span>
        <button @click="show = false" class="ml-2"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash['error'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-all duration-300">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span><?= $flash['error'] ?></span>
        <button @click="show = false" class="ml-2"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <?php endif; ?>

    <?php
    $mesCommunautes = $_SESSION['mes_communautes'] ?? [];
    $commActive = $_SESSION['communaute_courante'] ?? ($mesCommunautes[0] ?? null);
    ?>

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Navigation -->
                <div class="flex items-center gap-8">
                    <a href="/app" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">C</span>
                        </div>
                        <span class="font-semibold text-lg text-gray-900">Cado.me</span>
                    </a>

                    <?php if (!empty($mesCommunautes)): ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center">
                                <span class="text-violet-600 text-xs font-bold"><?= strtoupper(substr($commActive['nom'], 0, 1)) ?></span>
                            </div>
                            <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($commActive['nom']) ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <?php foreach ($mesCommunautes as $comm): ?>
                            <a href="/c/<?= htmlspecialchars($comm['slug']) ?>/app"
                               class="flex items-center gap-3 px-4 py-2 hover:bg-violet-50 transition">
                                <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center">
                                    <span class="text-violet-600 text-xs font-bold"><?= strtoupper(substr($comm['nom'], 0, 1)) ?></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($comm['nom']) ?></p>
                                    <p class="text-xs text-gray-500"><?= ucfirst(htmlspecialchars($comm['role'])) ?></p>
                                </div>
                            </a>
                            <?php endforeach; ?>
                            <hr class="my-2 border-gray-100">
                            <a href="/app/communautes/creer" class="flex items-center gap-3 px-4 py-2 hover:bg-violet-50 transition text-violet-600">
                                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                                <span class="text-sm font-medium">Créer une communauté</span>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <?php if ($commActive): ?>
                    <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/notifications" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-500"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-violet-500 rounded-full"></span>
                    </a>
                    <?php endif; ?>

                    <!-- Profile -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center">
                                <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <?php if ($commActive): ?>
                            <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/profil" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 transition text-sm">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i> Mon profil
                            </a>
                            <?php endif; ?>
                            <a href="/app" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 transition text-sm">
                                <i data-lucide="layout-grid" class="w-4 h-4 text-gray-400"></i> Mes communautés
                            </a>
                            <hr class="my-2 border-gray-100">
                            <form method="POST" action="/deconnexion">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-50 transition text-sm text-red-600">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $slot ?>
    </main>

    <!-- Mobile Bottom Nav -->
    <?php if ($commActive): ?>
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-40">
        <div class="flex items-center justify-around py-2">
            <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/app" class="flex flex-col items-center gap-1 p-2 text-violet-600">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-xs">Accueil</span>
            </a>
            <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/membres" class="flex flex-col items-center gap-1 p-2 text-gray-500">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="text-xs">Membres</span>
            </a>
            <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/formations" class="flex flex-col items-center gap-1 p-2 text-gray-500">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-xs">Formations</span>
            </a>
            <a href="/c/<?= htmlspecialchars($commActive['slug']) ?>/messages" class="flex flex-col items-center gap-1 p-2 text-gray-500">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                <span class="text-xs">Messages</span>
            </a>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Icons refresh -->
    <script>lucide.createIcons();</script>
</body>
</html>
