<?php $slug = htmlspecialchars($communaute['slug']); ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Gestion de la communauté</h1>
        <p class="text-gray-500 mt-1"><?= htmlspecialchars($communaute['nom']) ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="/c/<?= $slug ?>/gestion/parametres" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                <i data-lucide="settings" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">Paramètres</h3>
            <p class="text-sm text-gray-500 mt-1">Nom, description, couleurs, visibilité</p>
        </a>

        <a href="/c/<?= $slug ?>/membres" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                <i data-lucide="users" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">Membres</h3>
            <p class="text-sm text-gray-500 mt-1">Gérer les membres et les rôles</p>
        </a>

        <a href="/c/<?= $slug ?>/formations" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                <i data-lucide="book-open" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">Formations</h3>
            <p class="text-sm text-gray-500 mt-1">Créer et gérer les formations</p>
        </a>

        <a href="/c/<?= $slug ?>/ressources" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                <i data-lucide="folder" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">Ressources</h3>
            <p class="text-sm text-gray-500 mt-1">Gérer les fichiers et documents</p>
        </a>

        <a href="/c/<?= $slug ?>/evenements" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                <i data-lucide="calendar" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">Événements</h3>
            <p class="text-sm text-gray-500 mt-1">Planifier des événements</p>
        </a>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 opacity-50">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                <i data-lucide="shield" class="w-6 h-6 text-gray-400"></i>
            </div>
            <h3 class="font-semibold text-gray-900">Signalements</h3>
            <p class="text-sm text-gray-500 mt-1">Bientôt disponible</p>
        </div>
    </div>
</div>
