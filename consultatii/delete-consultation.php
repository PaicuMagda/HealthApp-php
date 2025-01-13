<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexiune eșuată: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$cnp = $data['cnp'] ?? null;
$nr_consultatie = $data['nr_consultatie'] ?? null;

if (!$cnp || !$nr_consultatie) {
    echo json_encode(["success" => false, "message" => "CNP-ul și numărul consultației sunt necesare."]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM consultatie WHERE cnp = ? AND nr_consultatie = ?");
$stmt->bind_param("si", $cnp, $nr_consultatie);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Consultație ștearsă cu succes."]);
} else {
    echo json_encode(["success" => false, "message" => "Eroare la ștergerea consultației."]);
}

$stmt->close();
$conn->close();
?>