<?php
/**
 * Script pour definir ou redefinir le mot de passe du super administrateur.
 * 
 * Usage: php database/scripts/set_admin_password.php
 * 
 * Le script demande l'email et le nouveau mot de passe.
 */

// Charger la config DB directement
$dbConfig = require __DIR__ . '/../../config/database.php';

echo "=== Configuration du mot de passe administrateur ===\n\n";

// Demander l'email
$email = readline("Email de l'admin (defaut: admin@cado.me): ");
$email = trim($email) ?: 'admin@cado.me';

// Demander le mot de passe
$password = readline("Nouveau mot de passe (min. 8 caracteres): ");
if (strlen($password) < 8) {
    echo "Erreur: Le mot de passe doit contenir au moins 8 caracteres.\n";
    exit(1);
}

// Confirmer
$confirm = readline("Confirmer le mot de passe: ");
if ($password !== $confirm) {
    echo "Erreur: Les mots de passe ne correspondent pas.\n";
    exit(1);
}

try {
    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $db = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // Verifier si l'utilisateur existe
    $stmt = $db->prepare('SELECT id, role_plateforme FROM utilisateurs WHERE email = :email');
    $stmt->execute(['email' => strtolower(trim($email))]);
    $user = $stmt->fetch();
    
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    if (!$user) {
        echo "Utilisateur non trouve avec l'email: {$email}\n";
        echo "Voulez-vous le creer comme super administrateur ? (o/n): ";
        $create = readline();
        
        if (strtolower(trim($create)) !== 'o') {
            echo "Abandon.\n";
            exit(0);
        }
        
        // Creer l'utilisateur
        $stmt = $db->prepare(
            'INSERT INTO utilisateurs (prenom, nom, identifiant, email, mot_de_passe, role_plateforme, statut, email_verifie, date_creation, date_modification)
             VALUES (:prenom, :nom, :identifiant, :email, :mot_de_passe, :role, :statut, 1, NOW(), NOW())'
        );
        $stmt->execute([
            'prenom' => 'Admin',
            'nom' => 'Cado.me',
            'identifiant' => 'admin',
            'email' => strtolower(trim($email)),
            'mot_de_passe' => $hash,
            'role' => 'super_administrateur',
            'statut' => 'actif',
        ]);
        
        echo "\nSuper administrateur cree avec succes !\n";
        echo "Email: {$email}\n";
        echo "Role: super_administrateur\n";
    } else {
        // Mettre a jour le mot de passe
        $stmt = $db->prepare('UPDATE utilisateurs SET mot_de_passe = :mot_de_passe, date_modification = NOW() WHERE email = :email');
        $stmt->execute([
            'mot_de_passe' => $hash,
            'email' => strtolower(trim($email)),
        ]);
        
        echo "\nMot de passe mis a jour avec succes !\n";
        echo "Email: {$email}\n";
        echo "Role: {$user['role_plateforme']}\n";
    }
    
    echo "\nVous pouvez maintenant vous connecter sur /admin/connexion\n";
    
} catch (PDOException $e) {
    echo "Erreur de connexion a la base de donnees: " . $e->getMessage() . "\n";
    echo "Verifiez que le serveur MySQL est demarre et que config/database.php est correct.\n";
    exit(1);
}
