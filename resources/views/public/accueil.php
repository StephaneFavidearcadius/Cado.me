<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cado.me - Votre plateforme communautaire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
</head>    <style>
        *, *::before, *::after { border-radius: 0 !important; }
        html { scroll-behavior: smooth; }
        @media (pointer: coarse) { button, a, [role="button"] { min-height: 44px; } }
    </style>
<body class="font-sora bg-white text-gray-900 antialiased">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Cado.me</span>
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a href="/decouvrir" class="text-sm text-gray-500 hover:text-gray-900 transition">Découvrir</a>
                </nav>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="/connexion" class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-3 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 transition shadow-sm whitespace-nowrap">Commencer</a>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════ -->
    <!-- HERO SECTION — NE PAS MODIFIER              -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="pt-24 sm:pt-32 pb-12 sm:pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-violet-50 text-violet-600 px-4 py-1.5 rounded-full text-xs font-semibold mb-8 border border-violet-100">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                Plateforme SaaS multi-communautés
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-gray-900 leading-[1.1] mb-4 sm:mb-6 tracking-tight">
                Votre communauté,
                <span class="text-violet-500">votre espace</span>
            </h1>

            <p class="text-base sm:text-lg text-gray-500 max-w-2xl mx-auto mb-6 sm:mb-10 leading-relaxed px-2">
                Créez, gérez et développez votre propre communauté privée.
                Formations, contenus, événements - tout est réuni sur une seule plateforme.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-10 sm:mb-16 px-4">
                <a href="/inscription" class="w-full sm:w-auto px-8 py-3.5 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-xl transition shadow-lg shadow-violet-500/20 text-sm text-center">
                    Créer ma communauté
                </a>
                <a href="/decouvrir" class="w-full sm:w-auto px-8 py-3.5 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition border border-gray-200 text-sm text-center">
                    Découvrir
                </a>
            </div>

            <!-- Screenshot Preview -->
            <div class="relative mx-auto max-w-5xl px-2">
                <div class="bg-gray-100 border border-gray-200 overflow-hidden shadow-2xl shadow-gray-200/50">
                    <img src="/images/hero-screenshot.png" alt="Aperçu de Cado.me — communauté AI BUILDERS avec feed, sidebar et menu" class="w-full h-auto block" loading="eager">
                </div>
            </div>
        </div>
    </section>
    <!-- ═══════════════════════════════════════════ -->
    <!-- FIN HERO SECTION                            -->
    <!-- ═══════════════════════════════════════════ -->


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 01 — NOTRE CONVICTION               -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-6">Notre conviction</p>
            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4 sm:mb-6">
                <span class="text-gray-400 line-through">Skool</span> n'a pas été pensé pour nous.<br>
                <span class="text-violet-500">Cado.me</span> si.
            </h2>
            <p class="text-base sm:text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed px-2">
                Une plateforme construite pour les créateurs, coachs et formateurs francophones.
                Paiements, langue locale, support qui répond. Tout ce qui manque ailleurs.
            </p>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 02 — DES VRAIES COMMUNAUTÉS        -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 gap-4">
                <div>
                    <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-3">Sur Cado.me aujourd'hui</p>
                    <h2 class="text-3xl font-bold text-gray-900">Des vraies communautés.<br>Des vrais créateurs. De vrais résultats.</h2>
                </div>
                <a href="/decouvrir" class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-violet-600 hover:text-violet-700 transition">
                    Découvrir toutes les communautés
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Community Cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-violet-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">AI</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">AI MASTERY</h3>
                            <p class="text-xs text-gray-400">Intelligence artificielle · Gratuit</p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-xs text-gray-500 mb-4 border-t border-gray-100 pt-4">
                        <span>Feed</span><span>Cours</span><span>Calendrier</span><span>Membres</span>
                    </div>
                    <div class="bg-gray-50 p-3 text-sm text-gray-600">
                        <span class="text-xs text-gray-400">il y a 2h</span><br>
                        Nouveau module : Fondamentaux de l'IA
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-emerald-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">DC</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Digital Career</h3>
                            <p class="text-xs text-gray-400">Marketing digital · 9€/mois</p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-xs text-gray-500 mb-4 border-t border-gray-100 pt-4">
                        <span>Feed</span><span>Cours</span><span>Événements</span><span>Membres</span>
                    </div>
                    <div class="bg-gray-50 p-3 text-sm text-gray-600">
                        <span class="text-xs text-gray-400">il y a 5h</span><br>
                        Live prévu demain à 18h — SEO avancé
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-amber-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">SY</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">SYNAD</h3>
                            <p class="text-xs text-gray-400">Entrepreneuriat · 29€/mois</p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-xs text-gray-500 mb-4 border-t border-gray-100 pt-4">
                        <span>Feed</span><span>Cours</span><span>Calendrier</span><span>Messages</span>
                    </div>
                    <div class="bg-gray-50 p-3 text-sm text-gray-600">
                        <span class="text-xs text-gray-400">il y a 1j</span><br>
                        3 nouveaux membres cette semaine
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center md:hidden">
                <a href="/decouvrir" class="inline-flex items-center gap-2 text-sm font-medium text-violet-600 hover:text-violet-700 transition">
                    Découvrir toutes les communautés
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 03 — TES PAIEMENTS (01)            -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 sm:gap-16 items-center">
                <div>
                    <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">01 · Tes paiements</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Tes paiements, ton argent,<br>ton compte.
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Cado.me te laisse connecter <strong>tes propres comptes</strong> de paiement.
                        L'argent arrive directement chez toi. On ne touche rien, on ne retient rien.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-5 h-5 text-violet-500 flex-shrink-0"></i>
                            Aucune commission sur tes ventes
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-5 h-5 text-violet-500 flex-shrink-0"></i>
                            Versement instantané sur ton compte
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-5 h-5 text-violet-500 flex-shrink-0"></i>
                            Tu changes de provider quand tu veux
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-5 h-5 text-violet-500 flex-shrink-0"></i>
                            Mobile Money + carte bancaire
                        </li>
                    </ul>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i data-lucide="smartphone" class="w-4 h-4 text-violet-500"></i>
                            Mobile Money
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i data-lucide="credit-card" class="w-4 h-4 text-violet-500"></i>
                            Carte bancaire
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-8">
                    <!-- Payment Simulation Card -->
                    <div class="bg-white border border-gray-200 p-6 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                                    <span class="text-violet-600 font-bold text-sm">S</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Stephane paie</p>
                                    <p class="text-xs text-gray-400">9€ · Carte bancaire</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-violet-500 bg-violet-50 px-3 py-1">0% pour Cado.me</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center py-3">
                        <i data-lucide="arrow-down" class="w-5 h-5 text-gray-300"></i>
                    </div>
                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Reçu sur ton compte</p>
                                <p class="text-xs text-gray-400">+ 9€ · maintenant</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-xs text-gray-400 mt-4">Versement direct · 0% commission</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 04 — FORMATIONS (02)               -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 sm:gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <!-- Course Preview Card -->
                    <div class="bg-white border border-gray-200 overflow-hidden">
                        <div class="bg-violet-500 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 flex items-center justify-center">
                                <i data-lucide="play" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <p class="text-white text-sm font-semibold">Module 01 · Bases</p>
                                <p class="text-violet-200 text-xs">Fondamentaux de l'IA</p>
                            </div>
                            <span class="ml-auto text-violet-200 text-xs">14:23</span>
                        </div>
                        <div class="p-5">
                            <h4 class="font-semibold text-gray-900 text-sm mb-2">Créer et vendre des produits numériques à l'aide de l'IA</h4>
                            <p class="text-xs text-gray-400 mb-3">3 / 5 leçons · Vidéo + 2 documents</p>
                            <div class="w-full bg-gray-100 h-1.5">
                                <div class="bg-violet-500 h-1.5" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-white border border-gray-200 p-4">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Espace cours</span>
                            <span class="font-semibold text-gray-900">32 / 50 Go</span>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">02 · Formations</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Des formations comme tu les rêves.
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Modules, leçons, vidéos, quiz. Stocké chez nous ou hébergé ailleurs, c'est toi qui choisis.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="upload" class="w-5 h-5 text-violet-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Upload vidéo direct</h4>
                                <p class="text-sm text-gray-500">MP4, WebM, MOV — jusqu'à 100 Go</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="link" class="w-5 h-5 text-violet-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Liens externes</h4>
                                <p class="text-sm text-gray-500">YouTube non répertorié, Vimeo privé, Dailymotion</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="folder" class="w-5 h-5 text-violet-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Documents & fichiers</h4>
                                <p class="text-sm text-gray-500">PDFs, templates, ressources téléchargeables</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="bar-chart-2" class="w-5 h-5 text-violet-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Suivi de progression</h4>
                                <p class="text-sm text-gray-500">Barres de progression, leçons complétées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 05 — SIX ESPACES (03)              -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">03 · Tout dans le même espace</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Six espaces. Une seule communauté.</h2>
                <p class="text-gray-500 max-w-xl mx-auto">Pas dix outils à connecter, pas dix abonnements à gérer. Tout est là, dès le premier jour.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
                <!-- Feed -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Feed</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Un fil de discussion qui vit.</strong> Posts, likes, commentaires, épinglage, images, vidéos, fichiers. Le quotidien de ta communauté.</p>
                </div>

                <!-- Messagerie -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="message-circle" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Messagerie</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Discussions privées et groupe.</strong> Envoie des messages, images, vidéos et fichiers. Comme Messenger, intégré à ta communauté.</p>
                </div>

                <!-- Formations -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="book-open" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Formations</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Des cours structurés.</strong> Modules, leçons, vidéos, documents. Progression trackée pour chaque membre.</p>
                </div>

                <!-- Calendrier -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="calendar" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Calendrier</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Tout ce qui arrive.</strong> Lives, ateliers, deadlines. Liens Google Meet intégrés. Rappels avant chaque événement.</p>
                </div>

                <!-- Membres -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Membres</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Profils, rôles, classement.</strong> Créateur, modérateur, membre. Leaderboard, badges, avatars.</p>
                </div>

                <!-- À propos -->
                <div class="border border-gray-200 p-6">
                    <div class="w-10 h-10 bg-violet-100 flex items-center justify-center mb-4">
                        <i data-lucide="globe" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Page publique</h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><strong class="text-gray-700">Une vraie page de vente.</strong> cado.me/ton-nom. Présentation, prix, témoignages, bouton de paiement. Partage sur WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 06 — PAGE PUBLIQUE (04)            -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 sm:gap-16 items-center">
                <div>
                    <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">04 · Page publique</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Ta communauté a une vraie URL.<br>Et un vrai site.
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Chaque communauté a sa page publique sur <code class="bg-gray-100 px-2 py-0.5 text-violet-600 text-sm">cado.me/ton-nom</code>.
                        Présentation, témoignages, prix, bouton de paiement. Partage le lien sur WhatsApp, TikTok ou Instagram, les inscriptions arrivent toutes seules.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="link" class="w-5 h-5 text-violet-500 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">URL claire et partageable</p>
                                <p class="text-sm text-gray-500">cado.me/ton-nom — courte, lisible, parfaite pour un message WhatsApp ou un bio Instagram.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="search" class="w-5 h-5 text-violet-500 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">SEO optimisé</p>
                                <p class="text-sm text-gray-500">Ta page est indexée par Google. Les gens te trouvent naturellement.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="zap" class="w-5 h-5 text-violet-500 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Conversion rapide</p>
                                <p class="text-sm text-gray-500">Paiement en 3 taps, sans création de compte préalable.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 overflow-hidden shadow-xl">
                    <!-- Browser chrome -->
                    <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-200 flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 bg-red-400"></div>
                            <div class="w-2.5 h-2.5 bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 bg-green-400"></div>
                        </div>
                        <div class="flex-1 text-center">
                            <div class="inline-flex items-center gap-1.5 bg-white border border-gray-200 px-3 py-1 text-xs text-gray-400 w-full max-w-xs">
                                <i data-lucide="lock" class="w-3 h-3"></i>
                                cado.me/ai-mastery
                            </div>
                        </div>
                    </div>
                    <!-- Page content preview -->
                    <div class="h-64 bg-gradient-to-br from-violet-500 to-violet-700 p-6 flex flex-col justify-end">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-white flex items-center justify-center">
                                <span class="text-violet-600 font-bold">AI</span>
                            </div>
                            <div>
                                <h3 class="text-white font-bold">AI MASTERY</h3>
                                <p class="text-violet-200 text-xs">Intelligence artificielle · 12 membres</p>
                            </div>
                        </div>
                        <p class="text-violet-100 text-sm">Créez, vendez et automatisez vos produits numériques grâce à l'IA.</p>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex gap-4 text-xs text-gray-500">
                            <span><strong class="text-gray-900">12</strong> membres</span>
                            <span><strong class="text-gray-900">5</strong> cours</span>
                            <span><strong class="text-gray-900">4.8</strong> ★</span>
                        </div>
                        <span class="bg-violet-500 text-white text-xs font-semibold px-4 py-2">Rejoindre</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 07 — 5 ÉTAPES                      -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">De zéro à payé</p>
                <h2 class="text-3xl font-bold text-gray-900">5 étapes. Une seule après-midi.</h2>
            </div>

            <div class="space-y-8">
                <div class="flex gap-6 items-start">
                    <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 font-bold">01</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Tu choisis ton plan</h3>
                        <p class="text-sm text-gray-500">Essentiel à 9€/mois ou Pro à 29€/mois. C'est tout. Pas de commission.</p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 font-bold">02</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Tu connectes tes paiements</h3>
                        <p class="text-sm text-gray-500">Mobile Money et carte bancaire. Tes clés, tes comptes, ton argent.</p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 font-bold">03</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Tu construis ta communauté</h3>
                        <p class="text-sm text-gray-500">Feed, formations, cours, événements, messagerie. Le tout en français, intuitif.</p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 font-bold">04</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Tu partages le lien</h3>
                        <p class="text-sm text-gray-500">cado.me/ton-nom. WhatsApp, Instagram, TikTok. Les inscriptions arrivent.</p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="w-12 h-12 bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-violet-600 font-bold">05</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Tu encaisses, on dort</h3>
                        <p class="text-sm text-gray-500">Chaque paiement va direct sur ton compte. Cado.me ne voit jamais l'argent passer.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 08 — TARIFS                        -->
    <!-- ═══════════════════════════════════════════ -->
    <section id="pricing" class="py-16 sm:py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">Tarifs</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Un abonnement. Une communauté.</h2>
                <p class="text-gray-500">La techno, l'hébergement, le support. Zéro commission sur tes paiements.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <!-- Essentiel -->
                <div class="bg-white border border-gray-200 p-8">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Essentiel</div>
                    <p class="text-sm text-gray-500 mb-4">Pour démarrer fort</p>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-bold text-gray-900">9</span>
                        <span class="text-lg text-gray-500">€ / mois</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Jusqu'à <strong>100 membres</strong>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Cours, modules, leçons illimités
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Encaissement direct, 0% commission
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            10 Go de stockage
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Page publique cado.me/ton-nom
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Support en français
                        </li>
                    </ul>
                    <a href="/inscription" class="block w-full text-center py-3 bg-violet-50 hover:bg-violet-100 text-violet-600 font-semibold transition text-sm border border-violet-200">
                        Commencer en Essentiel
                    </a>
                </div>

                <!-- Pro -->
                <div class="bg-violet-500 p-8 text-white relative overflow-hidden">
                    <div class="absolute top-4 right-4 bg-white/20 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-wide">Recommandé</div>
                    <div class="text-xs font-semibold text-violet-200 uppercase tracking-wide mb-3">Pro</div>
                    <p class="text-sm text-violet-200 mb-4">Pour les créateurs qui scalent</p>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-bold text-white">29</span>
                        <span class="text-lg text-violet-200">€ / mois</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            <strong>Tout l'Essentiel</strong>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            <strong>Membres illimités</strong>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Analytiques avancées
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            50 Go de stockage
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Branding personnalisé (logo, couleurs)
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Support prioritaire
                        </li>
                    </ul>
                    <a href="/inscription" class="block w-full text-center py-3 bg-white hover:bg-violet-50 text-violet-600 font-semibold transition text-sm">
                        Passer en Pro
                    </a>
                </div>
            </div>

            <!-- Zero Commission Banner -->
            <div class="mt-12 text-center">
                <div class="inline-flex items-center gap-4 bg-white border border-gray-200 px-8 py-5">
                    <span class="text-4xl font-extrabold text-violet-500">0%</span>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-900">Aucune commission sur ce que tu encaisses.</p>
                        <p class="text-xs text-gray-500">Si ta communauté fait 1000€ ce mois, tu reçois 1000€. Point. Tu paies juste ton abonnement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 09 — COMPARAISON                   -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">Comparaison</p>
                <h2 class="text-3xl font-bold text-gray-900">Pourquoi pas <span class="text-gray-400 line-through">Skool</span>, <span class="text-gray-400 line-through">Kajabi</span> ?</h2>
            </div>                <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-4 pr-4 font-semibold text-gray-900"></th>
                            <th class="py-4 px-4 font-semibold text-violet-600 bg-violet-50">Cado.me</th>
                            <th class="py-4 px-4 font-semibold text-gray-500">Skool</th>
                            <th class="py-4 px-4 font-semibold text-gray-500">Kajabi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">Paiement Mobile Money + carte</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50"><i data-lucide="check" class="w-4 h-4 text-violet-500 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">Encaissement direct sur ton compte</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50"><i data-lucide="check" class="w-4 h-4 text-violet-500 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">Commission sur tes ventes</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50 font-semibold text-violet-600">0%</td>
                            <td class="py-3.5 px-4 text-center text-gray-500">2.9%</td>
                            <td class="py-3.5 px-4 text-center text-gray-500">0% mais 149$/mois</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">Interface en français</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50"><i data-lucide="check" class="w-4 h-4 text-violet-500 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">Support francophone</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50"><i data-lucide="check" class="w-4 h-4 text-violet-500 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                            <td class="py-3.5 px-4 text-center"><i data-lucide="x" class="w-4 h-4 text-gray-300 inline"></i></td>
                        </tr>
                        <tr>
                            <td class="py-3.5 pr-4 font-medium text-gray-900">Tarif d'entrée</td>
                            <td class="py-3.5 px-4 text-center bg-violet-50/50 font-semibold text-violet-600">9€</td>
                            <td class="py-3.5 px-4 text-center text-gray-500">99$/mois</td>
                            <td class="py-3.5 px-4 text-center text-gray-500">149$/mois</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 10 — TÉMOIGNAGES                   -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">Sur le terrain</p>
                <h2 class="text-3xl font-bold text-gray-900">Des créateurs. Des coachs. Des formateurs.</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white border border-gray-200 p-6">
                    <blockquote class="text-sm text-gray-600 leading-relaxed mb-6 italic">
                        « Le mois dernier, ma communauté a généré 850€ d'abonnements. Cado.me n'a pris aucun pourboire. Tout est arrivé sur mon compte, comme prévu. »
                    </blockquote>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                            <span class="text-violet-600 font-bold text-sm">AD</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Amadou Diop</p>
                            <p class="text-xs text-gray-400">Coach business · Dakar · 165 membres</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <blockquote class="text-sm text-gray-600 leading-relaxed mb-6 italic">
                        « Mes étudiants paient en Mobile Money depuis leur téléphone. Aucune friction, aucun blocage. Enfin une plateforme qui nous comprend. »
                    </blockquote>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                            <span class="text-violet-600 font-bold text-sm">CN</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Cheikh Ndao</p>
                            <p class="text-xs text-gray-400">Prof de maths · Thiès</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-6">
                    <blockquote class="text-sm text-gray-600 leading-relaxed mb-6 italic">
                        « Avant : Telegram + Excel + virements. Maintenant : un lien cado.me et c'est fini. Mes 200 membres sont tous dedans. »
                    </blockquote>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 flex items-center justify-center">
                            <span class="text-violet-600 font-bold text-sm">FS</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Fatou Sarr</p>
                            <p class="text-xs text-gray-400">Créatrice de contenu · Abidjan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <div class="inline-flex items-center gap-3 bg-white border border-gray-200 px-6 py-3">
                    <span class="text-2xl font-extrabold text-violet-500">+850€</span>
                    <span class="text-sm text-gray-500">encaissés en 30 jours<br>par une seule communauté</span>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 11 — FAQ                           -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-4">Questions fréquentes</p>
                <h2 class="text-3xl font-bold text-gray-900">Ce que les gens demandent.</h2>
            </div>

            <div class="space-y-0" x-data="{ open: null }">
                <!-- FAQ 1 -->
                <div class="border-b border-gray-200">
                    <button @click="open = open === 1 ? null : 1" class="w-full flex items-center justify-between py-5 text-left">
                        <span class="text-sm font-semibold text-gray-900 pr-4">Comment fonctionnent les paiements ?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="open === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === 1" x-collapse>
                        <p class="text-sm text-gray-500 pb-5 leading-relaxed">
                            Tu crées un compte chez le processeur de paiement de ton choix (Mobile Money local ou Stripe pour la carte bancaire). Tu colles tes clés dans ton dashboard Cado.me, et c'est tout. À chaque paiement d'un membre, l'argent arrive directement sur ton compte. Cado.me n'est jamais sur le chemin de l'argent.
                        </p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border-b border-gray-200">
                    <button @click="open = open === 2 ? null : 2" class="w-full flex items-center justify-between py-5 text-left">
                        <span class="text-sm font-semibold text-gray-900 pr-4">Cado.me prend-il une commission ?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="open === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === 2" x-collapse>
                        <p class="text-sm text-gray-500 pb-5 leading-relaxed">
                            Zéro. Vraiment zéro. Tu paies un abonnement mensuel fixe (Essentiel 9€ ou Pro 29€), et tu encaisses 100% de ce que tes membres paient.
                        </p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border-b border-gray-200">
                    <button @click="open = open === 3 ? null : 3" class="w-full flex items-center justify-between py-5 text-left">
                        <span class="text-sm font-semibold text-gray-900 pr-4">Quels moyens de paiement sont acceptés pour les membres ?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="open === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === 3" x-collapse>
                        <p class="text-sm text-gray-500 pb-5 leading-relaxed">
                            Mobile Money (Wave, Orange Money, MTN, Free Money…) et carte bancaire internationale via Stripe. Tu peux activer les deux en même temps.
                        </p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border-b border-gray-200">
                    <button @click="open = open === 4 ? null : 4" class="w-full flex items-center justify-between py-5 text-left">
                        <span class="text-sm font-semibold text-gray-900 pr-4">Y a-t-il une limite sur les vidéos et cours ?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="open === 4 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === 4" x-collapse>
                        <p class="text-sm text-gray-500 pb-5 leading-relaxed">
                            Essentiel te donne 10 Go d'espace, Pro 50 Go. Tu peux aussi héberger tes vidéos sur YouTube (non répertorié), Vimeo privé ou Dailymotion. Aucune limite sur le nombre de cours ou de leçons.
                        </p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="border-b border-gray-200">
                    <button @click="open = open === 5 ? null : 5" class="w-full flex items-center justify-between py-5 text-left">
                        <span class="text-sm font-semibold text-gray-900 pr-4">Est-ce sans engagement ?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="open === 5 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === 5" x-collapse>
                        <p class="text-sm text-gray-500 pb-5 leading-relaxed">
                            Non. Mensuel, sans engagement. Tu peux arrêter quand tu veux, ta communauté et tes données restent exportables.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════ -->
    <!-- SECTION 12 — CTA FINAL                     -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-16 sm:py-24 bg-violet-500">
        <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mb-4 leading-tight">
                Ta communauté n'attend que toi.
            </h2>
            <p class="text-violet-200 mb-6 sm:mb-10 max-w-lg mx-auto text-sm sm:text-lg px-4">
                9€ pour démarrer. 0% sur ce que tu encaisses. Aucun outil à brancher en plus.
            </p>
            <a href="/inscription" class="block sm:inline-block w-full sm:w-auto px-10 py-4 bg-white hover:bg-violet-50 text-violet-600 font-bold transition text-sm shadow-xl text-center">
                Créer ma communauté maintenant
            </a>
            <p class="text-violet-300 text-xs mt-4">2 minutes pour créer · Aucune carte requise</p>
        </div>
    </section>


    <!-- Footer -->
    <footer class="border-t border-gray-100 py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 sm:gap-8 mb-10 sm:mb-12">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">C</span>
                        </div>
                        <span class="font-bold text-gray-900">Cado.me</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Votre plateforme communautaire.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-4">Produit</h4>
                    <ul class="space-y-2">
                        <li><a href="#pricing" class="text-sm text-gray-500 hover:text-violet-600 transition">Tarifs</a></li>
                        <li><a href="/decouvrir" class="text-sm text-gray-500 hover:text-violet-600 transition">Découvrir</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Fonctionnalités</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-4">Communauté</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Blog</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Forum</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">CGU</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Confidentialité</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Cookies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-4">Entreprise</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">À propos</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Contact</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-violet-600 transition">Carrières</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-400">&copy; <?= date('Y') ?> Cado.me — Tous droits réservés</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-400 hover:text-violet-500 transition"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                    <a href="#" class="text-gray-400 hover:text-violet-500 transition"><i data-lucide="github" class="w-4 h-4"></i></a>
                    <a href="#" class="text-gray-400 hover:text-violet-500 transition"><i data-lucide="linkedin" class="w-4 h-4"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
