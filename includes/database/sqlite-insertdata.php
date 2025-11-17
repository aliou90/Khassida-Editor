<?php
// Connexion à la base de données
require_once __DIR__.'/sqlite-config.php';
$db = $pdo;

// Ajouter l'utilisateur administrateur
$email = 'superadmin@gmail.com';
$password = '123x';
$is_admin = 1;
$is_super_admin = 1;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (email, password, is_admin, is_super_admin, state) VALUES ('$email', '$password_hash', $is_admin, $is_super_admin, $state)";
$pdo->exec($sql);

$email = 'admin@gmail.com';
$password = '123x';
$is_admin = 1;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (email, password, is_admin, state) VALUES ('$email', '$password_hash', $is_admin, $state)";
$pdo->exec($sql);

// Ajouter un utilisateur standard
$email = 'user@gmail.com';
$password = '123y';
$is_admin = 0;
$state = 1;
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (email, password, is_admin, state) VALUES ('$email', '$password_hash', $is_admin, $state)";
$pdo->exec($sql);

// Données à insérer
$songs = [
    [
        'title' => 'Zikroulah',
        'intro' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'verses' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'conclusion' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'category' => 'zikr'
    ],
    [
        'title' => 'Salatoul Fatihi',
        'intro' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'verses' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'conclusion' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'category' => 'salawat'
    ],
    [
        'title' => 'Mawahibouli Khadimoul Aqtab',
        'intro' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'verses' => 'Seydina Cheikh Ahmadou Bamba yallah nafi yagou si barke Serigne Touba',
        'conclusion' => 'Seydina Cheikh Ahmadou Bamba yallah nafi yagou si barke Serigne Touba',
        'category' => 'madh'
    ],
    [
        'title' => 'Khassaides',
        'intro' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'verses' => 'Doundal akhda diawrigne mame moussa kourel sama saar moussakourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar moussa kourel sama saar',
        'conclusion' => 'Allahouma salli ala Seydina Mouhammadin wa ala alihi wa sahbihi wa sallim',
        'category' => 'zikr'
    ]
];

// Préparation de la requête d'insertion
$stmt = $db->prepare('INSERT INTO songs (title, introduction, development, conclusion, category) VALUES (:title, :intro, :verses, :conclusion, :category)');

// Parcourir le tableau de données et exécuter la requête pour chaque élément
foreach ($songs as $song) {
    $stmt->execute($song);
}


echo "Les données ont été insérées avec succès !";