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

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['id'])) {
    $id = $conn->real_escape_string($data['id']);

    $sql_check = "SELECT * FROM pacient WHERE id = '$id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $sql_delete = "DELETE FROM pacient WHERE id = '$id'";

        if ($conn->query($sql_delete) === TRUE) {
            echo json_encode(["success" => true, "message" => "Pacientul a fost șters."]);
        } else {
            echo json_encode(["error" => "Eroare la ștergere: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Pacientul nu a fost găsit."]);
    }
} else {
    echo json_encode(["error" => "ID pacient invalid."]);
}

$conn->close();
?>