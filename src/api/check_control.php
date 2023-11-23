<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if ($data === null || !property_exists($data, 'nomCandidat') || !property_exists($data, 'prenomCandidat')) {
        echo json_encode(['error' => 'Données JSON non valides.']);
        http_response_code(400); // Réponse Bad Request
    } else {
        // Ajout d'une requête SELECT pour vérifier si l'étudiant a déjà été contrôlé
        $selectSql = "SELECT * FROM controler WHERE nomCandidat = ? AND prenomCandidat = ?";

        $stmt = $pdo->prepare($selectSql);
        $stmt->execute([$data->nomCandidat, $data->prenomCandidat]);
        $existingControl = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingControl) {
            echo json_encode(['alreadyControlled' => true]);
        } else {
            echo json_encode(['alreadyControlled' => false]);
        }
    }
} else {
    echo json_encode(['error' => 'Requête non autorisée.']);
    http_response_code(405); // Réponse Method Not Allowed
}
?>
