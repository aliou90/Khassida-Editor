<?php
// Connexion à la base de données MySQL avec PDO
require_once('mysql-config.php');
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Assurez-vous que la connexion utilise utf8mb4
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

// création de la table user
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    country TEXT,
    region TEXT,
    phone TEXT,
    profile_picture TEXT,
    state BOOLEAN NOT NULL DEFAULT 0,
    inscription_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    activation_code TEXT,
    is_super_admin BOOLEAN NOT NULL DEFAULT 0,
    is_hight_admin BOOLEAN NOT NULL DEFAULT 0,
    is_admin BOOLEAN NOT NULL DEFAULT 0
)";
// exécution de la requête SQL
$pdo->exec($sql);

// Requête SQL pour la création de la table "chansons"
$sql = "CREATE TABLE IF NOT EXISTS songs (   
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category ENUM('zikr', 'salat', 'madh', 'hilm', 'wasiya', 'quran') NOT NULL,
    add_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT(11) REFERENCES users(id) ON DELETE NO ACTION,
    approval INT(11) DEFAULT 0,
    PRIMARY KEY (id)
)";
// Exécution de la requête SQL
$pdo->exec($sql);

// Définir la requête SQL pour créer la table "approval"
$sql = "CREATE TABLE IF NOT EXISTS approval (
    user_id INT(11) NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    song_id INT(11) REFERENCES songs(id) ON DELETE CASCADE,
    is_approved BOOLEAN NOT NULL DEFAULT 0,
    is_disapproved BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (user_id, song_id)
)";
$pdo->exec($sql);

// Définir la requête SQL pour créer la table "selection"
$sql = "CREATE TABLE IF NOT EXISTS selection (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    song_id INT(11) NOT NULL REFERENCES songs(id) ON DELETE CASCADE,
    summary INT(11)
)";
$pdo->exec($sql);

// Création de la table "newsletter"
$pdo->exec("CREATE TABLE IF NOT EXISTS newsletter (
    email VARCHAR(255) PRIMARY KEY,
    join_date DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Création de la table "transactions"
$sql = "CREATE TABLE IF NOT EXISTS transactions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)"; 
$pdo->exec($sql);


echo "Base de données créée avec succès" . PHP_EOL;

/*
// Ajouter l'utilisateur super administrateur
$name = 'Super Administrateur';
$email = 'super.admin@mouride.com';
$password = '123xx';
$is_super_admin = 1;
$is_hight_admin = 1;
$is_admin = 1;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (id, name, email, password, is_super_admin, is_hight_admin, is_admin, state) VALUES (1, '$name', '$email', '$password_hash', $is_super_admin, $is_hight_admin, $is_admin, $state)";
$pdo->exec($sql);

// Ajouter un utilisateur correcteur (Superviseur)
$name = 'Correcteur (Superviseur)';
$email = 'hight.admin@mouride.com';
$password = '123xy';
$is_super_admin = 0;    
$is_hight_admin = 1;
$is_admin = 1;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (id, name, email, password, is_admin, is_hight_admin, is_super_admin, state) VALUES (2, '$name', '$email', '$password_hash', $is_admin, $is_hight_admin, $is_super_admin, $state)";
$pdo->exec($sql);

// Ajouter un utilisateur éditeur
$name = 'Éditeur (Écrivain)';
$email = 'admin@mouride.com';
$password = '123x';
$is_super_admin = 0;
$is_hight_admin = 0;
$is_admin = 1;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (id, name, email, password,is_super_admin, is_hight_admin, is_admin, state) VALUES (3, '$name', '$email', '$password_hash', $is_super_admin, $is_hight_admin, $is_admin, $state)";
$pdo->exec($sql);

// Ajouter un utilisateur standard (Lecteur)
$name = 'Utilisateur';
$email = 'user@mouride.com';
$password = '123y';
$is_super_admin = 0;
$is_hight_admin = 0;
$is_admin = 0;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (id, name, email, password, is_super_admin, is_hight_admin, is_admin, state) VALUES (4, '$name', '$email', '$password_hash',$is_super_admin, $is_hight_admin, $is_admin, $state)";
$pdo->exec($sql);


echo "Utilisateurs insérés avec succès";
*/

// Fermeture de la connexion à la base de données MySQL
$pdo = null;
?>
