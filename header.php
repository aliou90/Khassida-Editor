<?php
/* ---------------------------------------------------
    IMPORTATION DE PAGES ET SCRIPTES
------------------------------------------------------*/
require_once(__DIR__.'/assets/assets-var.php');
require_once(__DIR__.'/assets/svg-var.php');
require_once(__DIR__.'/includes/express-functions.php');
require_once(__DIR__.'/includes/lang.php');
require_once(__DIR__.'/includes/device.php');
require_once(__DIR__.'/includes/config.php');
require_once(__DIR__.'/includes/functions.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $page_title ?? 'KSEditor - Éditeur de Xassidas';?></title>

    <!-- JQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Ajout de jQuery UI -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css"> <!-- Ajout de jQuery UI css -->
    <script src="assets/js/jquery-ui.min.js"></script> <!-- Ajout de jQuery UI js -->
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"> <!-- Lien vers Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>

    <!-- Ajout de Hammer.js -->
    <script src="assets/js/hammer.min.js"></script>

    <!-- IMPORTER KSEDITOR mon éditeur pour l'édition des Xassaides @By-Aliou"Mbengue -->
    <link rel="stylesheet" href="assets/css/kseditor.css">
    <script src="assets/js/kseditor.js"></script> <!-- Lien vers @ksEditor Mon Application JavaScript -->

    <!-- Inclure Slick Carousel CSS et JavaScript (CRÉATEUR DE CAROUSEL) -->
    <link rel="stylesheet" type="text/css" href="assets/css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css"/>
    <script type="text/javascript" src="assets/js/slick.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">


</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-secondary border-secondary fixed-top m-0 p-0  menu-fixe" style="height: auto;">
        <div class="container-fluid">
            <img src="assets/images/khassaid-editor-brand.png" class="img-fluid" style="width: 100%; height:40px;" alt="Logo khassidati">
        </div>
        <div class="d-flex menuButtonContainer">
            <a href="song-add.php" class="btn btn-primary"><?php echo $nav_addbook_svg; ?></a>

            <a href="profile.php" class="btn btn-secondary"><?php echo $nav_myeditions_svg; ?></a>
        </div>
    </nav>
</header>