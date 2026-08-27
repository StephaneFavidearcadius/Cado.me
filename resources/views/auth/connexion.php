<h2 class="text-2xl font-bold text-gray-900 mb-2">Connexion</h2>
<p class="text-gray-500 mb-6">Accédez à votre espace communautaire</p>

<form method="POST" action="/connexion" class="space-y-5">
    <?= \App\Core\Csrf::field() ?>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
            </div>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                   placeholder="votre@email.com">
        </div>
    </div>

    <!-- Mot de passe -->
    <div>
        <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
            </div>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required
                   class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                   placeholder="••••••••">
        </div>
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40">
        Se connecter
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-sm text-gray-500">
        Pas encore de compte ?
        <a href="/inscription" class="text-violet-500 hover:text-violet-600 font-medium">Créer un compte</a>
    </p>
</div>
