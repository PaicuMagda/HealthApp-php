<?php
// Setările pentru conexiunea la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthapp";

// Crează conexiunea
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifică conexiunea
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Verifică dacă datele au fost trimise prin metoda POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obține datele trimise de la utilizator
    $cnp = $_POST['cnp'];  // CNP-ul pacientului
    $data_consultatie = $_POST['data_consultatie'];  // Data consultatiei
    $diagnostic = $_POST['diagnostic'];  // Diagnosticul
    $medicamentatie = $_POST['medicamentatie'];  // Medicamentatia

    // Verifică dacă toate câmpurile sunt completate
    if (empty($cnp) || empty($data_consultatie) || empty($diagnostic) || empty($medicamentatie)) {
        echo json_encode(["error" => "Toate câmpurile sunt obligatorii."]);
        exit;
    }

    // Pregătește interogarea SQL pentru a insera datele în tabela `consultatie`
    $sql = "INSERT INTO consultatie (cnp, data_consultatie, diagnostic, medicamentatie) 
            VALUES ('$cnp', '$data_consultatie', '$diagnostic', '$medicamentatie')";

    // Execută interogarea
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Consultația a fost adăugată cu succes."]);
    } else {
        echo json_encode(["error" => "Eroare la adăugarea consultației: " . $conn->error]);
    }
}

// Închide conexiunea
$conn->close();
?>