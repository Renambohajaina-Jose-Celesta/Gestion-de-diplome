<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';

// Vérifiez la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Préparez votre requête SELECT
        $sqlSelect = "SELECT * FROM verifier";

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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données de la requête POST
    $data = json_decode(file_get_contents("php://input"));

    // Assurez-vous que les données sont définies
    if (!isset($data->nomCandidat, $data->prenomCandidat, $data->numDemande)) {
        die("Paramètres manquants.");
    }

    try {
        // Préparez votre requête d'insertion avec une requête préparée
        $sqlInsert = "INSERT INTO verifier (annee, numCandidat, nomCandidat, prenomCandidat, dateNaissance, lieuNaissance, libelleCourt, libelleSerie, libelleSecteur, nomSpecialite, mention, numDemande)
            SELECT lb.annee, lb.numCandidat, lb.nomCandidat, lb.prenomCandidat, lb.dateNaissance, lb.lieuNaissance, lb.libelleCourt, lb.libelleSerie, lb.libelleSecteur, lb.nomSpecialite, lb.mention, d.numdemande
            FROM listesbac lb
            INNER JOIN demande d ON lb.numCandidat = d.numCandidat
            WHERE lb.nomCandidat = :nomCandidat AND lb.prenomCandidat = :prenomCandidat AND d.numdemande = :numDemande";

        // Utilisez une requête préparée pour éviter les attaques par injection SQL
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->bindParam(":nomCandidat", $data->nomCandidat);
        $stmtInsert->bindParam(":prenomCandidat", $data->prenomCandidat);
        $stmtInsert->bindParam(":numDemande", $data->numDemande);

        // Exécutez la requête d'insertion
        if ($stmtInsert->execute()) {
            echo "Enregistrement ajouté avec succès";
            error_log("Données reçues dans insert_verifier.php : " . json_encode($data));
        } else {
            echo "Erreur lors de l'ajout de l'enregistrement";
        }
    } catch (PDOException $e) {
        // Loguez l'erreur plutôt que de l'afficher directement
        error_log("Erreur PDO (Insert) : " . $e->getMessage());
        echo "Une erreur s'est produite lors de l'insertion";
    }
} else {
    echo "Méthode non autorisée"; // Ou renvoyez une réponse HTTP appropriée
}
?>
