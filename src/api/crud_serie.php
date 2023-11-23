<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'bd.php';

// Opération d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $option = $data['Option'];
    $serie = $data['Serie'];
    $secteur = $data['Secteur'];
    $specialite = $data['Specialite'];

    $sql = "INSERT INTO serie (Option, Serie, Secteur, Specialite) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$option, $serie, $secteur, $specialite]);

    echo json_encode(["message" => "Série ajoutée avec succès"]);
}

// Opération de modification
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $option = $data['Option'];
    $serie = $data['Serie'];
    $secteur = $data['Secteur'];
    $specialite = $data['Specialite'];

    $sql = "UPDATE serie SET Option = ?, Serie = ?, Secteur = ?, Specialite = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$option, $serie, $secteur, $specialite, $id]);

    echo json_encode(["message" => "Série modifiée avec succès"]);
}

// Opération de suppression
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $sql = "DELETE FROM serie WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(["message" => "Série supprimée avec succès"]);
}

// Opération de récupération
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM serie";
    $stmt = $pdo->query($sql);
    $series = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($series);
}
?>