<?php
/**
 * Cado.me — Script d'installation / migration
 * Accessible depuis le navigateur : http://localhost/Cado.me/public/install.php
 * À SUPPRIMER en production !
 */

$messages = [];
$erreur = false;

try {
    // Charger la config
    require_once __DIR__ . '/../vendor/autoload.php';
    \App\Core\Config::load();
    \App\Core\Session::start();

    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $dbname = $_ENV['DB_DATABASE'] ?? 'cado_me';
    $username = $_ENV['DB_USERNAME'] ?? 'root';
    $password = $_ENV['DB_PASSWORD'] ?? '';

    $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Créer la base si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbname}`");
    $messages[] = "✅ Base de données '{$dbname}' prête.";

    // Lister les fichiers de migration
    $migrationsDir = __DIR__ . '/../database/migrations';
    $sqlFiles = glob($migrationsDir . '/*.sql');
    sort($sqlFiles);

    if (empty($sqlFiles)) {
        $messages[] = "⚠️ Aucun fichier de migration trouvé dans database/migrations/";
    }

    foreach ($sqlFiles as $file) {
        $basename = basename($file);
        $sql = file_get_contents($file);

        if ($sql === false) {
            $messages[] = "⚠️ Impossible de lire {$basename}";
            continue;
        }

        // Exécuter le SQL ligne par ligne pour mieux gérer les erreurs
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        $ok = 0;
        $skip = 0;
        $fail = 0;

        foreach ($statements as $statement) {
            // Ignorer les commentaires vides et lignes vides
            if ($statement === '' || str_starts_with($statement, '--')) {
                continue;
            }

            try {
                $pdo->exec($statement);
                $ok++;
            } catch (PDOException $e) {
                $code = $e->getCode();
                // Ignorer les erreurs "already exists" (1050, 1060, 1061)
                if (in_array($code, ['42S01', '1050', '1060', '1061', '1091'])) {
                    $skip++;
                } else {
                    $fail++;
                    $shortMsg = $e->getMessage();
                    if (strlen($shortMsg) > 120) {
                        $shortMsg = substr($shortMsg, 0, 120) . '...';
                    }
                    $messages[] = "⚠️ [{$basename}] {$shortMsg}";
                }
            }
        }

        $detail = "{$ok} exécuté(s)";
        if ($skip > 0) $detail .= ", {$skip} ignoré(s) (déjà existant)";
        if ($fail > 0) $detail .= ", {$fail} échoué(s)";
        $messages[] = "📦 {$basename} — {$detail}";
    }

    $messages[] = "";
    $messages[] = "🎉 Installation terminée ! Vous pouvez accéder à <a href='/' style='color:#7830E0;font-weight:600;'>l'application</a>.";
    $messages[] = "<small style='color:#999;'>⚠️ Supprimez ce fichier (install.php) en production.</small>";

} catch (Exception $e) {
    $erreur = true;
    $messages[] = "❌ Erreur : " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cado.me — Installation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; border-radius: 0 !important; }
        body { font-family: 'Sora', sans-serif; background: #f5f3ff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: white; max-width: 640px; width: 100%; padding: 2.5rem; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111; margin-bottom: 0.5rem; }
        .subtitle { color: #6b7280; font-size: 0.875rem; margin-bottom: 2rem; }
        .messages { list-style: none; }
        .messages li { padding: 0.6rem 0; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem; line-height: 1.5; color: #374151; }
        .messages li:last-child { border-bottom: none; }
        .messages a { text-decoration: none; }
        .error-banner { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Cado.me — Installation</h1>
        <p class="subtitle">Migration automatique de la base de données</p>

        <?php if ($erreur): ?>
        <div class="error-banner"><?= end($messages) ?></div>
        <?php endif; ?>

        <ul class="messages">
            <?php foreach ($messages as $msg): ?>
            <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
