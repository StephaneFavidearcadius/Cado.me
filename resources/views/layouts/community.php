<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Cado.me' ?> - Cado.me</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <base href="/">
    <style>[x-cloak] { display: none !important; }</style>
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
    $slug = htmlspecialchars($commActive['slug'] ?? '');
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    ?>

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
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
                                <span class="text-violet-600 text-xs font-bold"><?= strtoupper(substr($commActive['nom'] ?? 'C', 0, 1)) ?></span>
                            </div>
                            <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($commActive['nom'] ?? '') ?></span>
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

                <div class="flex items-center gap-4">
                    <?php if ($slug): ?>
                    <a href="/c/<?= $slug ?>/notifications" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-500"></i>
                    </a>
                    <?php endif; ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center">
                                <span class="text-violet-600 text-sm font-bold"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <?php if ($slug): ?>
                            <a href="/c/<?= $slug ?>/profil" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 transition text-sm">
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

    <!-- Main with Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-8">
            <!-- Sidebar Navigation -->
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-1">
                    <?php if ($commActive): ?>
                    <div class="flex items-center gap-3 px-3 py-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                            <span class="text-violet-600 font-bold"><?= strtoupper(substr($commActive['nom'], 0, 1)) ?></span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($commActive['nom']) ?></p>
                            <p class="text-xs text-gray-400"><?= ucfirst(htmlspecialchars($commActive['slug'] ?? '')) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <nav class="space-y-1">
                        <a href="/c/<?= $slug ?>/app" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_ends_with($currentPath, '/app') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="home" class="w-5 h-5"></i> Accueil
                        </a>
                        <a href="/c/<?= $slug ?>/feed" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_ends_with($currentPath, '/feed') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Feed
                        </a>
                        <a href="/c/<?= $slug ?>/membres" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/membres') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="users" class="w-5 h-5"></i> Membres
                        </a>
                        <a href="/c/<?= $slug ?>/formations" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/formations') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="book-open" class="w-5 h-5"></i> Formations
                        </a>
                        <a href="/c/<?= $slug ?>/ressources" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/ressources') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="folder" class="w-5 h-5"></i> Ressources
                        </a>
                        <a href="/c/<?= $slug ?>/evenements" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/evenements') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="calendar" class="w-5 h-5"></i> Événements
                        </a>
                        <a href="/c/<?= $slug ?>/messages" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/messages') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="message-circle" class="w-5 h-5"></i> Messages
                        </a>
                        <a href="/c/<?= $slug ?>/notifications" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/notifications') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="bell" class="w-5 h-5"></i> Notifications
                        </a>

                        <hr class="my-3 border-gray-100">

                        <a href="/c/<?= $slug ?>/gestion" class="flex items-center gap-3 px-3 py-2.5 rounded-xl <?= str_contains($currentPath, '/gestion') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> text-sm transition">
                            <i data-lucide="settings" class="w-5 h-5"></i> Gestion
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <?= $slot ?>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Nav -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-40">
        <div class="flex items-center justify-around py-2">
            <a href="/c/<?= $slug ?>/feed" class="flex flex-col items-center gap-1 p-2 <?= str_ends_with($currentPath, '/feed') ? 'text-violet-600' : 'text-gray-500' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-xs">Feed</span>
            </a>
            <a href="/c/<?= $slug ?>/membres" class="flex flex-col items-center gap-1 p-2 <?= str_contains($currentPath, '/membres') ? 'text-violet-600' : 'text-gray-500' ?>">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="text-xs">Membres</span>
            </a>
            <a href="/c/<?= $slug ?>/formations" class="flex flex-col items-center gap-1 p-2 <?= str_contains($currentPath, '/formations') ? 'text-violet-600' : 'text-gray-500' ?>">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-xs">Formations</span>
            </a>
            <a href="/c/<?= $slug ?>/messages" class="flex flex-col items-center gap-1 p-2 <?= str_contains($currentPath, '/messages') ? 'text-violet-600' : 'text-gray-500' ?>">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                <span class="text-xs">Messages</span>
            </a>
        </div>
    </nav>

    <script>lucide.createIcons();</script>
</body>
</html>
