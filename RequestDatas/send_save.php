<?php
// Connexion à la base de données avec PDO
require_once __DIR__.'/../assets/assets-var.php';

try {
    // création d'une nouvelle connexion PDO à la base de données SQLite
    $pdo = new PDO('sqlite:'.$app['url_ip'].'includes/database/mouride.db');
    
    // activation des erreurs PDO pour pouvoir les attraper avec try...catch
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // en cas d'erreur de connexion, affichage du message d'erreur et arrêt du script
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}


/**------------------------------------------------------------
 *  ENREGISTREMENT DES OEUVRES
 ------------------------------------------------------------*/

// Requête SQL pour récupérer les données de la table "qurans"
$sql = "SELECT id, title, introduction, development, conclusion, category, user_id, approval FROM songs";

$quran_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$quran_smtp->bindValue(':category', 'quran');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$quran_smtp->execute();
$qurans = $quran_smtp->fetchAll(PDO::FETCH_ASSOC);

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($qurans as $quran) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $quran['id'] . '_' . $quran['title'] . '.json';
    
    // Encodage des données de la quran en JSON
    $donnees_quran = [
        'id' => $quran['id'],
        'title' => $quran['title'],
        'introduction' => $quran['introduction'],
        'development' => $quran['development'],
        'conclusion' => $quran['conclusion'],
        'category' => $quran['category'],
        'user_id' => $quran['user_id'],
        'approval' => $quran['approval']
    ];
    $json_quran = json_encode($donnees_quran, JSON_PRETTY_PRINT);
    
    // Écriture des données de la quran dans le fichier JSON
    file_put_contents(__DIR__."/files/1_Quran/".$nom_fichier, $json_quran);
}

$madh_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$madh_smtp->bindValue(':category', 'madh');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$madh_smtp->execute();
$madhs = $madh_smtp->fetchAll(PDO::FETCH_ASSOC);

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($madhs as $madh) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $madh['id'] . '_' . $madh['title'] . '.json';
    
    // Encodage des données de la madh en JSON
    $donnees_madh = [
        'id' => $madh['id'],
        'title' => $madh['title'],
        'introduction' => $madh['introduction'],
        'development' => $madh['development'],
        'conclusion' => $madh['conclusion'],
        'category' => $madh['category'],
        'user_id' => $madh['user_id'],
        'approval' => $madh['approval']
    ];
    $json_madh = json_encode($donnees_madh, JSON_PRETTY_PRINT);
    
    // Écriture des données de la madh dans le fichier JSON
    file_put_contents(__DIR__."/files/2_Madh/".$nom_fichier, $json_madh);
}

$salat_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$salat_smtp->bindValue(':category', 'salat');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$salat_smtp->execute();
$salats = $salat_smtp->fetchAll(PDO::FETCH_ASSOC);
// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($salats as $salat) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $salat['id'] . '_' . $salat['title'] . '.json';
    
    // Encodage des données de la salat en JSON
    $donnees_salat = [
        'id' => $salat['id'],
        'title' => $salat['title'],
        'introduction' => $salat['introduction'],
        'development' => $salat['development'],
        'conclusion' => $salat['conclusion'],
        'category' => $salat['category'],
        'user_id' => $salat['user_id'],
        'approval' => $salat['approval']
    ];
    $json_salat = json_encode($donnees_salat, JSON_PRETTY_PRINT);
    
    // Écriture des données de la salat dans le fichier JSON
    file_put_contents(__DIR__."/files/3_Salat/".$nom_fichier, $json_salat);
}

$zikr_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$zikr_smtp->bindValue(':category', 'zikr');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$zikr_smtp->execute();
$zikrs = $zikr_smtp->fetchAll(PDO::FETCH_ASSOC);

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($zikrs as $zikr) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $zikr['id'] . '_' . $zikr['title'] . '.json';
    
    // Encodage des données de la zikr en JSON
    $donnees_zikr = [
        'id' => $zikr['id'],
        'title' => $zikr['title'],
        'introduction' => $zikr['introduction'],
        'development' => $zikr['development'],
        'conclusion' => $zikr['conclusion'],
        'category' => $zikr['category'],
        'user_id' => $zikr['user_id'],
        'approval' => $zikr['approval']
    ];
    $json_zikr = json_encode($donnees_zikr, JSON_PRETTY_PRINT);
    
    // Écriture des données de la zikr dans le fichier JSON
    file_put_contents(__DIR__."/files/4_Zikr/".$nom_fichier, $json_zikr);
}

