<?php
$slug = htmlspecialchars($communaute['slug']);
$estAdmin = in_array($_SESSION['communaute_courante']['role'] ?? '', ['proprietaire', 'administrateur']);

// Mois actuel
$mois = (int)($_GET['mois'] ?? date('m'));
$annee = (int)($_GET['annee'] ?? date('Y'));
$jourActuel = date('j');
$moisActuel = date('m');
$anneeActuelle = date('Y');

$premierJour = mktime(0, 0, 0, $mois, 1, $annee);
$nbJours = date('t', $premierJour);
$jourSemaine = date('w', $premierJour); // 0=Dim, 1=Lun, ...

$moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
$joursNoms = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

// Mois précédent/suivant
$prevMois = $mois - 1;
$prevAnnee = $annee;
if ($prevMois < 1) { $prevMois = 12; $prevAnnee--; }
$nextMois = $mois + 1;
$nextAnnee = $annee;
if ($nextMois > 12) { $nextMois = 1; $nextAnnee++; }

// Organiser les événements par jour
$evenementsParJour = [];
foreach ($evenements as $evt) {
    $jourEvt = (int)date('j', strtotime($evt['date_debut']));
    $moisEvt = (int)date('m', strtotime($evt['date_debut']));
    $anneeEvt = (int)date('Y', strtotime($evt['date_debut']));
    if ($moisEvt === $mois && $anneeEvt === $annee) {
        $evenementsParJour[$jourEvt][] = $evt;
    }
}
?>

<div class="max-w-6xl mx-auto" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Calendrier</h1>
        </div>
        <?php if ($estAdmin): ?>
        <button @click="showForm = !showForm" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-5 py-2 text-sm transition">
            <span x-show="!showForm">+ Nouvel événement</span>
            <span x-show="showForm">Annuler</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Formulaire création -->
    <?php if ($estAdmin): ?>
    <div x-show="showForm" x-cloak x-transition class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 mb-4">Créer un événement</h2>
        <form method="POST" action="/c/<?= $slug ?>/evenements" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="titre" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                        <option value="webinaire">Webinaire</option>
                        <option value="meetup">Meetup</option>
                        <option value="atelier">Atelier</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none resize-none"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
                    <input type="datetime-local" name="date_debut" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="datetime-local" name="date_fin" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien (optionnel)</label>
                <input type="url" name="lien" placeholder="https://..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
            </div>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 text-white font-semibold px-6 py-2 text-sm transition">Créer</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Calendar Navigation -->
    <div class="bg-white border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="/c/<?= $slug ?>/calendrier?mois=<?= $prevMois ?>&annee=<?= $prevAnnee ?>" class="flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 transition">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-900"><?= $moisNoms[$mois - 1] ?> <?= $annee ?></h2>
            </div>
            <a href="/c/<?= $slug ?>/calendrier?mois=<?= $nextMois ?>&annee=<?= $nextAnnee ?>" class="flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 transition">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Day headers -->
        <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200">
            <?php foreach ($joursNoms as $jour): ?>
            <div class="bg-gray-50 py-2 text-center text-xs font-semibold text-gray-500 uppercase"><?= $jour ?></div>
            <?php endforeach; ?>

            <?php
            // Jours vides avant le premier jour (Lundi = 0)
            $offset = ($jourSemaine === 0) ? 6 : $jourSemaine - 1;
            for ($i = 0; $i < $offset; $i++):
            ?>
            <div class="bg-white h-28 border-b border-r border-gray-100 p-1"></div>
            <?php endfor; ?>

            <?php for ($jour = 1; $jour <= $nbJours; $jour++):
                $isToday = ($jour === $jourActuel && $mois === $moisActuel && $annee === $anneeActuelle);
                $hasEvents = !empty($evenementsParJour[$jour]);
            ?>
            <div class="bg-white h-28 border-b border-r border-gray-100 p-1.5 <?= $isToday ? 'bg-violet-50' : '' ?> relative">
                <span class="text-sm font-medium <?= $isToday ? 'text-white' : 'text-gray-700' ?> <?= $isToday ? 'inline-flex items-center justify-center w-6 h-6' : '' ?>"
                      style="<?= $isToday ? 'background: var(--comm-color);' : '' ?>"><?= $jour ?></span>
                <?php if ($hasEvents): ?>
                <div class="mt-1 space-y-0.5">
                    <?php foreach (array_slice($evenementsParJour[$jour], 0, 2) as $evt): ?>
                    <div class="text-[10px] px-1 py-0.5 truncate font-medium text-white" style="background: var(--comm-color);">
                        <?= htmlspecialchars(mb_strimwidth($evt['titre'], 0, 20, '...')) ?>
                    </div>
                    <?php endforeach; ?>
                    <?php if (count($evenementsParJour[$jour]) > 2): ?>
                    <div class="text-[10px] text-gray-400 px-1">+<?= count($evenementsParJour[$jour]) - 2 ?> de plus</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endfor; ?>

            <?php
            // Jours vides après le dernier jour
            $totalCells = $offset + $nbJours;
            $remaining = (7 - ($totalCells % 7)) % 7;
            for ($i = 0; $i < $remaining; $i++):
            ?>
            <div class="bg-white h-28 border-b border-r border-gray-100 p-1"></div>
            <?php endfor; ?>
        </div>
    </div>
</div>
