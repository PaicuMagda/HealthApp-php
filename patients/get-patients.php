<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexiune eșuată: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT id, doctor_id, nume, prenume,  locatie, data_nasterii, gen, cnp, email, varsta, greutate, inaltime, ocupatie, strada, numar, poza FROM pacient";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $patients = [];
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    echo json_encode($patients);
} else {
    echo json_encode(["message" => "Nu există pacienți"]);
}

$conn->close();
?>