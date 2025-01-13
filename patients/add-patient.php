<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['doctor_id'], $data['nume'], $data['prenume'], $data['cnp'])) {
    echo json_encode(["error" => "Câmpuri obligatorii lipsă."]);
    exit;
}

$doctor_id = $conn->real_escape_string($data['doctor_id']);
$nume = $conn->real_escape_string($data['nume']);
$prenume = $conn->real_escape_string($data['prenume']);
$cnp = $conn->real_escape_string($data['cnp']);
$locatie = $conn->real_escape_string($data['locatie'] ?? null);
$data_nasterii = $conn->real_escape_string($data['data_nasterii'] ?? null);
$gen = $conn->real_escape_string($data['gen'] ?? 'Alt');
$email = $conn->real_escape_string($data['email'] ?? null);
$varsta = $conn->real_escape_string($data['varsta'] ?? null);
$greutate = $conn->real_escape_string($data['greutate'] ?? null);
$inaltime = $conn->real_escape_string($data['inaltime'] ?? null);
$ocupatie = $conn->real_escape_string($data['ocupatie'] ?? null);
$poza = $conn->real_escape_string($data['poza'] ?? null);

$sql = "INSERT INTO pacient (doctor_id, nume, prenume, cnp,locatie, data_nasterii, gen, email, varsta, greutate, inaltime, ocupatie, poza)
        VALUES ('$doctor_id', '$nume', '$prenume', '$cnp', '$locatie', '$data_nasterii', '$gen', '$email', '$varsta', '$greutate', '$inaltime', '$ocupatie', '$poza')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    $sql = "SELECT * FROM pacient WHERE id = $last_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $new_patient = $result->fetch_assoc();
        echo json_encode(["success" => true, "patient" => $new_patient]);
    } else {
        echo json_encode(["error" => "Pacientul a fost adăugat, dar nu poate fi preluat."]);
    }
} else {
    echo json_encode(["error" => "Eroare la adăugare: " . $conn->error]);
}

$conn->close();
?>