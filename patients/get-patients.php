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

function getAllPatientsWithConsultations($conn)
{
    $sql = "SELECT id, doctor_id, nume, prenume, data_nasterii, gen, cnp, email, varsta,
     greutate, inaltime, ocupatie, strada, numar, poza 
            FROM pacient";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patient = $row;
            $cnp_patient = $row['cnp'];

            $sql_consultations = "SELECT nr_consultatie, data_consultatie, diagnostic, medicamentatie 
                                  FROM consultatie 
                                  WHERE cnp = '$cnp_patient'";
            $consultations_result = $conn->query($sql_consultations);

            if ($consultations_result->num_rows > 0) {
                $consultations = [];
                while ($consultation = $consultations_result->fetch_assoc()) {
                    $consultations[] = $consultation;
                }
                $patient['consultations'] = $consultations;
            } else {
                $patient['consultations'] = [];
            }

            $patients[] = $patient;
        }
        return $patients;
    } else {
        return ["message" => "Nu există pacienți disponibili"];
    }
}

$patients = getAllPatientsWithConsultations($conn);
echo json_encode($patients);

$conn->close();

?>