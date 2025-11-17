<?php
// Connexion à la base de données SQLite
require_once(__DIR__.'/includes/config.php');

// Vérifier si la requête est une soumission de formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    // Vérifier si le titre et la catégorie sont valides
    if (!empty($title) && !empty($category)) {
        // Vérifier si le titre existe déjà dans la même catégorie
        $query = "SELECT COUNT(*) FROM songs WHERE title = :title AND category = :category";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Titre déjà existant dans la même catégorie
            echo json_encode(['success' => false, 'message' => 'Le titre existe déjà dans cette catégorie.']);
        } else {
            // Insérer le nouveau titre dans la base de données
            $insertQuery = "INSERT INTO songs (title, category, user_id, approval) VALUES (:title, :category, :user_id, :approval)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':title', $title, PDO::PARAM_STR);
            $insertStmt->bindParam(':category', $category, PDO::PARAM_STR);
            $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $approval = 0; // degré d'approbation par défaut
            $insertStmt->bindParam(':approval', $approval, PDO::PARAM_INT);

            if ($insertStmt->execute()) {
                // Récupérer l'ID de la chanson insérée
                $songId = $pdo->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'Le Titre et la Catégorie ont été ajoutés avec succès !', 'id' => $songId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'oeuvre.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Le Titre et la Catégorie sont requis.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
?>
