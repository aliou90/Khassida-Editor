<?php
try {
    // création d'une nouvelle connexion PDO à la base de données SQLite
    $pdo = new PDO('sqlite:'.__DIR__.'/mouride.db');
    
    // activation des erreurs PDO pour pouvoir les attraper avec try...catch
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // en cas d'erreur de connexion, affichage du message d'erreur et arrêt du script
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