$hilm_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$hilm_smtp->bindValue(':category', 'hilm');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$hilm_smtp->execute();
$hilms = $hilm_smtp->fetchAll(PDO::FETCH_ASSOC);

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($hilms as $hilm) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $hilm['id'] . '_' . $hilm['title'] . '.json';
    
    // Encodage des données de la hilm en JSON
    $donnees_hilm = [
        'id' => $hilm['id'],
        'title' => $hilm['title'],
        'introduction' => $hilm['introduction'],
        'development' => $hilm['development'],
        'conclusion' => $hilm['conclusion'],
        'category' => $hilm['category'],
        'user_id' => $hilm['user_id'],
        'approval' => $hilm['approval']
    ];
    $json_hilm = json_encode($donnees_hilm, JSON_PRETTY_PRINT);
    
    // Écriture des données de la hilm dans le fichier JSON
    file_put_contents(__DIR__."/files/5_Hilm/".$nom_fichier, $json_hilm);
}

$wasiya_smtp = $pdo->prepare($sql. ' WHERE category = :category');
$wasiya_smtp->bindValue(':category', 'wasiya');
// Exécution de la requête et récupération des résultats sous forme d'un tableau associatif
$wasiya_smtp->execute();
$wasiyas = $wasiya_smtp->fetchAll(PDO::FETCH_ASSOC);

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($wasiyas as $wasiya) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = $wasiya['id'] . '_' . $wasiya['title'] . '.json';
    
    // Encodage des données de la wasiya en JSON
    $donnees_wasiya = [
        'id' => $wasiya['id'],
        'title' => $wasiya['title'],
        'introduction' => $wasiya['introduction'],
        'development' => $wasiya['development'],
        'conclusion' => $wasiya['conclusion'],
        'category' => $wasiya['category'],
        'user_id' => $wasiya['user_id'],
        'approval' => $wasiya['approval']
    ];
    $json_wasiya = json_encode($donnees_wasiya, JSON_PRETTY_PRINT);
    
    // Écriture des données de la wasiya dans le fichier JSON
    file_put_contents(__DIR__."/files/6_Wasiya/".$nom_fichier, $json_wasiya);
}


echo "Les données des oeuvres ont été sauvegardées avec succès";
?>


<?php 

// Requête SQL pour récupérer les données de la table
$sql = "SELECT id, name, email, password, country, region, phone, profile_picture, state, inscription_date, activation_code, is_super_admin, is_hight_admin, is_admin FROM users";

// Exécution de la requête et récupération du résultat sous forme d'un tableau associatif
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Vérification que l'utilisateur existe
if (!$users) {
    exit("Aucun enrtegistrement dans la table users.");
}

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($users as $user) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = 'user_' . $user['id'] . '.json';
    
    // Encodage des données de la wasiya en JSON
    $user_data = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'password' => $user['password'],
        'country' => $user['country'],
        'region' => $user['region'],
        'phone' => $user['phone'],
        'profile_picture' => $user['profile_picture'],
        'state' => $user['state'],
        'inscription_date' => $user['inscription_date'],
        'activation_code' => $user['activation_code'],
        'is_super_admin' => $user['is_super_admin'],
        'is_hight_admin' => $user['is_hight_admin'],
        'is_admin' => $user['is_admin']
    ];
    $json_user = json_encode($user_data, JSON_PRETTY_PRINT);
    
    // Écriture des données de la wasiya dans le fichier JSON
    file_put_contents(__DIR__."/users/".$nom_fichier, $json_user);
}


echo "Les données des utilisateurs ont été enregistrées dans le répertoire " . __DIR__."/users/" ;
?>

<?php 
/**------------------------------------------------------------
 *  ENREGISTREMENT DES APPROBATIONS
 ------------------------------------------------------------*/

// Requête SQL pour récupérer les données de la table
$sql = "SELECT user_id, song_id, is_approved, is_disapproved FROM approval";

// Exécution de la requête et récupération du résultat sous forme d'un tableau associatif
$aprvs = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Vérification la tables contient des enregistrements
if (!$aprvs) {
    exit("Aucun enrtegistrement dans la table approval.");
}

// Parcours des résultats et enregistrement dans des fichiers JSON
foreach ($aprvs as $aprv) {
    // Construction du nom de fichier au format "id_titre.json"
    $nom_fichier = 'supervisor_' . $aprv['user_id'] . '-' . $aprv['song_id'] . '.json';
    
    // Encodage des données de la wasiya en JSON
   // Encodage des données de la wasiya en JSON
   $aprv_data = [
    'user_id' => $aprv['user_id'],
    'song_id' => $aprv['song_id'],
    'is_approved' => $aprv['is_approved'],
    'is_disapproved' => $aprv['is_disapproved']
];
$json_aprv = json_encode($aprv_data, JSON_PRETTY_PRINT);

// Écriture des données de la wasiya dans le fichier JSON
file_put_contents(__DIR__."/approval/".$nom_fichier, $json_aprv);
}

echo "Les données d'approbation des oeuvres ont été enregistrées dans le répertoire " . __DIR__."/approval/" ;
?>

