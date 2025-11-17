<?php
require_once(__DIR__.'/includes/config.php');
require_once(__DIR__.'/includes/functions.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Une erreur est survenue.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $songId = $_POST['id'];
    $result = supprimer_definitivement_chanson($pdo, $songId);

    if ($result === true) {
        $response = ['success' => true, 'message' => "L'œuvre a été supprimée définitivement."];
    } else {
        $response = ['success' => false, 'message' => "Échec de la suppression. Veuillez réessayer plus tard."];
    }
}

echo json_encode($response);
?>
