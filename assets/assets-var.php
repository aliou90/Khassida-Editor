<?php
// DATE PAR DÉFAUT (TIMEZONE) DE L'APPLICATION
date_default_timezone_set('UTC');
$date = date('d-m-Y H:i:s');

/** VARIABLES DE L'APPLICATION */
// Informations de L'application
$app =[
    'name' => 'Khassida Editor',
    'icone' => '',
    'url' => 'http://localhost:2222/',
    'url_ip' => 'http://192.168.1.100/',
    'ip' => '192.168.1.100',
    'smtp_email' => 'alioumbengue2828@gmail.com',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_auth' => true,
    'smtp_secure' => 'tls',
    'smtp_port' => 587,
    'smtp_password' => 'twdaqdpbsnclrkcm'
];
// Informations de l'administrateur
$admin = [
    'corp' => 'Tech Jamm',
    'name' => 'Aliou Mbengue',
    'phone' => '+221776647080',
    'email' => 'tech.jamm.corp@gmail.com',
    'address' => 'Sénégal/Dakar/Pikine'
];
// Titre des pages
$page_title = $app['name'];

// Affichage dans les pages web
$categorie_dic = [
    'quran' => 'Quran',                             /**Le livre saint ou Coran */
    'zikr' => 'Prières et glorifications de Dieu',
    'hilm' => 'Sciences islamiques',                /** Enseignement religieux*/
    'madh' => 'Louanges au Prophète (PSL)',
    'salat' => 'Prières sur le prophète (PSL)', 
    'wasiya' => 'Recommandations'
];
// CLÉ DE CATEGORY DANS LA BASE DD
$categorie_clefs = array_keys($categorie_dic); 


?>