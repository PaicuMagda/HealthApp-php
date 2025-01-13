<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

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
            $patient = $row;
            $cnp_patient = $row['cnp'];

            // Aducem consultațiile pentru fiecare pacient
            $sql_consultations = "SELECT nr_consultatie, data_consultatie, diagnostic, medicamentatie 
                                  FROM consultatie 
                                  WHERE cnp = '$cnp_patient'"; // Căutăm doar consultațiile care se potrivesc cu CNP-ul pacientului

            $consultations_result = $conn->query($sql_consultations);

            if ($consultations_result->num_rows > 0) {
                // Adăugăm consultațiile în array-ul pacientului
                $consultations = [];
                while ($consultation = $consultations_result->fetch_assoc()) {
                    $consultations[] = $consultation;
                }
                $patient['consultations'] = $consultations;
            } else {
                // Dacă nu sunt consultații, setăm un array gol
                $patient['consultations'] = [];
            }

            $patients[] = $patient;
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