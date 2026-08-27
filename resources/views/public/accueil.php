<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cado.me - Votre plateforme communautaire</title>
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
<body class="font-sora bg-white">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-lg border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/25">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="/connexion" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 rounded-xl transition shadow-lg shadow-violet-500/25">Commencer</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-violet-50 text-violet-600 px-4 py-2 rounded-full text-sm font-medium mb-8">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                Plateforme SaaS multi-communautés
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight mb-6">
                Votre communauté,
                <span class="text-violet-500">votre espace</span>
            </h1>

            <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Créez, gérez et développez votre propre communauté privée.
                Formations, contenus, événements — tout est réuni sur une seule plateforme.
            </p>

            <div class="flex items-center justify-center gap-4">
                <a href="/inscription" class="px-8 py-4 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-2xl transition shadow-xl shadow-violet-500/25 hover:shadow-violet-500/40 text-lg">
                    Créer ma communauté
                </a>
                <a href="/decouvrir" class="px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-2xl transition text-lg">
                    Découvrir
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">Tout ce dont vous avez besoin</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="layout-dashboard" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Feed dynamique</h3>
                    <p class="text-gray-500 leading-relaxed">Partagez du contenu, images, vidéos, fichiers. Vos membres interagissent en temps réel.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="book-open" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Formations</h3>
                    <p class="text-gray-500 leading-relaxed">Créez des cours structurés avec leçons, vidéos et suivi de progression pour vos membres.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="calendar" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Événements</h3>
                    <p class="text-gray-500 leading-relaxed">Planifiez des événements, webinaires etmeet-ups. Gardez votre communauté engagée.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="bg-gradient-to-br from-violet-500 to-violet-700 rounded-3xl p-12 sm:p-16 text-white shadow-2xl shadow-violet-500/30">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Prêt à lancer votre communauté ?</h2>
                <p class="text-violet-100 text-lg mb-8 max-w-xl mx-auto">Rejoignez des centaines de créateurs qui font vivre leur espace sur Cado.me.</p>
                <a href="/inscription" class="inline-block px-8 py-4 bg-white text-violet-600 font-semibold rounded-2xl hover:bg-violet-50 transition shadow-lg text-lg">
                    Créer ma communauté gratuitement
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            © <?= date('Y') ?> Cado.me — Tous droits réservés
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
