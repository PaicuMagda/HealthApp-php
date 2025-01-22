<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexiune eșuată la baza de date: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $inputData = json_decode(file_get_contents('php://input'), true);
    if (!isset($inputData['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID-ul pacientului nu a fost transmis']);
        exit;
    }

    $id = $inputData['id'];
    $nume = $inputData['nume'] ?? null;
    $prenume = $inputData['prenume'] ?? null;
    $cnp = $inputData['cnp'] ?? null;
    $email = $inputData['email'] ?? null;
    $varsta = $inputData['varsta'] ?? null;
    $greutate = $inputData['greutate'] ?? null;
    $inaltime = $inputData['inaltime'] ?? null;
    $ocupatie = $inputData['ocupatie'] ?? null;
    $telefon = $inputData['telefon'] ?? null;
    $rh = $inputData['rh'] ?? null;
    $grupa_sanguina = $inputData['grupa_sanguina'] ?? null;
    $boli_cronice = $inputData['boli_cronice'] ?? null;
    $vaccinari = $inputData['vaccinari'] ?? null;
    $boli_ereditare = $inputData['boli_ereditare'] ?? null;
    $boala = $inputData['boala'] ?? null;
    $dieta = $inputData['dieta'] ?? null;
    $activitate_fizica = $inputData['activitate_fizica'] ?? null;
    $fumat = isset($inputData['fumat']) ? 1 : 0;
    $consum_alcool = isset($inputData['consum_alcool']) ? 1 : 0;
    $consum_droguri = isset($inputData['consum_droguri']) ? 1 : 0;
    $judet = $inputData['judet'] ?? null;
    $oras = $inputData['oras'] ?? null;
    $strada = $inputData['strada'] ?? null;
    $numar = $inputData['numar'] ?? null;
    $bloc = $inputData['bloc'] ?? null;
    $apartament = $inputData['apartament'] ?? null;
    $scara = $inputData['scara'] ?? null;
    $etaj = $inputData['etaj'] ?? null;
    $cod_postal = $inputData['cod_postal'] ?? null;

    $sql = "UPDATE pacient SET 
                nume = ?, 
                prenume = ?, 
                cnp = ?, 
                email = ?, 
                varsta = ?, 
                greutate = ?, 
                inaltime = ?, 
                ocupatie = ?, 
                telefon = ?, 
                rh = ?, 
                grupa_sanguina = ?, 
                boli_cronice = ?, 
                vaccinari = ?, 
                boli_ereditare = ?, 
                boala = ?, 
                dieta = ?, 
                activitate_fizica = ?, 
                fumat = ?, 
                consum_alcool = ?, 
                consum_droguri = ?, 
                judet = ?, 
                oras = ?, 
                strada = ?, 
                numar = ?, 
                bloc = ?, 
                apartament = ?, 
                scara = ?, 
                etaj = ?, 
                cod_postal = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Eroare la pregătirea interogării: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param(
        'ssssiiissssssssssiiissssssssi',
        $nume,
        $prenume,
        $cnp,
        $email,
        $varsta,
        $greutate,
        $inaltime,
        $ocupatie,
        $telefon,
        $rh,
        $grupa_sanguina,
        $boli_cronice,
        $vaccinari,
        $boli_ereditare,
        $boala,
        $dieta,
        $activitate_fizica,
        $fumat,
        $consum_alcool,
        $consum_droguri,
        $judet,
        $oras,
        $strada,
        $numar,
        $bloc,
        $apartament,
        $scara,
        $etaj,
        $cod_postal,
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pacient actualizat cu succes']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Eroare la actualizarea pacientului: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Metodă HTTP invalidă']);
}

$conn->close();
?>