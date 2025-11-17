<?php
//require_once('mysql-config.php');

// Tableau de données à insérer
$chansons = array(
    array(
        'title' => 'Zikr Allah',
        'introduction' => 'Louons Allah, le Tout-Puissant',
        'development' => 'Qui a créé le ciel et la terre',
        'conclusion' => 'Louange à Allah, Seigneur des mondes',
        'category' => 'zikr'
    ),
    array(
        'title' => 'Salatou Salam',
        'introduction' => 'Que la prière et la paix soient sur notre Prophète',
        'development' => 'Mohammed, le Messager de Dieu',
        'conclusion' => 'Que la paix soit sur lui, ainsi que la miséricorde d\'Allah et Ses bénédictions',
        'category' => 'salawat'
    ),
    array(
        'title' => 'Madh Khadim Rassoul',
        'introduction' => 'Louange à Allah, le Créateur de toutes choses',
        'development' => 'Louange à Allah pour avoir envoyé son prophète',
        'conclusion' => 'Que la paix et la bénédiction d\'Allah soient sur lui',
        'category' => 'madh'
    ),
    array(
        'title' => 'Hilm Khadimou Rassoul',
        'introduction' => 'Louange à Allah, Seigneur des mondes',
        'development' => 'Nous louons notre Prophète qui a apporté la guidance',
        'conclusion' => 'Que la paix et la bénédiction d\'Allah soient sur lui',
        'category' => 'hilm'
    ),
    array(
        'title' => 'Wasaya Serigne Touba',
        'introduction' => 'Que la paix et la bénédiction d\'Allah soient sur notre Prophète',
        'development' => 'Nous recevons les conseils de notre maître',
        'conclusion' => 'Nous sommes à votre service, ô Touba',
        'category' => 'wasaya'
    )
);

// Préparer la requête d'insertion
$stmt = $pdo->prepare("INSERT INTO songs (title, introduction, development, conclusion, category) VALUES (:title, :introduction, :development, :conclusion, :category)");

// Parcourir le tableau de données et exécuter la requête pour chaque élément
foreach ($chansons as $chanson) {
    $stmt->execute($chanson);
}

$pdo = $pdo;

// Ajouter l'utilisateur administrateur
$email = 'admin@exemple.com';
$password = '123x';
$is_admin = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (email, password, is_admin) VALUES ('$email', '$password_hash', $is_admin)";
$pdo->exec($sql);

// Ajouter un utilisateur standard
$email = 'user@exemple.com';
$password = '123y';
$is_admin = 0;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (email, password, is_admin) VALUES ('$email', '$password_hash', $is_admin)";
$pdo->exec($sql);


echo "Les données ont été insérées avec succès !";
?>
