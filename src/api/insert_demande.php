<?php
// Autoriser l'accès depuis n'importe quelle origine (à ajuster en production pour des origines spécifiques)
header("Access-Control-Allow-Origin: *");

// Autoriser certaines méthodes HTTP
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT"); // Suppression de DELETE

// Autoriser certains en-têtes HTTP
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Vérifier si la méthode de requête est OPTIONS (pré-vérification CORS) et renvoyer une réponse 200 OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclure le fichier de connexion à la base de données (bd.php)
include 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées dans la requête
    $data = json_decode(file_get_contents("php://input"));

    $sql = "INSERT INTO demande (numdemande, numCandidat, nomCandidat, prenomCandidat, libelleCourt, mention, centre, date, annee, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'En attente')";
    $stmt = $pdo->prepare($sql);

    $success = $stmt->execute([
        $data->numeroDemande,
        $data->numCandidat,
        $data->nomCandidat,
        $data->prenomCandidat,
        $data->libelleCourt,
        $data->mention,
        $data->centre,
        $data->date,
        $data->annee
    ]);

    if ($success) {
        echo json_encode(['message' => 'Demande insérée avec succès.']);
    } else {
        echo json_encode(['error' => 'Erreur lors de l\'insertion de la demande.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'checkDemande' && isset($_GET['numCandidat']) && isset($_GET['annee'])) {
            $numCandidat = $_GET['numCandidat'];
            $annee = $_GET['annee'];

            $sql = "SELECT COUNT(*) as count FROM demande WHERE numCandidat = :numCandidat AND annee = :annee";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':numCandidat', $numCandidat);
            $stmt->bindParam(':annee', $annee);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $result['count'];

            if ($count > 0) {
                echo json_encode(['hasSubmitted' => true]);
            } else {
                echo json_encode(['hasSubmitted' => false]);
            }
        } else {
            echo json_encode(['error' => 'Action, student number, or year missing']);
        }
    } else {
        try {
            $sql = "SELECT * FROM demande";
            $stmt = $pdo->query($sql);

            $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($demandes) {
                echo json_encode($demandes);
            } else {
                echo json_encode(['message' => 'Aucune demande trouvée.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur lors de la récupération des demandes : ' . $e->getMessage()]);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    $sql = "UPDATE demande SET  centre = ?, date = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    $success = $stmt->execute([
        $data->centre,
        $data->date,
        $data->id // L'ID de la demande à mettre à jour
    ]);

    if ($success) {
        echo json_encode(['message' => 'Demande mise à jour avec succès.']);
    } else {
        echo json_encode(['error' => 'Erreur lors de la mise à jour de la demande.']);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>
