<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

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

if (!isset($data['id'], $data['doctor_id'], $data['nume'], $data['prenume'], $data['cnp'])) {
    echo json_encode(["error" => "Datele esențiale lipsesc!"]);
    exit;
}

$id = $conn->real_escape_string($data['id']);
$doctor_id = $conn->real_escape_string($data['doctor_id']);
$nume = $conn->real_escape_string($data['nume']);
$prenume = $conn->real_escape_string($data['prenume']);
$cnp = $conn->real_escape_string($data['cnp']);
$data_nasterii = isset($data['data_nasterii']) ? $conn->real_escape_string($data['data_nasterii']) : null;
$gen = isset($data['gen']) ? $conn->real_escape_string($data['gen']) : 'Alt';
$email = isset($data['email']) ? $conn->real_escape_string($data['email']) : null;
$varsta = isset($data['varsta']) ? $conn->real_escape_string($data['varsta']) : null;
$greutate = isset($data['greutate']) ? $conn->real_escape_string($data['greutate']) : null;
$inaltime = isset($data['inaltime']) ? $conn->real_escape_string($data['inaltime']) : null;
$ocupatie = isset($data['ocupatie']) ? $conn->real_escape_string($data['ocupatie']) : null;
$poza = isset($data['poza']) ? $conn->real_escape_string($data['poza']) : null;

$strada = isset($data['strada']) ? $conn->real_escape_string($data['strada']) : null;
$numar = isset($data['numar']) ? $conn->real_escape_string($data['numar']) : null;
$judet = isset($data['judet']) ? $conn->real_escape_string($data['judet']) : null;
$oras = isset($data['oras']) ? $conn->real_escape_string($data['oras']) : null;
$bloc = isset($data['bloc']) ? $conn->real_escape_string($data['bloc']) : null;
$apartament = isset($data['apartament']) ? $conn->real_escape_string($data['apartament']) : null;
$scara = isset($data['scara']) ? $conn->real_escape_string($data['scara']) : null;
$etaj = isset($data['etaj']) ? $conn->real_escape_string($data['etaj']) : null;
$cod_postal = isset($data['cod_postal']) ? $conn->real_escape_string($data['cod_postal']) : null;

$telefon = isset($data['telefon']) ? $conn->real_escape_string($data['telefon']) : null;
$rh = isset($data['rh']) ? $conn->real_escape_string($data['rh']) : null;
$grupa_sanguina = isset($data['grupa_sanguina']) ? $conn->real_escape_string($data['grupa_sanguina']) : null;
$boli_cronice = isset($data['boli_cronice']) ? $conn->real_escape_string($data['boli_cronice']) : null;
$vaccinari = isset($data['vaccinari']) ? $conn->real_escape_string($data['vaccinari']) : null;
$boli_ereditare = isset($data['boli_ereditare']) ? $conn->real_escape_string($data['boli_ereditare']) : null;

$dieta = isset($data['dieta']) ? $conn->real_escape_string($data['dieta']) : null;
$activitate_fizica = isset($data['activitate_fizica']) ? $conn->real_escape_string($data['activitate_fizica']) : null;
$fumat = isset($data['fumat']) ? (int) $data['fumat'] : 0;
$consum_alcool = isset($data['consum_alcool']) ? (int) $data['consum_alcool'] : 0;
$consum_droguri = isset($data['consum_droguri']) ? (int) $data['consum_droguri'] : 0;
$boala = isset($data['boala']) ? $conn->real_escape_string($data['boala']) : null;

$sql = "UPDATE pacient SET 
    doctor_id = '$doctor_id',
    nume = '$nume',
    prenume = '$prenume',
    cnp = '$cnp',
    data_nasterii = '$data_nasterii',
    gen = '$gen',
    email = '$email',
    varsta = '$varsta',
    greutate = '$greutate',
    inaltime = '$inaltime',
    ocupatie = '$ocupatie',
    poza = '$poza',
    strada = '$strada',
    numar = '$numar',
    judet = '$judet',
    oras = '$oras',
    bloc = '$bloc',
    apartament = '$apartament',
    scara = '$scara',
    etaj = '$etaj',
    cod_postal = '$cod_postal',
    telefon = '$telefon',
    rh = '$rh',
    grupa_sanguina = '$grupa_sanguina',
    boli_cronice = '$boli_cronice',
    vaccinari = '$vaccinari',
    boli_ereditare = '$boli_ereditare',
    dieta = '$dieta',
    activitate_fizica = '$activitate_fizica',
    fumat = $fumat,
    consum_alcool = $consum_alcool,
    consum_droguri = $consum_droguri,
    boala = '$boala'
WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Pacient actualizat cu succes!"]);
} else {
    echo json_encode(["error" => "Eroare la actualizare: " . $conn->error]);
}

$conn->close();
?>