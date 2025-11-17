<?php
// Connexion à la base de données SQLite
require_once(__DIR__.'/includes/config.php');

// Vérifier si la requête est une soumission de formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $songId = isset($_POST['song_id']) ? intval($_POST['song_id']) : 0;

    // Vérifier si le titre et la catégorie sont valides
    if (!empty($title) && !empty($category) && $songId > 0) {
        // Vérifier si le titre existe déjà dans la même catégorie, mais pas pour la chanson actuelle
        $query = "SELECT COUNT(*) FROM songs WHERE title = :title AND category = :category AND id != :song_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':song_id', $songId, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Titre déjà existant dans la même catégorie, récupérer le titre et la catégorie de la chanson
            $songQuery = "SELECT title, category FROM songs WHERE id = :song_id";
            $songStmt = $pdo->prepare($songQuery);
            $songStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);
            $songStmt->execute();
            $songData = $songStmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => false,
                'message' => 'Le titre existe déjà dans cette catégorie.',
                'title' => $songData['title'],
                'category' => $songData['category']
            ]);
        } else {
            // Modifier le titre dans la base de données
            $updateQuery = "UPDATE songs SET title = :title, category = :category WHERE id = :song_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':title', $title, PDO::PARAM_STR);
            $updateStmt->bindParam(':category', $category, PDO::PARAM_STR);
            $updateStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Le Titre et la Catégorie ont été modifiés avec succès !']);
            } else {
                // Erreur lors de la modification, récupérer le titre et la catégorie de la chanson
                $songQuery = "SELECT title, category FROM songs WHERE id = :song_id";
                $songStmt = $pdo->prepare($songQuery);
                $songStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);
                $songStmt->execute();
                $songData = $songStmt->fetch(PDO::FETCH_ASSOC);

                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de l\'oeuvre.',
                    'title' => $songData['title'],
                    'category' => $songData['category']
                ]);
            }
        }
    } else {
        // Erreur Titre ou catégorie ou identifiant manquant, récupérer le titre et la catégorie de la chanson
        $songQuery = "SELECT title, category FROM songs WHERE id = :song_id";
        $songStmt = $pdo->prepare($songQuery);
        $songStmt->bindParam(':song_id', $songId, PDO::PARAM_INT);
        $songStmt->execute();
        $songData = $songStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => false,
            'message' => 'Titre, catégorie, et identifiant de l\'oeuvre sont requis.',
            'title' => $songData['title'],
            'category' => $songData['category']
        ]);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Requête invalide.']);
}
?>
