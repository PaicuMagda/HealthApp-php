<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexiune eșuată: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM pacient WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Pacient șters cu succes."]);
        } else {
            echo json_encode(["success" => false, "message" => "Eroare la ștergerea pacientului."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID-ul pacientului lipsește."]);
    }
}


$conn->close();
?>