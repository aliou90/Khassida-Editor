<?php
// Informations de l'application
define('APP_NAME', 'Karamati');
define('APP_VERSION', '1.0');

// Informations de connexion à la base de données MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'mouride');
define('DB_USER', 'root');
define('DB_PASSWORD', '');


// Connexion à la base de données MySQL avec PDO
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Assurez-vous que la connexion utilise utf8mb4
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
