<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "auto_ecole";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["message" => "Connexion échouée: " . $conn->connect_error]));
}

// Connexion admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET["ajouter"])) {
    $data = json_decode(file_get_contents("php://input"));

    if ($data === null || empty($data->email) || empty($data->mot_de_passe)) {
        http_response_code(400);
        echo json_encode(["message" => "Email et mot de passe sont requis."]);
        exit();
    }

    $email = $conn->real_escape_string($data->email);
    $mot_de_passe = $data->mot_de_passe;

    $stmt = $conn->prepare("SELECT id_admin, mot_de_passe FROM Admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($mot_de_passe === $row["mot_de_passe"]) {
            session_regenerate_id(true);
            $_SESSION["id_admin"] = $row["id_admin"];
            $_SESSION["email"] = $email;

            http_response_code(200);
            echo json_encode(["message" => "Connexion réussie !", "user" => ["id_admin" => $row["id_admin"], "email" => $email]]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Mot de passe incorrect."]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Aucun compte trouvé avec cet email."]);
    }
    $stmt->close();
}

// Gestion des élèves
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["students"])) {
    $query = "SELECT id_eleve, nom, prenom, date_naissance, rue, code_postal, ville, NPEH, id_auto_ecole FROM Eleve";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $eleves = [];
        while ($row = $result->fetch_assoc()) {
            $eleves[] = $row;
        }
        http_response_code(200);
        echo json_encode(["success" => true, "eleves" => $eleves]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Aucun élève trouvé."]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["student"])) {
    $id_eleve = $_GET["id_eleve"] ?? null;
    
    if (!$id_eleve || !is_numeric($id_eleve)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID élève invalide."]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id_eleve, nom, prenom, date_naissance, rue, code_postal, ville, NPEH, id_auto_ecole FROM Eleve WHERE id_eleve = ?");
    $stmt->bind_param("i", $id_eleve);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(200);
        echo json_encode(["success" => true, "eleve" => $result->fetch_assoc()]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Élève non trouvé."]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET["ajouter"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $required = ['nom', 'prenom', 'date_naissance', 'rue', 'code_postal', 'ville', 'NPEH', 'id_auto_ecole'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Champ $field manquant."]);
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO Eleve (nom, prenom, date_naissance, rue, code_postal, ville, NPEH, id_auto_ecole) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $data['nom'], $data['prenom'], $data['date_naissance'], $data['rue'], $data['code_postal'], $data['ville'], $data['NPEH'], $data['id_auto_ecole']);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Élève ajouté.", "id_eleve" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout."]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET["modifier_eleve"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id_eleve'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID élève manquant."]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE Eleve SET nom=?, prenom=?, date_naissance=?, rue=?, code_postal=?, ville=?, NPEH=?, id_auto_ecole=? WHERE id_eleve=?");
    $stmt->bind_param("sssssssii", $data['nom'], $data['prenom'], $data['date_naissance'], $data['rue'], $data['code_postal'], $data['ville'], $data['NPEH'], $data['id_auto_ecole'], $data['id_eleve']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Élève mis à jour."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur de mise à jour."]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET["supprimer"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id_eleve'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID élève manquant."]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM Eleve WHERE id_eleve = ?");
    $stmt->bind_param("i", $data['id_eleve']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Élève supprimé."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur de suppression."]);
    }
    $stmt->close();
}

// Gestion des tests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["tests"])) {
    $query = "SELECT id_test, theme, date, score, id_admin, id_eleve FROM Test";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $tests = [];
        while ($row = $result->fetch_assoc()) {
            $tests[] = $row;
        }
        http_response_code(200);
        echo json_encode(["success" => true, "tests" => $tests]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Aucun test trouvé."]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["test"])) {
    $id_test = $_GET["id_test"] ?? null;
    
    if (!$id_test || !is_numeric($id_test)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID test invalide."]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id_test, theme, date, score, id_admin, id_eleve FROM Test WHERE id_test = ?");
    $stmt->bind_param("i", $id_test);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(200);
        echo json_encode(["success" => true, "test" => $result->fetch_assoc()]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Test non trouvé."]);
    }
    $stmt->close();
}

// Gestion des tests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET["ajouter_test"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validation des données
    $required = ['theme', 'date', 'score', 'id_admin', 'id_eleve'];
    $missing = [];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $missing[] = $field;
        }
    }

    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Champs manquants: " . implode(', ', $missing)
        ]);
        exit();
    }

    // Préparation de la requête
    $stmt = $conn->prepare("INSERT INTO Test (theme, date, score, id_admin, id_eleve) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", 
        $data['theme'],
        $data['date'],
        $data['score'],
        $data['id_admin'],
        $data['id_eleve']
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "id_test" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout"]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET["modifier_test"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id_test'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID test manquant."]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE Test SET theme=?, date=?, score=?, id_admin=?, id_eleve=? WHERE id_test=?");
    $stmt->bind_param("ssdiii", $data['theme'], $data['date'], $data['score'], $data['id_admin'], $data['id_eleve'], $data['id_test']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Test mis à jour."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur de mise à jour."]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET["supprimer_test"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id_test'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID test manquant."]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM Test WHERE id_test = ?");
    $stmt->bind_param("i", $data['id_test']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Test supprimé."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur de suppression."]);
    }
    $stmt->close();
}
// Gestion des auto-écoles
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["auto_ecoles"])) {
    $query = "SELECT id_auto_ecole, nom, adresse, telephone, email FROM AutoEcole";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $autoEcoles = [];
        while ($row = $result->fetch_assoc()) {
            $autoEcoles[] = $row;
        }
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $autoEcoles]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Aucune auto-école trouvée"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["auto_ecole"])) {
    $id = $conn->real_escape_string($_GET["id"]);
    
    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID auto-école invalide"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id_auto_ecole, nom, adresse, telephone, email FROM AutoEcole WHERE id_auto_ecole = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $result->fetch_assoc()]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Auto-école non trouvée"]);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET["add_auto_ecole"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $required = ['nom', 'adresse', 'telephone', 'email'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Champ $field manquant"]);
            exit();
        }
    }

    // Validation email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Email invalide"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO AutoEcole (nom, adresse, telephone, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['nom'], $data['adresse'], $data['telephone'], $data['email']);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
    } else {
        // Gestion erreur email unique
        if ($conn->errno == 1062) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout"]);
        }
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET["update_auto_ecole"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id_auto_ecole'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID manquant"]);
        exit();
    }

    $required = ['nom', 'adresse', 'telephone', 'email'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Champ $field manquant"]);
            exit();
        }
    }

    // Validation email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Email invalide"]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE AutoEcole SET nom=?, adresse=?, telephone=?, email=? WHERE id_auto_ecole=?");
    $stmt->bind_param("ssssi", $data['nom'], $data['adresse'], $data['telephone'], $data['email'], $data['id_auto_ecole']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Aucune modification"]);
        }
    } else {
        if ($conn->errno == 1062) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erreur de mise à jour"]);
        }
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET["delete_auto_ecole"])) {
    $id = $conn->real_escape_string($_GET["id"]);
    
    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID invalide"]);
        exit();
    }

    // Vérification des élèves associés
    $check = $conn->prepare("SELECT COUNT(*) AS count FROM Eleve WHERE id_auto_ecole = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Impossible de supprimer: des élèves sont associés"]);
        $check->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("DELETE FROM AutoEcole WHERE id_auto_ecole = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Auto-école non trouvée"]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur de suppression"]);
    }
    $stmt->close();
}

$conn->close();
?>