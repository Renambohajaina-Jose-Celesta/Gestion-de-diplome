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

    if (!empty($data->nom) && !empty($data->prenom) && !empty($data->motDePasse) && !empty($data->email)) {
        $nom = $data->nom;
        $prenom = $data->prenom;
        $motDePasse = $data->motDePasse;
        $email = $data->email;

        // Hacher le mot de passe
        $hashMotDePasse = password_hash($motDePasse, PASSWORD_DEFAULT);

        // Requête SQL pour insérer un nouvel utilisateur
        $sql = "INSERT INTO users (username, prenom, password, email) VALUES (:nom, :prenom, :hashMotDePasse, :email)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':hashMotDePasse', $hashMotDePasse, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Exécuter la requête
        $result = $stmt->execute();

        if ($result) {
            // Utilisateur ajouté avec succès
            $response = [
                "error" => false,
                "message" => "Utilisateur ajouté avec succès.",
            ];
        } else {
            // Échec de l'ajout de l'utilisateur
            $response = [
                "error" => true,
                "message" => "Erreur lors de l'ajout de l'utilisateur.",
            ];
        }
    } else {
        // Informations manquantes dans la requête
        $response = [
            "error" => true,
            "message" => "Nom d'utilisateur, mot de passe et photo requis.",
        ];
    }
} else {
    // Méthode non autorisée
    $response = [
        "error" => true,
        "message" => "Méthode non autorisée.",
    ];
}

// Réponse au format JSON
header("Content-Type: application/json");
echo json_encode($response);
?>
