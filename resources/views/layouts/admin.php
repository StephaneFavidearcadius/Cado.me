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
    <style>*, *::before, *::after { border-radius: 0 !important; }</style>
</head>
<body class="font-sora bg-gray-50 min-h-screen">

    <!-- Flash -->
    <?php if (!empty($flash['success'])): ?>
    <div class="fixed top-4 right-4 z-50 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3" x-data x-init="setTimeout(() => $el.remove(), 4000)">
        <span><?= $flash['success'] ?></span>
    </div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
    <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3" x-data x-init="setTimeout(() => $el.remove(), 4000)">
        <span><?= $flash['error'] ?></span>
    </div>
    <?php endif; ?>

    <!-- Admin Header -->
    <header class="bg-gray-900 text-white border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="/admin" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">C</span>
                        </div>
                        <span class="font-semibold">Cado.me</span>
                        <span class="text-xs bg-violet-600 px-2 py-0.5 rounded-full font-medium">Admin</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-1">
                        <a href="/admin" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Dashboard</a>
                        <a href="/admin/communautes" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Communautés</a>
                        <a href="/admin/utilisateurs" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Utilisateurs</a>
                        <a href="/admin/plans" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Plans</a>
                        <a href="/admin/abonnements" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Abonnements</a>
                        <a href="/admin/moderation" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Modération</a>
                        <a href="/admin/parametres" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Paramètres</a>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <a href="/app" class="text-sm text-gray-400 hover:text-white transition">← Retour à l'app</a>
                    <form method="POST" action="/deconnexion" class="inline">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="text-sm text-gray-400 hover:text-red-400 transition">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile nav -->
    <div class="md:hidden bg-gray-900 border-b border-gray-800 overflow-x-auto">
        <nav class="flex items-center gap-1 px-4 py-2 whitespace-nowrap">
            <a href="/admin" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Dashboard</a>
            <a href="/admin/communautes" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Communautés</a>
            <a href="/admin/utilisateurs" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Utilisateurs</a>
            <a href="/admin/plans" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Plans</a>
            <a href="/admin/abonnements" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Abonnements</a>
            <a href="/admin/moderation" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Modération</a>
            <a href="/admin/parametres" class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-800 transition">Paramètres</a>
        </nav>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $slot ?>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
