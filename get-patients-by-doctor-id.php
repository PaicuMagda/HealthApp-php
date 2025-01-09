<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
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

// Verifică dacă `doctor_id` este transmis ca parametru GET
if (isset($_GET['doctor_id'])) {
    $doctor_id = $conn->real_escape_string($_GET['doctor_id']); // Securizare împotriva SQL Injection

    // Modifică interogarea pentru a selecta pacienții unui anumit doctor
    $sql = "SELECT id, doctor_id, nume, prenume, locatie, data_nasterii, gen, cnp, email, varsta, greutate, inaltime, ocupatie, strada, numar, poza 
            FROM pacient 
            WHERE doctor_id = '$doctor_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        echo json_encode($patients);
    } else {
        echo json_encode(["message" => "Nu există pacienți pentru acest doctor"]);
    }
} else {
    echo json_encode(["error" => "Parametrul 'doctor_id' este obligatoriu"]);
}

$conn->close();
?>