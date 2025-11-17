<?php
/** ------------------------------------------------------------------
 *  FICHIER DE VÉRIFICATION DES DROITS D'ADMINISTRATION
 * ----------------------------------------------------------------
 */
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

// Vérifier si l'utilisateur est administrateur 
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit();
}
?>
