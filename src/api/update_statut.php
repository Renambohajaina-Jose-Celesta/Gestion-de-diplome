<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    $statut = $data->statut;

    try {
        $sql = "UPDATE demande SET statut = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$statut, $id]);

        if ($success) {
            echo json_encode(['message' => 'Statut mis à jour avec succès.']);
        } else {
            echo json_encode(['error' => 'Erreur lors de la mise à jour du statut.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la mise à jour du statut : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>
