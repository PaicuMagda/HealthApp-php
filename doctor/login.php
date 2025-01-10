<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (isset($data['username'], $data['password'])) {
        $username = $conn->real_escape_string($data['username']);
        $password = $data['password'];

        $sql = "SELECT * FROM doctor WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                unset($row['password']);
                echo json_encode(["success" => true, "user" => $row]);
            } else {
                echo json_encode(["success" => false, "message" => "Parolă incorectă"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Utilizatorul nu există"]);
        }
    } else {
        echo json_encode(["error" => "Date incomplete"]);
    }
} else {
    echo json_encode(["error" => "Metodă invalidă"]);
}

$conn->close();
