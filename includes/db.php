<?php
// Configuration via variables d'environnement pour faciliter le déploiement
$driver = getenv('DB_DRIVER') ?: 'pgsql'; // 'pgsql' ou 'mysql'
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: ($driver === 'pgsql' ? '5432' : '3306');
$dbname = getenv('DB_NAME') ?: 'boutique_db';
$user = getenv('DB_USER') ?: ($driver === 'pgsql' ? 'postgres' : 'root');
$pass = getenv('DB_PASS') ?: '';

try {
    if ($driver === 'pgsql') {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    } else {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    // Forcer l'encodage UTF8 pour PostgreSQL
    if ($driver === 'pgsql') {
        $pdo->exec("SET NAMES 'UTF8'");
    }
} catch (PDOException $e) {
    // Afficher un message générique en production; garder le détail en développement
    $msg = getenv('APP_ENV') === 'production' ? 'Erreur de connexion à la base de données.' : $e->getMessage();
    die($msg);
}