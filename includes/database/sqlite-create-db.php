<?php
// Connexion à la base de données SQLite
try {
    $dbFile = __DIR__ . '/mouride.db';

    // Vérifiez si le fichier de base de données existe et supprimez-le
    if (file_exists($dbFile)) {
        unlink($dbFile);
    }
    $pdo = new PDO('sqlite:'.__DIR__.'/mouride.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Assurez-vous que le mode de codage est UTF-8
    $pdo->exec("PRAGMA encoding = 'UTF-8';");
    
        // Création de la table "users"
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            email TEXT,
            password TEXT,
            country TEXT,
            region TEXT,
            phone TEXT,
            profile_picture TEXT,
            state INT,
            inscription_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            activation_code TEXT,
            is_super_admin INTEGER,
            is_hight_admin INTEGER,
            is_admin INTEGER)");

    // Création de la table "chansons"
    $pdo->exec("CREATE TABLE IF NOT EXISTS songs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        content TEXT,
        category TEXT,
        add_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        approval INTEGER,
        user_id INTEGER REFERENCES users(id)
        )");

    // Définir la requête SQL pour créer la table "approval"
    $pdo->exec("CREATE TABLE IF NOT EXISTS approval (
        user_id INTEGER NOT NULL REFERENCES users(id),
        song_id INTEGER NOT NULL REFERENCES songs(id),
        is_approved INTEGER,
        is_disapproved INTEGER,
        PRIMARY KEY (user_id, song_id)
    )");

    // Définir la requête SQL pour créer la table "selection"
    $pdo->exec("CREATE TABLE IF NOT EXISTS selection (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id),
        song_id INTEGER NOT NULL REFERENCES songs(id),
        summary INTEGER
    )");

    // Création de la table "newsletter"
    $pdo->exec("CREATE TABLE IF NOT EXISTS newsletter (
        email TEXT PRIMARY KEY,
        join_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Création de la table "transactions"
    $pdo->exec("CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT,
        amount REAL,
        date DATETIME DEFAULT CURRENT_TIMESTAMP
    )");


    echo "Base de données créée avec succès".PHP_EOL;

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

} catch (/*PDOException $e */ Throwable $th) {
    //echo "Erreur lors de la création de la base de données : " . $e->getMessage();
    echo $th;
}
?>
