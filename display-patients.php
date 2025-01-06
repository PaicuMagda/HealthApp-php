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

// Afișare pacienți pentru un doctor
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_patients') {

    $doctor_id = $_GET['doctor_id'] ?? null;

    if (!$doctor_id) {
        echo json_encode(["error" => "ID-ul doctorului este obligatoriu!"]);
        exit;
    }

    $sql = "SELECT * FROM patients WHERE doctor_id = '$doctor_id'";
    $result = $conn->query($sql);

    $patients = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
    }

    echo json_encode($patients);
}

$conn->close();
