<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    
    // Requête pour récupérer l'utilisateur
    $stmt = $conn->prepare("SELECT id_admin, mot_de_passe FROM Admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Vérification du mot de passe 
        if ($mot_de_passe === $row["mot_de_passe"]) {
            $_SESSION["id_admin"] = $row["id_admin"];
            $_SESSION["email"] = $email;
            echo "Connexion réussie !";
            header("Location: admin_api.php"); // Redirection après connexion
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form method="POST" action="">
        <label>Email :</label>
        <input type="email" name="email" required><br>
        
        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" required><br>
        
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
