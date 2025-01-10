<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

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

if (isset($data['id'], $data['nume'], $data['prenume'], $data['cnp'])) {
    $id = $conn->real_escape_string($data['id']);
    $doctor_id = $conn->real_escape_string($data['doctor_id']);
    $nume = $conn->real_escape_string($data['nume']);
    $prenume = $conn->real_escape_string($data['prenume']);
    $adresa = isset($data['adresa']) ? $conn->real_escape_string($data['adresa']) : NULL;
    $locatie = isset($data['locatie']) ? $conn->real_escape_string($data['locatie']) : NULL;
    $data_nasterii = isset($data['data_nasterii']) ? $conn->real_escape_string($data['data_nasterii']) : NULL;
    $gen = isset($data['gen']) ? $conn->real_escape_string($data['gen']) : 'Alt';
    $cnp = $conn->real_escape_string($data['cnp']);
    $email = isset($data['email']) ? $conn->real_escape_string($data['email']) : NULL;
    $varsta = isset($data['varsta']) ? $conn->real_escape_string($data['varsta']) : NULL;
    $greutate = isset($data['greutate']) ? $conn->real_escape_string($data['greutate']) : NULL;
    $inaltime = isset($data['inaltime']) ? $conn->real_escape_string($data['inaltime']) : NULL;
    $ocupatie = isset($data['ocupatie']) ? $conn->real_escape_string($data['ocupatie']) : NULL;
    $poza = isset($data['poza']) ? $conn->real_escape_string($data['poza']) : NULL;

    $sql = "UPDATE patients SET 
            doctor_id = '$doctor_id',
            nume = '$nume',
            prenume = '$prenume',
            adresa = '$adresa',
            locatie = '$locatie',
            data_nasterii = '$data_nasterii',
            gen = '$gen',
            cnp = '$cnp',
            email = '$email',
            varsta = '$varsta',
            greutate = '$greutate',
            inaltime = '$inaltime',
            ocupatie = '$ocupatie',
            poza = '$poza'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Pacient actualizat cu succes!"]);
    } else {
        echo json_encode(["error" => "Eroare la actualizare: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Date incomplete!"]);
}

$conn->close();
?>