<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';

// Vérifiez la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Préparez votre requête SELECT
        $sqlSelect = "SELECT * FROM decharge";

        // Utilisez une requête préparée pour éviter les attaques par injection SQL
        $stmtSelect = $pdo->query($sqlSelect);

        // Récupérez les données sous forme de tableau associatif
        $dataSelect = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

        // Répondez avec les données JSON
        echo json_encode($dataSelect);
    } catch (PDOException $e) {
        // Loguez l'erreur plutôt que de l'afficher directement
        error_log("Erreur PDO (Select) : " . $e->getMessage());
        echo "Une erreur s'est produite lors de la sélection";
    } finally {
        // Fermez la connexion après la sélection
        $pdo = null;
    }
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données de la requête POST
    $data = json_decode(file_get_contents("php://input"));
 
   // Assurez-vous que les données nécessaires sont définies avant de les utiliser
   if (isset($data->numerodemande, $data->base64Pdf, $data->annee, $data->numCandidat, $data->nomCandidat, $data->prenomCandidat)) {
    try {
        // Préparez votre requête d'insertion
        $sqlInsert = "INSERT INTO decharge (numerodemande, base64Pdf, annee, numCandidat, nomCandidat, prenomCandidat)
                      VALUES (:numerodemande, :base64Pdf, :annee, :numCandidat, :nomCandidat, :prenomCandidat)";

        // Utilisez une requête préparée pour éviter les attaques par injection SQL
        $stmtInsert = $pdo->prepare($sqlInsert);

        // Liez les valeurs des paramètres
        $stmtInsert->bindParam(':numerodemande', $data->numerodemande);
        $stmtInsert->bindParam(':base64Pdf', $data->base64Pdf);
        $stmtInsert->bindParam(':annee', $data->annee);
        $stmtInsert->bindParam(':numCandidat', $data->numCandidat);
        $stmtInsert->bindParam(':nomCandidat', $data->nomCandidat);
        $stmtInsert->bindParam(':prenomCandidat', $data->prenomCandidat);

        // Exécutez la requête
        $stmtInsert->execute();

        // Répondez avec un message de succès
        echo "Insertion réussie";
    } catch (PDOException $e) {
        // Loguez l'erreur plutôt que de l'afficher directement
        error_log("Erreur PDO (Insert) : " . $e->getMessage());
        echo "Une erreur s'est produite lors de l'insertion";
    } finally {
        // Fermez la connexion après l'insertion
        $pdo = null;
    }
} else {
    // Répondez avec un message d'erreur si les données ne sont pas définies
    echo "Données manquantes";
}
 }
  else {
       // Répondez avec un message d'erreur si la méthode n'est ni GET ni POST
       echo "Méthode non autorisée";
   }
   ?>
   