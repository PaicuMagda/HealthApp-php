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
        SELECT d.specializare, COUNT(DISTINCT p.id) AS numar_pacienti
        FROM Doctor d
        JOIN Pacient p ON p.doctor_id = d.id
        JOIN Consultatie c ON c.cnp = p.cnp
        GROUP BY d.specializare
    ";

    $result_specializare = $conn->query($sql_specializare);
    $specializari = [];

    if ($result_specializare->num_rows > 0) {
        while ($row = $result_specializare->fetch_assoc()) {
            $specializari[] = [
                'specializare' => $row['specializare'],
                'numar_pacienti' => $row['numar_pacienti']
            ];
        }
    } else {
        $specializari[] = ["message" => "Nu sunt pacienți pe specialități."];
    }

    $sql_boli = "
        SELECT c.diagnostic, COUNT(DISTINCT p.id) AS numar_pacienti
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