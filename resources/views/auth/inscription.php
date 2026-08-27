<h2 class="text-2xl font-bold text-gray-900 mb-2">Inscription</h2>
<p class="text-gray-500 mb-6">Créez votre compte Cado.me</p>

<form method="POST" action="/inscription" class="space-y-5">
    <?= \App\Core\Csrf::field() ?>

    <div class="grid grid-cols-2 gap-4">
        <!-- Prénom -->
        <div>
            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1.5">Prénom</label>
            <input type="text" id="prenom" name="prenom" required
                   value="<?= htmlspecialchars($old['prenom'] ?? '') ?>"
                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                   placeholder="Jean">
            <?php if (!empty($errors['prenom'])): ?>
            <p class="mt-1 text-xs text-red-500"><?= $errors['prenom'] ?></p>
            <?php endif; ?>
        </div>

        <!-- Nom -->
        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
            <input type="text" id="nom" name="nom" required
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                   placeholder="Dupont">
            <?php if (!empty($errors['nom'])): ?>
            <p class="mt-1 text-xs text-red-500"><?= $errors['nom'] ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($old['email'] ?? '') ?>"
               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
               placeholder="votre@email.com">
        <?php if (!empty($errors['email'])): ?>
        <p class="mt-1 text-xs text-red-500"><?= $errors['email'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Mot de passe -->
    <div>
        <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required
               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
               placeholder="Minimum 8 caractères">
        <?php if (!empty($errors['mot_de_passe'])): ?>
        <p class="mt-1 text-xs text-red-500"><?= $errors['mot_de_passe'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Confirmation -->
    <div>
        <label for="mot_de_passe_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
        <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required
               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
               placeholder="Retapez votre mot de passe">
        <?php if (!empty($errors['mot_de_passe_confirmation'])): ?>
        <p class="mt-1 text-xs text-red-500"><?= $errors['mot_de_passe_confirmation'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40">
        Créer mon compte
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-sm text-gray-500">
        Déjà un compte ?
        <a href="/connexion" class="text-violet-500 hover:text-violet-600 font-medium">Se connecter</a>
    </p>
</div>
