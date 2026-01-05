<?php
// Script CLI pour créer ou mettre à jour un compte admin
require_once __DIR__ . '/../includes/db.php';

if (php_sapi_name() !== 'cli') {
    die("Ce script doit être lancé en CLI.\nUsage: php create_admin.php <username> <password>\n");
}

if ($argc < 3) {
    die("Usage: php create_admin.php <username> <password>\n");
}

$username = $argv[1];
$password = $argv[2];

if (strlen($username) < 3 || strlen($password) < 6) {
    die("Nom d'utilisateur trop court (>=3) ou mot de passe trop court (>=6).\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:u, :p) ON CONFLICT (username) DO UPDATE SET password_hash = EXCLUDED.password_hash');
$stmt->execute([':u' => $username, ':p' => $hash]);

echo "Admin '$username' créé/mis à jour avec succès.\n";
