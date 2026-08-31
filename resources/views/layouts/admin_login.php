<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Administration' ?> - Cado.me</title>
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
        [x-cloak] { display: none !important; }
        *, *::before, *::after { border-radius: 0 !important; }
    </style>
</head>
<body class="font-sora bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 bg-violet-500 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/25">
                    <span class="text-white font-bold text-2xl">C</span>
                </div>
                <span class="font-bold text-2xl text-white">Cado.me</span>
            </a>
            <p class="mt-3 text-gray-400">Administration plateforme</p>
        </div>

        <!-- Flash Messages -->
        <?php if (!empty($flash['success'])): ?>
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 flex-shrink-0"></i>
            <span class="text-sm"><?= $flash['success'] ?></span>
        </div>
        <?php endif; ?>

        <?php if (!empty($flash['error'])): ?>
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0"></i>
            <span class="text-sm"><?= $flash['error'] ?></span>
        </div>
        <?php endif; ?>

        <!-- Card -->
        <div class="bg-gray-800 border border-gray-700 p-8">
            <?= $slot ?>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            &copy; <?= date('Y') ?> Cado.me &mdash; Espace administration
        </p>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
