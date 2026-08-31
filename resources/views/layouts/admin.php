<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Admin' ?> - Cado.me</title>
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
    <style>
        *, *::before, *::after { border-radius: 0 !important; }
        [x-cloak] { display: none !important; }
        .sidebar-link.active { background: rgba(120,48,224,0.12); color: #7830E0; font-weight: 600; }
        .sidebar-link:hover { background: rgba(120,48,224,0.06); }
        .sidebar-link { transition: all 0.15s ease; }
        @media (max-width: 1023px) {
            .sidebar-mobile-overlay { display: none; }
            .sidebar-mobile-overlay.open { display: block; }
            .sidebar-panel { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar-panel.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sora bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar overlay -->
    <div class="sidebar-mobile-overlay fixed inset-0 z-40 bg-black/40 lg:hidden" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false" x-cloak></div>

    <!-- Flash Messages -->
    <?php if (!empty($flash['success'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 bg-emerald-500 text-white px-5 py-3 shadow-lg flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="text-sm font-medium"><?= $flash['success'] ?></span>
        <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-5 py-3 shadow-lg flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span class="text-sm font-medium"><?= $flash['error'] ?></span>
        <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <?php endif; ?>

    <div class="flex min-h-screen">

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar-panel fixed lg:sticky top-0 left-0 z-50 lg:z-30 w-64 h-screen bg-gray-900 text-white flex flex-col overflow-y-auto"
               :class="{ 'open': sidebarOpen }">

            <!-- Logo -->
            <div class="px-5 py-5 border-b border-gray-800 flex items-center justify-between">
                <a href="/admin" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <div>
                        <span class="font-semibold text-sm">Cado.me</span>
                        <span class="block text-[10px] text-violet-400 font-medium uppercase tracking-wider">Administration</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <?php
                $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
                $links = [
                    ['href' => '/admin', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                    ['href' => '/admin/communautes', 'icon' => 'layout-grid', 'label' => 'Communautés'],
                    ['href' => '/admin/utilisateurs', 'icon' => 'users', 'label' => 'Utilisateurs'],
                    ['href' => '/admin/plans', 'icon' => 'credit-card', 'label' => 'Plans'],
                    ['href' => '/admin/abonnements', 'icon' => 'repeat', 'label' => 'Abonnements'],
                    ['href' => '/admin/moderation', 'icon' => 'shield-check', 'label' => 'Modération'],
                    ['href' => '/admin/parametres', 'icon' => 'settings', 'label' => 'Paramètres'],
                ];
                ?>
                <?php foreach ($links as $link): ?>
                <?php
                    $isActive = $currentPath === $link['href']
                        || ($link['href'] !== '/admin' && str_starts_with($currentPath, $link['href']));
                ?>
                <a href="<?= $link['href'] ?>"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-gray-300 <?= $isActive ? 'active' : '' ?>">
                    <i data-lucide="<?= $link['icon'] ?>" class="w-[18px] h-[18px] flex-shrink-0"></i>
                    <span><?= $link['label'] ?></span>
                </a>
                <?php endforeach; ?>
            </nav>

            <!-- Bottom section -->
            <div class="border-t border-gray-800 px-3 py-4 space-y-1">
                <a href="/app" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-gray-400 hover:text-white">
                    <i data-lucide="external-link" class="w-[18px] h-[18px] flex-shrink-0"></i>
                    <span>Retour à l'app</span>
                </a>
                <form method="POST" action="/admin/deconnexion">
                    <?= \App\Core\Csrf::field() ?>
                    <button type="submit" class="w-full sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-gray-400 hover:text-red-400">
                        <i data-lucide="log-out" class="w-[18px] h-[18px] flex-shrink-0"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===== MAIN AREA ===== -->
        <div class="flex-1 flex flex-col min-h-screen">

            <!-- Top bar (mobile + desktop) -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-14 flex items-center px-4 lg:px-8 gap-4">
                <!-- Mobile hamburger -->
                <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <!-- Breadcrumb / Page title -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-sm font-semibold text-gray-900 truncate"><?= $titre ?? 'Admin' ?></h1>
                </div>

                <!-- Right actions -->
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium bg-violet-50 text-violet-600 px-2.5 py-1">
                        <i data-lucide="shield" class="w-3 h-3"></i>
                        Super Admin
                    </span>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 lg:p-8">
                <?= $slot ?>
            </main>

            <!-- Footer -->
            <footer class="border-t border-gray-100 px-4 lg:px-8 py-4">
                <p class="text-xs text-gray-400">&copy; <?= date('Y') ?> Cado.me — Panel d'administration</p>
            </footer>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
