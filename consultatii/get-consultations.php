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
    echo json_encode(["status" => "error", "message" => "Conexiune eșuată: " . $conn->connect_error]);
    exit;
}

if (isset($_GET['cnp'])) {
    $cnp = $_GET['cnp'];

    if (!preg_match('/^\d{13}$/', $cnp)) {
        echo json_encode(["status" => "error", "message" => "CNP-ul este invalid."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT cnp FROM pacient WHERE cnp = ?");
    $stmt->bind_param("s", $cnp);
    $stmt->execute();
    $resultPatient = $stmt->get_result();

    if ($resultPatient->num_rows > 0) {
        $stmtConsultatii = $conn->prepare("SELECT nr_consultatie, data_consultatie, diagnostic, medicamentatie FROM consultatie WHERE cnp = ?");
        $stmtConsultatii->bind_param("s", $cnp);
        $stmtConsultatii->execute();
        $resultConsultatii = $stmtConsultatii->get_result();

        $consultatii = [];
        while ($row = $resultConsultatii->fetch_assoc()) {
            $consultatii[] = $row;
        }

        // Răspuns corect JSON
        echo json_encode(["consultatii" => $consultatii]);
    } else {
        echo json_encode(["status" => "error", "message" => "Pacientul cu acest CNP nu există."]);
    }

    $stmt->close();
    $stmtConsultatii->close();
} else {
    echo json_encode(["status" => "error", "message" => "CNP-ul nu a fost specificat."]);
}

$conn->close();
?>