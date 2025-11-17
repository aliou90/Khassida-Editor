<?php
/**------------------------------------------------------------
 *  RESTAURATION DES OEUVRES
 ------------------------------------------------------------*/
// Connexion à la base de données avec PDO
require_once __DIR__.'/../includes/config.php';

// Requête SQL pour insérer les données des fichiers JSON dans la table "chansons"
$sql = "INSERT INTO songs (id, title, content, category, user_id, approval) VALUES (:id, :title, :content, :category, :user_id,:approval)";

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/1_Quran/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/2_Madh/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/3_Salat/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/4_Zikr/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/5_Hilm/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}

// Parcours des fichiers JSON dans le dossier "files"
foreach (glob(__DIR__."/files/6_Wasiya/*.json") as $fichier) {
    // Récupération du contenu du fichier et décodage du JSON
    $json_chanson = file_get_contents($fichier);
    $donnees_chanson = json_decode($json_chanson, true);
    
    // Exécution de la requête d'insertion avec les données du fichier JSON
    $requete = $pdo->prepare($sql);
    $requete->bindValue(':id', $donnees_chanson['id']);
    $requete->bindValue(':title', $donnees_chanson['title']);
    $requete->bindValue(':content', $donnees_chanson['content']);
    $requete->bindValue(':category', $donnees_chanson['category']);
    $requete->bindValue(':user_id', $donnees_chanson['user_id']);
    $requete->bindValue(':approval', $donnees_chanson['approval']);
    $requete->execute();
}


echo "Les données des oeuvres ont été restaurées avec succès";
?>

<?php
/**------------------------------------------------------------
 *  RESTAURATIONS DES DONNÉES PERSONNELLES UTILISATEURS
 ------------------------------------------------------------*/
// Connexion à la base de données avec PDO
require_once __DIR__.'/../includes/config.php';

// Construction de la requête SQL pour insérer les données des utilisateurs dans la base de données
$sql = "INSERT INTO users (id, name, email, password, country, region, phone, profile_picture, state, inscription_date, activation_code, is_super_admin, is_hight_admin, is_admin)
        VALUES (:id, :name, :email, :password, :country, :region, :phone, :profile_picture, :state, :inscription_date, :activation_code, :is_super_admin, :is_hight_admin, :is_admin)";

foreach (glob(__DIR__."/users/*.json") as $fichier) {
    // Lecture du fichier JSON des données de l'utilisateur 1
    $json_data = file_get_contents($fichier);
    $user_data = json_decode($json_data, true);

    // Exécution de la requête d'insertion avec les données du fichier JSON
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_data['id']);
    $stmt->bindParam(':name', $user_data['name']);
    $stmt->bindParam(':email', $user_data['email']);
    $stmt->bindParam(':password', $user_data['password']);
    $stmt->bindParam(':country', $user_data['country']);
    $stmt->bindParam(':region', $user_data['region']);
    $stmt->bindParam(':phone', $user_data['phone']);
    $stmt->bindParam(':profile_picture', $user_data['profile_picture']);
    $stmt->bindParam(':state', $user_data['state']);
    $stmt->bindParam(':inscription_date', $user_data['inscription_date']);
    $stmt->bindParam(':activation_code', $user_data['activation_code']);
    $stmt->bindParam(':is_super_admin', $user_data['is_super_admin']);
    $stmt->bindParam(':is_hight_admin', $user_data['is_hight_admin']);
    $stmt->bindParam(':is_admin', $user_data['is_admin']);
    $stmt->execute();
}

echo "Les données des utilisateurs ont été restaurées." ;
?>

<?php
/**------------------------------------------------------------
 *  RESTAURATIONS DES APPROBATIONS
 ------------------------------------------------------------*/
// Connexion à la base de données avec PDO
require_once __DIR__.'/../includes/config.php';


// Construction de la requête SQL pour insérer les données des utilisateurs dans la base de données
$sql = "INSERT INTO approval (user_id, song_id, is_approved, is_disapproved)
        VALUES (:user_id, :song_id, :is_approved, :is_disapproved)";

foreach (glob(__DIR__."/approval/*.json") as $fichier) {
    // Lecture du fichier JSON des données de l'utilisateur 1
    $json_data = file_get_contents($fichier);
    $aprv_data = json_decode($json_data, true);

    // Exécution de la requête d'insertion avec les données du fichier JSON
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $aprv_data['user_id']);
    $stmt->bindParam(':song_id', $aprv_data['song_id']);
    $stmt->bindParam(':is_approved', $aprv_data['is_approved']);
    $stmt->bindParam(':is_disapproved', $aprv_data['is_disapproved']);
    $stmt->execute();
}

echo "Les données d'approbation ont été restaurées." . PHP_EOL ;
?>
