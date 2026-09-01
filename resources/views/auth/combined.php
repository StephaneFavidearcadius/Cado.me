<div x-data="{ activeTab: '<?= $activeTab ?? 'connexion' ?>', // Allow overriding from PHP $resetToken: '<?= $reset_token ?? '' ?>' }">
    <!-- Tabs Onglets -->
    <div class="flex mb-6 bg-gray-100 p-1">
        <button @click="activeTab = 'connexion'"
                :class="activeTab === 'connexion' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="flex-1 py-2.5 text-sm transition">
            Connexion
        </button>
        <button @click="activeTab = 'inscription'"
                :class="activeTab === 'inscription' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="flex-1 py-2.5 text-sm transition">
            Inscription
        </button>
    </div>

    <!-- ===== FORMULAIRE CONNEXION ===== -->
    <div x-show="activeTab === 'connexion'" x-transition>
        <p class="text-gray-500 mb-6 text-sm text-center">Connectez-vous pour accéder à votre espace.</p>

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
                           class="block w-full pl-11 pr-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="votre@email.com">
                </div>
            </div>

            <!-- Mot de passe avec toggle -->
            <div>
                <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input :type="show ? 'text' : 'password'" id="mot_de_passe" name="mot_de_passe" required
                           class="block w-full pl-11 pr-12 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <!-- Eye (mot de passe masqué) -->
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <!-- EyeOff (mot de passe visible) -->
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mot de passe oublié -->
            <div class="text-right">
                <a href="/mot-de-passe-oublie" @click.prevent="activeTab = 'reset_request'" class="text-sm text-violet-500 hover:text-violet-600 font-medium">
                    Mot de passe oublié ?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 transition duration-200">
                Se connecter
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="#" @click.prevent="$dispatch('switch-to-register')" class="text-sm text-violet-500 hover:text-violet-600 font-medium">
                Pas encore de compte ? Créer un compte
            </a>
        </div>
    </div>

    <!-- ===== FORMULAIRE INSCRIPTION ===== -->
    <div x-show="activeTab === 'inscription'" x-transition>
        <p class="text-gray-500 mb-6 text-sm text-center">Créez votre compte Cado.me</p>

        <form method="POST" action="/inscription" class="space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <div class="grid grid-cols-2 gap-4">
                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1.5">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required
                           value="<?= htmlspecialchars($old['prenom'] ?? '') ?>"
                           class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
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
                           class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="Dupont">
                    <?php if (!empty($errors['nom'])): ?>
                    <p class="mt-1 text-xs text-red-500"><?= $errors['nom'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email_ins" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <input type="email" id="email_ins" name="email" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                       placeholder="votre@email.com">
                <?php if (!empty($errors['email'])): ?>
                <p class="mt-1 text-xs text-red-500"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <!-- WhatsApp avec indicatif pays -->
            <div x-data="phoneSelector()">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Numéro WhatsApp</label>
                <div class="flex">
                    <div x-data="{ open: false }" class="relative flex-shrink-0">
                        <button type="button" @click="open = !open" class="flex items-center gap-1.5 px-3 py-3 border border-r-0 border-gray-200 bg-gray-50 text-sm hover:bg-gray-100 transition">
                            <span x-text="selectedFlag" class="text-lg">🇨🇮</span>
                            <span x-text="selectedCode" class="text-gray-700 font-medium">+225</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute top-full left-0 mt-1 w-64 bg-white border border-gray-200 shadow-lg z-50 max-h-60 overflow-y-auto">
                            <div class="p-2">
                                <input type="text" x-model="search" placeholder="Rechercher..." class="w-full px-3 py-1.5 border border-gray-200 text-sm outline-none focus:ring-1 focus:ring-violet-500">
                            </div>
                            <template x-for="c in filteredCountries" :key="c.code">
                                <button type="button" @click="selectCountry(c); open = false"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 hover:bg-violet-50 transition text-left text-sm">
                                    <span class="text-lg" x-text="c.flag"></span>
                                    <span class="text-gray-700" x-text="c.name"></span>
                                    <span class="text-gray-400 ml-auto" x-text="c.dial"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <input type="tel" name="whatsapp" x-model="number"
                           class="flex-1 px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="07 08 09 10">
                </div>
                <input type="hidden" name="whatsapp" :value="selectedCode + number">
            </div>

            <!-- Mot de passe avec toggle -->
            <div>
                <label for="mot_de_passe_ins" class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" id="mot_de_passe_ins" name="mot_de_passe" required
                           class="block w-full px-4 py-3 pr-12 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="Minimum 8 caractères">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                <?php if (!empty($errors['mot_de_passe'])): ?>
                <p class="mt-1 text-xs text-red-500"><?= $errors['mot_de_passe'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Confirmation mot de passe avec toggle -->
            <div>
                <label for="mot_de_passe_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required
                           class="block w-full px-4 py-3 pr-12 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="Retapez votre mot de passe">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                <?php if (!empty($errors['mot_de_passe_confirmation'])): ?>
                <p class="mt-1 text-xs text-red-500"><?= $errors['mot_de_passe_confirmation'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 transition duration-200">
                Créer mon compte
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="#" @click.prevent="activeTab = 'connexion'" class="text-sm text-violet-500 hover:text-violet-600 font-medium">
                Déjà un compte ? Se connecter
            </a>
        </div>
    </div>

    <!-- ===== FORMULAIRE MOT DE PASSE OUBLIÉ ===== -->
    <div x-show="activeTab === 'reset_request'" x-transition>
        <p class="text-gray-500 mb-6 text-sm text-center">Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>

        <form method="POST" action="/mot-de-passe-oublie" class="space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="email" name="email" required
                           class="block w-full pl-11 pr-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition placeholder-gray-400"
                           placeholder="votre@email.com">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 transition duration-200">
                Envoyer le lien
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="#" @click.prevent="activeTab = 'connexion'" class="text-sm text-violet-500 hover:text-violet-600 font-medium">
                Retour à la connexion
            </a>
        </div>
    </div>

    <!-- ===== EMAIL ENVOYÉ ===== -->
    <div x-show="activeTab === 'reset_sent'" x-transition>
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-violet-100 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="mail-check" class="w-8 h-8 text-violet-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Email envoyé</h3>
            <p class="text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                Si un compte existe avec cette adresse, vous recevrez un email avec un lien pour réinitialiser votre mot de passe.
            </p>
            <div class="mt-6">
                <a href="/connexion" class="text-sm text-violet-500 hover:text-violet-600 font-medium">
                    Retour à la connexion
                </a>
            </div>
        </div>
    </div>

    <!-- ===== FORMULAIRE NOUVEAU MOT DE PASSE ===== -->
    <div x-show="activeTab === 'reset_form'" x-transition>
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-violet-100 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="key-round" class="w-6 h-6 text-violet-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Nouveau mot de passe</h3>
            <p class="text-sm text-gray-500 mt-1">Choisissez un mot de passe sécurisé.</p>
        </div>

        <form method="POST" action="/reinitialiser-mot-de-passe/<?= htmlspecialchars($reset_token ?? '') ?>" class="space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nouveau mot de passe</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="mot_de_passe" required minlength="8"
                           class="block w-full pl-11 pr-12 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation" required minlength="8"
                       class="block w-full px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition"
                       placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full bg-violet-500 hover:bg-violet-600 text-white font-semibold py-3 px-4 transition duration-200">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>

    <!-- Lien dispatch pour le switch depuis connexion -->
    <div x-data @switch-to-register.window="activeTab = 'inscription'"></div>
</div>

<script>
function phoneSelector() {
    return {
        search: '',
        number: '',
        selectedCode: '+225',
        selectedFlag: '🇨🇮',
        countries: [
            { flag: '🇨🇮', name: 'Côte d\'Ivoire', dial: '+225', code: 'CI' },
            { flag: '🇫🇷', name: 'France', dial: '+33', code: 'FR' },
            { flag: '🇨🇲', name: 'Cameroun', dial: '+237', code: 'CM' },
            { flag: '🇸🇳', name: 'Sénégal', dial: '+221', code: 'SN' },
            { flag: '🇲🇱', name: 'Mali', dial: '+223', code: 'ML' },
            { flag: '🇧🇫', name: 'Burkina Faso', dial: '+226', code: 'BF' },
            { flag: '🇳🇪', name: 'Niger', dial: '+227', code: 'NE' },
            { flag: '🇬🇳', name: 'Guinée', dial: '+224', code: 'GN' },
            { flag: '🇨🇩', name: 'RD Congo', dial: '+243', code: 'CD' },
            { flag: '🇨🇬', name: 'Congo', dial: '+242', code: 'CG' },
            { flag: '🇬🇦', name: 'Gabon', dial: '+241', code: 'GA' },
            { flag: '🇹🇬', name: 'Togo', dial: '+228', code: 'TG' },
            { flag: '🇧🇯', name: 'Bénin', dial: '+229', code: 'BJ' },
            { flag: '🇲🇬', name: 'Madagascar', dial: '+261', code: 'MG' },
            { flag: '🇷🇼', name: 'Rwanda', dial: '+250', code: 'RW' },
            { flag: '🇧🇮', name: 'Burundi', dial: '+257', code: 'BI' },
            { flag: '🇹🇩', name: 'Tchad', dial: '+235', code: 'TD' },
            { flag: '🇨🇫', name: 'Centrafrique', dial: '+236', code: 'CF' },
            { flag: '🇲🇷', name: 'Mauritanie', dial: '+222', code: 'MR' },
            { flag: '🇩🇿', name: 'Algérie', dial: '+213', code: 'DZ' },
            { flag: '🇲🇦', name: 'Maroc', dial: '+212', code: 'MA' },
            { flag: '🇹🇳', name: 'Tunisie', dial: '+216', code: 'TN' },
            { flag: '🇪🇬', name: 'Égypte', dial: '+20', code: 'EG' },
            { flag: '🇳🇬', name: 'Nigeria', dial: '+234', code: 'NG' },
            { flag: '🇬🇭', name: 'Ghana', dial: '+233', code: 'GH' },
            { flag: '🇰🇪', name: 'Kenya', dial: '+254', code: 'KE' },
            { flag: '🇿🇦', name: 'Afrique du Sud', dial: '+27', code: 'ZA' },
            { flag: '🇪🇹', name: 'Éthiopie', dial: '+251', code: 'ET' },
            { flag: '🇺🇬', name: 'Ouganda', dial: '+256', code: 'UG' },
            { flag: '🇹🇿', name: 'Tanzanie', dial: '+255', code: 'TZ' },
            { flag: '🇧🇪', name: 'Belgique', dial: '+32', code: 'BE' },
            { flag: '🇨🇭', name: 'Suisse', dial: '+41', code: 'CH' },
            { flag: '🇨🇦', name: 'Canada', dial: '+1', code: 'CA' },
            { flag: '🇺🇸', name: 'États-Unis', dial: '+1', code: 'US' },
            { flag: '🇬🇧', name: 'Royaume-Uni', dial: '+44', code: 'GB' },
            { flag: '🇩🇪', name: 'Allemagne', dial: '+49', code: 'DE' },
            { flag: '🇪🇸', name: 'Espagne', dial: '+34', code: 'ES' },
            { flag: '🇮🇹', name: 'Italie', dial: '+39', code: 'IT' },
            { flag: '🇵🇹', name: 'Portugal', dial: '+351', code: 'PT' },
            { flag: '🇧🇷', name: 'Brésil', dial: '+55', code: 'BR' },
        ],
        get filteredCountries() {
            if (!this.search) return this.countries;
            const q = this.search.toLowerCase();
            return this.countries.filter(c => c.name.toLowerCase().includes(q) || c.dial.includes(q));
        },
        selectCountry(c) {
            this.selectedCode = c.dial;
            this.selectedFlag = c.flag;
            this.search = '';
        }
    }
}
</script>
