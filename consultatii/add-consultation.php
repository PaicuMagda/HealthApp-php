<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

$data = json_decode(file_get_contents("php://input"), true);
$cnp = $data['cnp'] ?? null;
$data_consultatie = $data['data_consultatie'] ?? null;
$diagnostic = $data['diagnostic'] ?? null;
$medicamentatie = $data['medicamentatie'] ?? null;

if (!$cnp || !$data_consultatie || !$diagnostic || !$medicamentatie) {
    echo json_encode(["success" => false, "message" => "Toate câmpurile sunt necesare."]);
    exit;
}

$sql_last_nr = "SELECT COALESCE(MAX(nr_consultatie), 0) AS last_nr FROM consultatie WHERE cnp = ?";
$stmt = $conn->prepare($sql_last_nr);
$stmt->bind_param("s", $cnp);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$last_nr = $row['last_nr'];
$new_nr_consultatie = $last_nr + 1;

$sql_insert = "INSERT INTO consultatie (cnp, nr_consultatie, data_consultatie, diagnostic, medicamentatie) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("sisss", $cnp, $new_nr_consultatie, $data_consultatie, $diagnostic, $medicamentatie);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Consultație adăugată cu succes."]);
} else {
    echo json_encode(["success" => false, "message" => "Eroare la adăugarea consultației."]);
}

$stmt->close();
$conn->close();
?>