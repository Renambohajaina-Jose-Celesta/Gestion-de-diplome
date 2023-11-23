<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Requête GET pour récupérer les données de contrôle existantes
    try {
        $stmt = $pdo->query("SELECT * FROM controler");

        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Aucune donnée de contrôle trouvée.']);
            http_response_code(404); // Réponse Not Found
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la récupération des données de contrôle.']);
        http_response_code(500); // Réponse Internal Server Error
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if ($data === null) {
        // Assurez-vous que les données JSON sont valides
        echo json_encode(['error' => 'Données JSON non valides.']);
        http_response_code(400); // Réponse Bad Request
    } else {
            // Insertion des données de contrôle si l'étudiant n'a pas encore été contrôlé
            $insertSql = "INSERT INTO controler (nomCandidat, prenomCandidat, banque, date) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertSql);

            $success = $stmt->execute([
                $data->nomCandidat,
                $data->prenomCandidat,
                $data->banque,
                $data->date
            ]);

            if ($success) {
                echo json_encode(['message' => 'Contrôle d\'étudiant avec succès.']);
            } else {
                echo json_encode(['error' => 'Erreur lors de l\'insertion du contrôle.']);
            }
        }
}
 else {
    echo json_encode(['error' => 'Requête non autorisée.']);
    http_response_code(405); // Réponse Method Not Allowed
}
?>
