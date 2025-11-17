<?php
// Connexion à la base de données SQLite
require_once(__DIR__.'/includes/config.php');

// Vérifier si la requête est une soumission de formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées par AJAX
    $songId = isset($_POST['song_id']) ? intval($_POST['song_id']) : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    // Vérifier si les données sont valides
    if ($songId > 0 && !empty($content)) {
        // Préparer la requête pour mettre à jour le champ content
        $updateQuery = "UPDATE songs SET content = :content WHERE id = :song_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':content', $content, PDO::PARAM_STR);
        $updateStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            // Mise à jour réussie
            echo json_encode(['success' => true, 'message' => 'Contenu mis à jour avec succès.']);
        } else {
            // Erreur lors de la mise à jour
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du contenu.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de chanson et contenu requis.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
?>
