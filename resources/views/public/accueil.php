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
</head>
<style>*, *::before, *::after { border-radius: 0 !important; }</style>
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
                <div class="flex items-center gap-3">
                    <a href="/connexion" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-violet-600 transition">Connexion</a>
                    <a href="/inscription" class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-500 hover:bg-violet-600 rounded-xl transition shadow-sm">Commencer</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-violet-50 text-violet-600 px-4 py-1.5 rounded-full text-xs font-semibold mb-8 border border-violet-100">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                Plateforme SaaS multi-communautés
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 leading-[1.1] mb-6 tracking-tight">
                Votre communauté,
                <span class="text-violet-500">votre espace</span>
            </h1>

            <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Créez, gérez et développez votre propre communauté privée.
                Formations, contenus, événements — tout est réuni sur une seule plateforme.
            </p>

            <div class="flex items-center justify-center gap-4 mb-16">
                <a href="/inscription" class="px-8 py-3.5 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-xl transition shadow-lg shadow-violet-500/20 text-sm">
                    Créer ma communauté
                </a>
                <a href="/decouvrir" class="px-8 py-3.5 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition border border-gray-200 text-sm">
                    Découvrir
                </a>
            </div>

            <!-- Screenshot Preview -->
            <div class="relative mx-auto max-w-5xl">
                <div class="bg-gray-100 rounded-2xl border border-gray-200 overflow-hidden shadow-2xl shadow-gray-200/50">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 text-center">
                            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-1 text-xs text-gray-400">
                                <i data-lucide="lock" class="w-3 h-3"></i>
                                cado.me/app
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-1 space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-violet-50 rounded-xl">
                                    <div class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">AI</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-900">AI MASTERY</div>
                                        <div class="text-[10px] text-gray-400">Publique</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500 text-xs font-bold">S</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-900">SYNAD</div>
                                        <div class="text-[10px] text-gray-400">Privée</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 space-y-4">
                                <div class="bg-white border border-gray-100 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 bg-violet-100 rounded-full flex items-center justify-center">
                                            <span class="text-violet-600 text-xs font-bold">S</span>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold">Stephane F.</div>
                                            <div class="text-[10px] text-gray-400">il y a 2h</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600">Bienvenue dans notre communauté d'IA ! Partagez vos projets et apprenez ensemble.</div>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1"><i data-lucide="heart" class="w-3 h-3"></i> 12</span>
                                    <span class="flex items-center gap-1"><i data-lucide="message-circle" class="w-3 h-3"></i> 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="w-12 h-12 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="layers" class="w-6 h-6 text-violet-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Tout ce dont vous avez besoin</h2>
                <p class="text-gray-500 max-w-2xl mx-auto leading-relaxed">
                    Cado.me offre une suite complète d'outils pour créer et animer votre communauté.
                    Tout est intégré, simple à utiliser et prêt à l'emploi.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Feed dynamique</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Partagez du contenu, images, vidéos, fichiers. Vos membres interagissent en temps réel avec likes et commentaires.</p>
                </div>

                <!-- Feature 2 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="book-open" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Formations structurées</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Créez des cours avec leçons, vidéos et suivi de progression pour vos membres.</p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="calendar" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Événements</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Planifiez des événements, webinaires et meet-ups. Gardez votre communauté engagée et active.</p>
                </div>

                <!-- Feature 4 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="message-circle" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Messagerie privée</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Discutez en direct avec les membres de votre communauté via des conversations privées sécurisées.</p>
                </div>

                <!-- Feature 5 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="folder" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Ressources partagées</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Stockez et partagez des documents, PDFs et fichiers avec votre communauté en toute simplicité.</p>
                </div>

                <!-- Feature 6 -->
                <div>
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Gestion des membres</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Invitez, gérez et suivez vos membres. Rôles, permissions et statistiques intégrés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works / Screenshot Section -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Créez votre communauté
                        en quelques minutes
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Choisissez un nom, personnalisez l'apparence et invitez vos membres.
                        Cado.me s'occupe de tout le reste — hébergement, sécurité et mise à jour.
                    </p>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-violet-600 text-sm font-bold">1</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Créez votre espace</h4>
                                <p class="text-sm text-gray-500">Choisissez un nom et une identité visuelle pour votre communauté.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-violet-600 text-sm font-bold">2</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Invitez vos membres</h4>
                                <p class="text-sm text-gray-500">Partagez votre lien d'invitation et commencez à grandir.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-violet-600 text-sm font-bold">3</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Animez et développez</h4>
                                <p class="text-sm text-gray-500">Publiez du contenu, créez des formations et organisez des événements.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                                    <span class="text-violet-600 font-bold">AI</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">AI MASTERY</div>
                                    <div class="text-xs text-gray-400">12 membres actifs</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-violet-500"></i>
                                </div>
                                Feed dynamique
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                    <i data-lucide="book-open" class="w-4 h-4 text-violet-500"></i>
                                </div>
                                5 formations en cours
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                    <i data-lucide="calendar" class="w-4 h-4 text-violet-500"></i>
                                </div>
                                2 événements à venir
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                    <i data-lucide="message-circle" class="w-4 h-4 text-violet-500"></i>
                                </div>
                                3 nouvelles conversations
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-20 bg-gray-50 border-y border-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <div class="w-12 h-12 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-8">
                <i data-lucide="quote" class="w-6 h-6 text-violet-500"></i>
            </div>
            <blockquote class="text-xl text-gray-700 leading-relaxed mb-8 italic">
                "Cado.me nous a permis de rassembler toute notre communauté d'apprentissage en un seul endroit.
                Les formations, le feed et les événements — tout est exactement là où on a besoin."
            </blockquote>
            <div>
                <p class="font-semibold text-gray-900">Stephane Favide</p>
                <p class="text-sm text-violet-500">Fondateur, AI MASTERY</p>
            </div>
        </div>
    </section>

    <!-- Pricing Teaser -->
    <section id="pricing" class="py-24">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Commencez gratuitement</h2>
            <p class="text-gray-500 mb-12 max-w-xl mx-auto">
                Créez votre première communauté gratuitement. Passez à un plan supérieur quand vous êtes prêt.
            </p>

            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8 text-left">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Gratuit</div>
                    <div class="text-4xl font-bold text-gray-900 mb-1">0€</div>
                    <div class="text-sm text-gray-500 mb-6">pour toujours</div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            1 communauté
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Jusqu'à 50 membres
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            Feed, messages, événements
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="check" class="w-4 h-4 text-violet-500 flex-shrink-0"></i>
                            1 Go de stockage
                        </li>
                    </ul>
                    <a href="/inscription" class="block w-full text-center py-3 bg-violet-50 hover:bg-violet-100 text-violet-600 font-semibold rounded-xl transition text-sm border border-violet-200">
                        Commencer
                    </a>
                </div>

                <!-- Pro Plan -->
                <div class="bg-violet-500 rounded-2xl p-8 text-left text-white relative overflow-hidden">
                    <div class="absolute top-4 right-4 bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Populaire</div>
                    <div class="text-xs font-semibold text-violet-200 uppercase tracking-wide mb-3">Pro</div>
                    <div class="text-4xl font-bold mb-1">29€</div>
                    <div class="text-sm text-violet-200 mb-6">par mois</div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Communautés illimitées
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Membres illimités
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            Formations & certifications
                        </li>
                        <li class="flex items-center gap-3 text-sm text-violet-100">
                            <i data-lucide="check" class="w-4 h-4 text-white flex-shrink-0"></i>
                            50 Go de stockage
                        </li>
                    </ul>
                    <a href="/inscription" class="block w-full text-center py-3 bg-white hover:bg-violet-50 text-violet-600 font-semibold rounded-xl transition text-sm">
                        Choisir Pro
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Resources -->
    <section id="resources" class="py-20 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-12">Ressources</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="book-open" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Guide de démarrage</h3>
                    <p class="text-sm text-gray-500 mb-4 leading-relaxed">Apprenez à créer et gérer votre première communauté avec notre guide pas à pas.</p>
                    <a href="#" class="text-sm text-violet-500 hover:text-violet-600 font-medium">En savoir plus →</a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Rejoignez la communauté</h3>
                    <p class="text-sm text-gray-500 mb-4 leading-relaxed">Connectez-vous avec d'autres créateurs, partagez des astuces et échangez vos idées.</p>
                    <a href="#" class="text-sm text-violet-500 hover:text-violet-600 font-medium">Rejoindre →</a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="file-text" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Documentation</h3>
                    <p class="text-sm text-gray-500 mb-4 leading-relaxed">Référez-vous à notre documentation complète pour chaque fonctionnalité.</p>
                    <a href="#" class="text-sm text-violet-500 hover:text-violet-600 font-medium">Lire →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24">
        <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Prêt à lancer votre communauté ?</h2>
            <p class="text-gray-500 mb-8 max-w-lg mx-auto">
                Rejoignez des centaines de créateurs qui font vivre leur espace sur Cado.me.
            </p>
            <a href="/inscription" class="inline-block px-10 py-4 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-xl transition shadow-lg shadow-violet-500/20 text-sm">
                Créer ma communauté gratuitement
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">C</span>
                        </div>
                        <span class="font-bold text-gray-900">Cado.me</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Votre plateforme communautaire SaaS.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-4">Produit</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-sm text-gray-500 hover:text-violet-600 transition">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="text-sm text-gray-500 hover:text-violet-600 transition">Tarifs</a></li>
                        <li><a href="/decouvrir" class="text-sm text-gray-500 hover:text-violet-600 transition">Découvrir</a></li>
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
                <p class="text-sm text-gray-400">© <?= date('Y') ?> Cado.me — Tous droits réservés</p>
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
