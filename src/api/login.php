<?php

// Autoriser l'accès depuis n'importe quelle origine (à ajuster en production pour des origines spécifiques)
header("Access-Control-Allow-Origin: *");

// Autoriser certaines méthodes HTTP
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Autoriser certains en-têtes HTTP
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Vérifier si la méthode de requête est OPTIONS (pré-vérification CORS) et renvoyer une réponse 200 OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclure le fichier de connexion à la base de données (bd.php)
include 'bd.php';

// Récupération des données de la requête POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du corps de la requête POST au format JSON
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->password)) {
        $username = $data->username;
        $password = $data->password;

        // Requête SQL pour vérifier les informations d'identification
        $sql = "SELECT * FROM users WHERE username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // Utilisateur authentifié avec succès
            $response = [
                "error" => false,
                "message" => "Connexion réussie. Redirection vers la page d'accueil...",
            ];
        } else {
            // Échec de l'authentification
            $response = [
                "error" => true,
                "message" => "Nom d'utilisateur ou mot de passe incorrect.",
                "nomIncorrect" => true,
            ];
        }
    } else {
        // Informations manquantes dans la requête
        $response = [
            "error" => true,
            "message" => "Nom d'utilisateur et mot de passe requis.",
        ];
    }

    // Réponse au format JSON
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // Méthode non autorisée
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>
