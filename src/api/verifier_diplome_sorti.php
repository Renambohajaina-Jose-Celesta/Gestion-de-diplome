<?php
// Incluez votre configuration de base de données ici (connexion PDO, etc.)

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';
// Vérifiez si le numéro de demande est défini dans la requête
if (isset($_GET['numerodemande'])) {
    $numerodemande = $_GET['numerodemande'];

    try {
        // Préparez votre requête SELECT pour vérifier si le diplôme est déjà sorti
        $sqlSelect = "SELECT COUNT(*) AS nbDiplomes FROM decharge WHERE numerodemande = :numerodemande";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->bindParam(':numerodemande', $numerodemande);
        $stmtSelect->execute();

        // Récupérez le résultat de la requête
        $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // Retournez true si le diplôme est déjà sorti, sinon false
        echo json_encode(['estDiplomeSorti' => $result['nbDiplomes'] > 0]);
    } catch (PDOException $e) {
        // Loguez l'erreur plutôt que de l'afficher directement
        error_log("Erreur PDO (Select) : " . $e->getMessage());
        http_response_code(500); // Erreur interne du serveur
        echo json_encode(["message" => "Une erreur s'est produite lors de la vérification du diplôme"]);
    } finally {
        // Fermez la connexion après la sélection
        $pdo = null;
    }
} else {
    // Répondez avec un message d'erreur si le numéro de demande n'est pas défini
    http_response_code(400); // Mauvaise requête
    echo json_encode(["message" => "Numéro de demande non défini dans la requête"]);
}
?>
