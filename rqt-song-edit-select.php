<?php
// Connexion à la base de données SQLite
require_once(__DIR__.'/includes/config.php');

// Vérifier si la requête est une soumission de formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées par AJAX
    $songId = isset($_POST['song_id']) ? intval($_POST['song_id']) : 0;

    // Vérifier si l'ID de chanson est valide
    if ($songId > 0) {
        // Préparer la requête pour récupérer les données de la chanson
        $selectQuery = "SELECT title, content, category FROM songs WHERE id = :song_id";
        $selectStmt = $pdo->prepare($selectQuery);
        $selectStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);

        if ($selectStmt->execute()) {
            // Récupérer les résultats
            $songData = $selectStmt->fetch(PDO::FETCH_ASSOC);
            if ($songData) {
                // Retourner les données pertinentes au format JSON
                echo json_encode(['success' => true, 'data' => $songData]);
            } else {
                // Aucune chanson trouvée avec cet ID
                echo json_encode(['success' => false, 'message' => 'Aucune chanson trouvée.']);
            }
        } else {
            // Erreur lors de l'exécution de la requête
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des données.']);
        }
    } else {
        // ID de chanson invalide
        echo json_encode(['success' => false, 'message' => 'ID de chanson non valide.']);
    }
} else {
    // Requête invalide
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
?>
