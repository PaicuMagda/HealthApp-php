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
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['doctor_id'], $data['nume'], $data['prenume'], $data['cnp'], $data['strada'], $data['numar'], $data['judet'], $data['oras'])) {
    echo json_encode(["error" => "Required fields missing."]);
    exit;
}

$doctor_id = $conn->real_escape_string($data['doctor_id']);
$nume = $conn->real_escape_string($data['nume']);
$prenume = $conn->real_escape_string($data['prenume']);
$cnp = $conn->real_escape_string($data['cnp']);
$data_nasterii = $conn->real_escape_string($data['data_nasterii'] ?? null);
$gen = $conn->real_escape_string($data['gen'] ?? 'Alt');
$email = $conn->real_escape_string($data['email'] ?? null);
$varsta = $conn->real_escape_string($data['varsta'] ?? null);
$greutate = $conn->real_escape_string($data['greutate'] ?? null);
$inaltime = $conn->real_escape_string($data['inaltime'] ?? null);
$ocupatie = $conn->real_escape_string($data['ocupatie'] ?? null);
$poza = $conn->real_escape_string($data['poza'] ?? null);

$strada = $conn->real_escape_string($data['strada']);
$numar = $conn->real_escape_string($data['numar']);
$judet = $conn->real_escape_string($data['judet']);
$oras = $conn->real_escape_string($data['oras']);
$bloc = $conn->real_escape_string($data['bloc'] ?? null);
$apartament = $conn->real_escape_string($data['apartament'] ?? null);
$scara = $conn->real_escape_string($data['scara'] ?? null);
$etaj = $conn->real_escape_string($data['etaj'] ?? null);
$cod_postal = $conn->real_escape_string($data['cod_postal'] ?? null);

$telefon = $conn->real_escape_string($data['telefon'] ?? null);
$rh = $conn->real_escape_string($data['rh'] ?? null);
$grupa_sanguina = $conn->real_escape_string($data['grupa_sanguina'] ?? null);
$boli_cronice = $conn->real_escape_string($data['boli_cronice'] ?? null);
$vaccinari = $conn->real_escape_string($data['vaccinari'] ?? null);
$boli_ereditare = $conn->real_escape_string($data['boli_ereditare'] ?? null);

$dieta = $conn->real_escape_string($data['dieta'] ?? null);
$activitate_fizica = $conn->real_escape_string($data['activitate_fizica'] ?? null);
$fumat = isset($data['fumat']) ? (int) $data['fumat'] : 0;
$consum_alcool = isset($data['consum_alcool']) ? (int) $data['consum_alcool'] : 0;
$consum_droguri = isset($data['consum_droguri']) ? (int) $data['consum_droguri'] : 0;
$boala = $conn->real_escape_string($data['boala'] ?? null);

$sql = "INSERT INTO pacient (
    doctor_id, nume, prenume, cnp, data_nasterii, gen, email, varsta, greutate, 
    inaltime, ocupatie, poza, strada, numar, judet, oras, bloc, apartament, scara, etaj, cod_postal, 
    telefon, rh, grupa_sanguina, boli_cronice, vaccinari, boli_ereditare, dieta, activitate_fizica, 
    fumat, consum_alcool, consum_droguri, boala
) VALUES (
    '$doctor_id', '$nume', '$prenume', '$cnp', '$data_nasterii', '$gen', '$email', '$varsta', '$greutate', 
    '$inaltime', '$ocupatie', '$poza', '$strada', '$numar', '$judet', '$oras', '$bloc', '$apartament', '$scara', '$etaj', '$cod_postal', 
    '$telefon', '$rh', '$grupa_sanguina', '$boli_cronice', '$vaccinari', '$boli_ereditare', '$dieta', '$activitate_fizica', 
    $fumat, $consum_alcool, $consum_droguri, '$boala'
)";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;

    $sql = "SELECT * FROM pacient WHERE id = $last_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $new_patient = $result->fetch_assoc();
        echo json_encode(["success" => true, "patient" => $new_patient]);
    } else {
        echo json_encode(["error" => "Patient added, but data retrieval failed."]);
    }
} else {
    echo json_encode(["error" => "Error during insertion: " . $conn->error]);
}

$conn->close();
?>