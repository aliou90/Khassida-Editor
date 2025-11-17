<?php
// Connexion à la base de données SQLite
require_once(__DIR__.'/includes/config.php');

// Supprimer toutes les chansons où le contenu est vide
$deleteQuery = "DELETE FROM songs WHERE content = ''";
$deleteStmt = $pdo->prepare($deleteQuery);

if ($deleteStmt->execute()) {
    // Suppression réussie
    echo json_encode(['success' => true, 'message' => 'Chansons sans contenu supprimées avec succès.']);
} else {
    // Erreur lors de la suppression
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression des chansons sans contenu.']);
}
?>
