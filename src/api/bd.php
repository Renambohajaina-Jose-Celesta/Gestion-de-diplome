<?php
$servername = "localhost";
$username = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL
$database = "delivrance"; // Nom de la base de données

try {
    // Créez une nouvelle instance PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Définissez le mode d'erreur de PDO sur exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "La connexion à la base de données a échoué : " . $e->getMessage();
}
?>
