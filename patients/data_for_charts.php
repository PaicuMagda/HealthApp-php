<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql_specializare = "
        SELECT 
            p.nume AS pacient_nume,
            COUNT(DISTINCT d.specializare) AS numar_specializari
        FROM Pacient p
        JOIN Consultatie c ON c.cnp = p.cnp
        JOIN Doctor d ON d.specializare IS NOT NULL
        GROUP BY p.cnp, p.nume
        ORDER BY numar_specializari DESC
    ";

    $result_specializare = $conn->query($sql_specializare);
    $specializari = [];

    if ($result_specializare->num_rows > 0) {
        while ($row = $result_specializare->fetch_assoc()) {
            $specializari[] = [
                'pacient_nume' => $row['pacient_nume'],
                'numar_specializari' => $row['numar_specializari']
            ];
        }
    } else {
        $specializari[] = ["message" => "Nu sunt pacienți cu consultații la specializări multiple."];
    }

    $sql_boli = "
        SELECT c.diagnostic, COUNT(DISTINCT p.cnp) AS numar_pacienti
        FROM Consultatie c
        JOIN Pacient p ON c.cnp = p.cnp
        GROUP BY c.diagnostic
    ";

    $result_boli = $conn->query($sql_boli);
    $diagnostice = [];

    if ($result_boli->num_rows > 0) {
        while ($row = $result_boli->fetch_assoc()) {
            $diagnostice[] = [
                'boala' => $row['diagnostic'],
                'numar_pacienti' => $row['numar_pacienti']
            ];
        }
    } else {
        $diagnostice[] = ["message" => "Nu sunt pacienți cu diagnostice."];
    }

    echo json_encode([
        'specializari' => $specializari,
        'diagnostice' => $diagnostice
    ]);
} else {
    echo json_encode(["error" => "Invalid request method. Please use GET."]);
}

$conn->close();
?>