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
    <style>
        [x-cloak] { display: none !important; }
        *, *::before, *::after { border-radius: 0 !important; }
        .editorial-grid { background-image: linear-gradient(rgba(120,48,224,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(120,48,224,0.04) 1px, transparent 1px); background-size: 60px 60px; }
        /* Responsive foundations */
        .bottom-nav-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .page-bottom-spacer { height: 80px; } /* space for fixed bottom nav */
        @media (min-width: 1024px) { .page-bottom-spacer { height: 0; } }
        /* Flash messages responsive */
        .flash-msg { max-width: calc(100vw - 2rem); left: 1rem; right: 1rem; }
        @media (min-width: 640px) { .flash-msg { left: auto; right: 1rem; max-width: 24rem; } }
        /* Smooth scroll */
        html { scroll-behavior: smooth; }
        /* Touch-friendly targets */
        @media (pointer: coarse) {
            button, a, [role="button"] { min-height: 44px; }
        }
    </style>
    <script>
    // Fonction pour afficher du texte sans entités HTML doubles
    function safeText(str) {
        if (!str) return '';
        return str.replace(/&amp;/g, '&').replace(/&#039;/g, "'").replace(/&quot;/g, '"').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    }
    </script>
</head>
<body class="font-sora bg-gray-50 text-gray-900 min-h-screen editorial-grid">

    <!-- Flash Messages -->
    <?php if (!empty($flash['success'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 z-50 bg-emerald-500 text-white px-4 sm:px-6 py-3 shadow-lg flex items-center gap-3 transition-all duration-300 flash-msg">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span><?= $flash['success'] ?></span>
        <button @click="show = false" class="ml-2"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 z-50 bg-red-500 text-white px-4 sm:px-6 py-3 shadow-lg flex items-center gap-3 transition-all duration-300 flash-msg">
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
    $commColor = $commActive['couleur_principale'] ?? '#7830E0';
    $commColorLight = $commColor . '18';
    ?>
    <style>:root { --comm-color: <?= $commColor ?>; --comm-color-light: <?= $commColorLight ?>; }</style>    <!-- Header + Mobile Sidebar Drawer (shared Alpine scope) -->
    <div x-data="{ sidebarOpen: false }">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14 sm:h-16">
                <div class="flex items-center gap-3 sm:gap-8">
                    <!-- Hamburger mobile -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 hover:bg-gray-100 -ml-2">
                        <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
                    </button>
                    <a href="/app" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--comm-color);">
                            <span class="text-white font-bold text-lg">C</span>
                        </div>
                        <span class="font-semibold text-lg text-gray-900">Cado.me</span>
                    </a>

                    <?php if (!empty($mesCommunautes)): ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                            <?php if (!empty($commActive['logo'])): ?>
                            <img src="<?= htmlspecialchars((new \App\Services\StorageService())->url($commActive['logo'])) ?>" class="w-7 h-7 object-cover" alt="">
                            <?php else: ?>
                            <div class="w-7 h-7 flex items-center justify-center" style="background: var(--comm-color-light);">
                                <span class="text-xs font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($commActive['nom'] ?? 'C', 0, 1)) ?></span>
                            </div>
                            <?php endif; ?>
                            <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($commActive['nom'] ?? '') ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <?php foreach ($mesCommunautes as $comm):
                                    $cColor = $comm['couleur_principale'] ?? '#7830E0';
                                    $isActive = ($comm['slug'] ?? '') === ($commActive['slug'] ?? '');
                                ?>
                            <a href="/c/<?= htmlspecialchars($comm['slug']) ?>/feed"
                               class="flex items-center gap-3 px-4 py-2 transition" style="<?= $isActive ? 'background:' . $cColor . '12;' : '' ?>">
                                <?php if (!empty($comm['logo'])): ?>
                                <img src="<?= htmlspecialchars((new \App\Services\StorageService())->url($comm['logo'])) ?>" class="w-8 h-8 object-cover" alt="">
                                <?php else: ?>
                                <div class="w-8 h-8 flex items-center justify-center" style="background: <?= $cColor ?>18;">
                                    <span class="text-xs font-bold" style="color: <?= $cColor ?>;"><?= strtoupper(substr($comm['nom'], 0, 1)) ?></span>
                                </div>
                                <?php endif; ?>
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

                <div class="flex items-center gap-1 sm:gap-3">
                    <!-- Découvrir -->
                    <a href="/decouvrir" class="hidden md:flex items-center gap-1.5 text-sm text-gray-500 transition font-medium px-2 py-2" style="--tw-text-opacity:1;" onmouseover="this.style.color='var(--comm-color)'" onmouseout="this.style.color=''">
                        <i data-lucide="compass" class="w-4 h-4"></i>
                        Découvrir
                    </a>

                    <!-- Barre de recherche -->
                    <div class="hidden lg:flex items-center bg-gray-100 px-3 py-2 w-56 xl:w-64">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2"></i>
                        <input type="text" placeholder="Rechercher..." class="bg-transparent text-sm outline-none w-full text-gray-700 placeholder-gray-400">
                    </div>

                    <?php if ($slug):
                        $notifService = new \App\Services\NotificationService();
                        $nbNotifs = $notifService->compterNonLues($commActive['id'] ?? 0, $_SESSION['utilisateur_id'] ?? 0);
                        $msgService = new \App\Services\MessageService();
                        $convs = $msgService->listerConversations($commActive['id'] ?? 0, $_SESSION['utilisateur_id'] ?? 0);
                        $nbMsgs = 0;
                        foreach ($convs as $c) { $nbMsgs += (int)($c['non_lus'] ?? 0); }
                    ?>
                    <!-- Messages icon -->
                    <a href="/c/<?= $slug ?>/messages" class="relative p-2 hover:bg-gray-100 transition hidden sm:flex">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <?php if ($nbMsgs > 0): ?>
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold flex items-center justify-center px-1">
                            <?= $nbMsgs > 99 ? '99+' : $nbMsgs ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- Notifications icon -->
                    <a href="/c/<?= $slug ?>/notifications" class="relative p-2 hover:bg-gray-100 transition hidden sm:flex">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-500"></i>
                        <?php if ($nbNotifs > 0): ?>
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold flex items-center justify-center px-1">
                            <?= $nbNotifs > 99 ? '99+' : $nbNotifs ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--comm-color-light);">
                                <span class="text-sm font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($_SESSION['utilisateur_prenom'] ?? 'U', 0, 1)) ?></span>
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

    <!-- Mobile Sidebar Drawer -->
    <div x-cloak>
        <!-- Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 z-50 lg:hidden"></div>
        <!-- Drawer -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed top-0 left-0 bottom-0 w-72 bg-white z-50 lg:hidden overflow-y-auto shadow-xl">
            <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <?php if (!empty($commActive['logo'])): ?>
                    <img src="<?= htmlspecialchars((new \App\Services\StorageService())->url($commActive['logo'])) ?>" class="w-8 h-8 object-cover" alt="">
                    <?php else: ?>
                    <div class="w-8 h-8 flex items-center justify-center" style="background: var(--comm-color-light);">
                        <span class="text-xs font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($commActive['nom'] ?? 'C', 0, 1)) ?></span>
                    </div>
                    <?php endif; ?>
                    <span class="font-semibold text-sm text-gray-900"><?= htmlspecialchars($commActive['nom'] ?? '') ?></span>
                </div>
                <button @click="sidebarOpen = false" class="p-2 hover:bg-gray-100"><i data-lucide="x" class="w-5 h-5 text-gray-500"></i></button>
            </div>
            <nav class="p-3 space-y-1">
                <?php
                $mobileNavItems = [
                    ['slug' => '/feed', 'label' => 'Communauté', 'icon' => 'layout-dashboard', 'match' => fn($p) => str_ends_with($p, '/feed') || str_ends_with($p, '/app')],
                    ['slug' => '/classroom', 'label' => 'Classe', 'icon' => 'graduation-cap', 'match' => fn($p) => str_contains($p, '/classroom')],
                    ['slug' => '/calendrier', 'label' => 'Calendrier', 'icon' => 'calendar', 'match' => fn($p) => str_contains($p, '/calendrier')],
                    ['slug' => '/membres', 'label' => 'Membres', 'icon' => 'users', 'match' => fn($p) => str_contains($p, '/membres') && !str_contains($p, '/leaderboards')],
                    ['slug' => '/leaderboards', 'label' => 'Classement', 'icon' => 'trophy', 'match' => fn($p) => str_contains($p, '/leaderboards')],
                    ['slug' => '/messages', 'label' => 'Messages', 'icon' => 'message-circle', 'match' => fn($p) => str_contains($p, '/messages')],
                    ['slug' => '/notifications', 'label' => 'Notifications', 'icon' => 'bell', 'match' => fn($p) => str_contains($p, '/notifications')],
                    ['slug' => '/favoris', 'label' => 'Favoris', 'icon' => 'bookmark', 'match' => fn($p) => str_contains($p, '/favoris')],
                    ['slug' => '/a-propos', 'label' => 'À propos', 'icon' => 'info', 'match' => fn($p) => str_contains($p, '/a-propos')],
                ];
                if (in_array(($commActive['role'] ?? ''), ['proprietaire', 'administrateur'])):
                    $mobileNavItems[] = ['slug' => '/gestion', 'label' => 'Paramètres', 'icon' => 'settings', 'match' => fn($p) => str_contains($p, '/gestion')];
                endif;
                foreach ($mobileNavItems as $nav):
                    $active = $nav['match']($currentPath);
                    $activeStyle = $active ? 'background: var(--comm-color-light); color: var(--comm-color); font-weight: 600;' : '';
                ?>
                <a href="/c/<?= $slug ?><?= $nav['slug'] ?>" @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3 py-3 text-sm transition" style="<?= $activeStyle ?> <?= !$active ? 'color: #4B5563;' : '' ?>">
                    <i data-lucide="<?= $nav['icon'] ?>" class="w-5 h-5"></i> <?= $nav['label'] ?>
                </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
    </div>

    <!-- Main with Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-8 page-bottom-spacer">
        <div class="flex gap-6 lg:gap-8">
            <!-- Sidebar Navigation -->
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-1">
                    <?php if ($commActive): ?>
                    <div class="flex items-center gap-3 px-3 py-3 mb-4">
                        <div class="w-10 h-10 flex items-center justify-center" style="background: var(--comm-color-light);">
                            <?php if (!empty($commActive['logo'])): ?>
                            <img src="<?= htmlspecialchars((new \App\Services\StorageService())->url($commActive['logo'])) ?>" class="w-10 h-10 object-cover" alt="">
                            <?php else: ?>
                            <span class="font-bold" style="color: var(--comm-color);"><?= strtoupper(substr($commActive['nom'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($commActive['nom']) ?></p>
                            <p class="text-xs text-gray-400"><?= ucfirst(htmlspecialchars($commActive['slug'] ?? '')) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <nav class="space-y-1">
                        <?php
                        $navItems = [
                            ['slug' => '/feed', 'label' => 'Communauté', 'icon' => 'layout-dashboard', 'match' => fn($p) => str_ends_with($p, '/feed') || str_ends_with($p, '/app')],
                            ['slug' => '/classroom', 'label' => 'Classe', 'icon' => 'graduation-cap', 'match' => fn($p) => str_contains($p, '/classroom')],
                            ['slug' => '/calendrier', 'label' => 'Calendrier', 'icon' => 'calendar', 'match' => fn($p) => str_contains($p, '/calendrier')],
                            ['slug' => '/membres', 'label' => 'Membres', 'icon' => 'users', 'match' => fn($p) => str_contains($p, '/membres') && !str_contains($p, '/leaderboards')],
                            ['slug' => '/leaderboards', 'label' => 'Classement', 'icon' => 'trophy', 'match' => fn($p) => str_contains($p, '/leaderboards')],
                            ['slug' => '/messages', 'label' => 'Messages', 'icon' => 'message-circle', 'match' => fn($p) => str_contains($p, '/messages')],
                            ['slug' => '/notifications', 'label' => 'Notifications', 'icon' => 'bell', 'match' => fn($p) => str_contains($p, '/notifications')],
                            ['slug' => '/favoris', 'label' => 'Favoris', 'icon' => 'bookmark', 'match' => fn($p) => str_contains($p, '/favoris')],
                        ];
                        foreach ($navItems as $nav):
                            $active = $nav['match']($currentPath);
                            $activeStyle = $active ? 'background: var(--comm-color-light); color: var(--comm-color); font-weight: 600;' : '';
                        ?>
                        <a href="/c/<?= $slug ?><?= $nav['slug'] ?>" class="flex items-center gap-3 px-3 py-2.5 text-sm transition" style="<?= $activeStyle ?> <?= !$active ? 'color: #4B5563;' : '' ?>">
                            <i data-lucide="<?= $nav['icon'] ?>" class="w-5 h-5"></i> <?= $nav['label'] ?>
                        </a>
                        <?php endforeach; ?>

                        <hr class="my-3 border-gray-100">

                        <a href="/c/<?= $slug ?>/a-propos" class="flex items-center gap-3 px-3 py-2.5 text-sm transition <?= str_contains($currentPath, '/a-propos') ? 'font-semibold' : '' ?>" style="<?= str_contains($currentPath, '/a-propos') ? 'background: var(--comm-color-light); color: var(--comm-color);' : 'color: #4B5563;' ?>">
                            <i data-lucide="info" class="w-5 h-5"></i> À propos
                        </a>
                        <?php if (in_array(($commActive['role'] ?? ''), ['proprietaire', 'administrateur'])): ?>
                        <a href="/c/<?= $slug ?>/gestion" class="flex items-center gap-3 px-3 py-2.5 text-sm transition" style="<?= str_contains($currentPath, '/gestion') ? 'background: var(--comm-color-light); color: var(--comm-color); font-weight: 600;' : 'color: #4B5563;' ?>">
                            <i data-lucide="settings" class="w-5 h-5"></i> Paramètres
                        </a>
                        <?php endif; ?>
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
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-40 bottom-nav-safe">
        <div class="flex items-center justify-around py-2">
            <a href="/c/<?= $slug ?>/feed" class="flex flex-col items-center gap-1 p-2" style="color: <?= (str_ends_with($currentPath, '/feed') || str_ends_with($currentPath, '/app')) ? 'var(--comm-color)' : '#6B7280' ?>;">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-xs">Communauté</span>
            </a>
            <a href="/c/<?= $slug ?>/membres" class="flex flex-col items-center gap-1 p-2" style="color: <?= str_contains($currentPath, '/membres') ? 'var(--comm-color)' : '#6B7280' ?>;">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="text-xs">Membres</span>
            </a>
            <a href="/c/<?= $slug ?>/formations" class="flex flex-col items-center gap-1 p-2" style="color: <?= str_contains($currentPath, '/formations') ? 'var(--comm-color)' : '#6B7280' ?>;">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-xs">Formations</span>
            </a>
            <a href="/c/<?= $slug ?>/messages" class="flex flex-col items-center gap-1 p-2" style="color: <?= str_contains($currentPath, '/messages') ? 'var(--comm-color)' : '#6B7280' ?>;">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                <span class="text-xs">Messages</span>
            </a>
        </div>
    </nav>

    <!-- Footer -->
    <footer class="border-t border-gray-100 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <p class="text-xs text-gray-400">&copy; <?= date('Y') ?> Cado.me</p>
            <?php if (($_SESSION['role_plateforme'] ?? '') === 'super_administrateur'): ?>
            <a href="/admin" class="inline-flex items-center gap-1.5 text-xs font-medium bg-violet-50 text-violet-600 px-3 py-1 hover:bg-violet-100 transition">
                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                Admin
            </a>
            <?php endif; ?>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
