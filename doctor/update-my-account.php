<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexiune eșuată: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Verificăm dacă datele necesare sunt furnizate
    if (isset($data['id'], $data['nume'], $data['specializare'], $data['email'], $data['cnp'], $data['prenume'])) {
        $id = $data['id'];
        $nume = $data['nume'];
        $specializare = $data['specializare'];
        $email = $data['email'];
        $cnp = $data['cnp'];
        $prenume = $data['prenume'];

        $stmt = $conn->prepare("UPDATE doctor SET nume = ?, specializare = ?, email = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sssi", $nume, $specializare, $email, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Datele au fost actualizate."]);
            } else {
                echo json_encode(["success" => false, "message" => "Eroare la actualizare: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Eroare la pregătirea cererii SQL: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Date incomplete"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metodă invalidă"]);
}

$conn->close();
