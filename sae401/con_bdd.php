<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
session_start();

// Connexion à la base de données
$host = "localhost"; // À adapter selon votre configuration
$user = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL
$database = "auto_ecole"; // Nom de la base de données

$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>