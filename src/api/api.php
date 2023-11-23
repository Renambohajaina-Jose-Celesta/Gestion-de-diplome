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

// Démarrer la session
session_start();

// Inclure la bibliothèque PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// Inclure le fichier de connexion à la base de données (bd.php)
include 'bd.php';

// Vérifier si un fichier Excel a été téléchargé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    // Vérifier si le fichier est un fichier Excel valide (.xlsx ou .xls)
    $allowedExtensions = ['xlsx', 'xls'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedExtensions)) {
        // Déplacer le fichier temporaire vers le répertoire de destination
        $targetDirectory = 'uploads/'; // Assurez-vous que ce répertoire existe
        $targetPath = $targetDirectory . $file['name'];

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Le fichier a été téléchargé avec succès, maintenant vous pouvez l'importer dans la base de données MySQL

            // Charger le fichier Excel
            $spreadsheet = IOFactory::load($targetPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            // Préparer la requête d'insertion dans la base de données
            $sql = "INSERT INTO listesbac (annee, numCandidat, nomCandidat, prenomCandidat, dateNaissance, lieuNaissance, libelleCourt, libelleSerie, libelleSecteur, nomSpecialite, mention) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            // Créer un tableau pour stocker les données importées
            $importedData = [];

            // Parcourir les lignes du fichier Excel et insérer les données dans la base de données
            for ($row = 2; $row <= $highestRow; $row++) {
                $annee = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $numCandidat = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $nomCandidat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $prenomCandidat = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $dateNaissance = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

                // Convertir la date Excel en date PHP
                $phpDate = Date::excelToTimestamp($dateNaissance);
                // Formater la date au format 'Y-m-d' pour MySQL
                $mysqlDate = date('Y-m-d', $phpDate);

                $lieuNaissance = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $libelleCourt = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $libelleSerie = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $libelleSecteur = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $nomSpecialite = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $mention = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                // Exécuter la requête d'insertion
                $stmt->execute([$annee, $numCandidat, $nomCandidat, $prenomCandidat, $mysqlDate, $lieuNaissance, $libelleCourt, $libelleSerie, $libelleSecteur, $nomSpecialite, $mention]);

                // Ajouter les données à notre tableau
                $importedData[] = [
                    'annee' => $annee,
                    'numCandidat' => $numCandidat,
                    'nomCandidat' => $nomCandidat,
                    'prenomCandidat' => $prenomCandidat,
                    'dateNaissance' => $mysqlDate,
                    'lieuNaissance' => $lieuNaissance,
                    'libelleCourt' => $libelleCourt,
                    'libelleSerie' => $libelleSerie,
                    'libelleSecteur' => $libelleSecteur,
                    'nomSpecialite' => $nomSpecialite,
                    'mention' => $mention,
                ];
            }

            // Répondre avec les données importées
            echo json_encode(['message' => 'Importation réussie.', 'data' => $importedData]);
        } else {
            echo json_encode(['error' => 'Échec du téléchargement du fichier.']);
        }
    } else {
        echo json_encode(['error' => 'Le format de fichier n\'est pas pris en charge.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getAll') {

    $query = "SELECT * FROM listesbac ORDER BY annee";
    $result = $pdo->query($query);
    $data = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Répondre avec les données JSON sous forme de tableau d'objets
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>