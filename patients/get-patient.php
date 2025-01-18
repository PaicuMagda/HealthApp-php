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
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cnp = isset($_GET['cnp']) ? $conn->real_escape_string($_GET['cnp']) : null;

    if ($cnp) {
        $sql_patient = "
            SELECT * 
            FROM pacient 
            WHERE cnp = ?
        ";
        $stmt_patient = $conn->prepare($sql_patient);
        $stmt_patient->bind_param("s", $cnp);

        if ($stmt_patient->execute()) {
            $result_patient = $stmt_patient->get_result();
            if ($result_patient->num_rows > 0) {
                $patient_data = $result_patient->fetch_assoc();

                $sql_consultations = "
                    SELECT * 
                    FROM consultatie

                    WHERE cnp = ?
                ";
                $stmt_consultations = $conn->prepare($sql_consultations);
                $stmt_consultations->bind_param("s", $cnp);

                if ($stmt_consultations->execute()) {
                    $result_consultations = $stmt_consultations->get_result();
                    $consultations = [];

                    while ($row = $result_consultations->fetch_assoc()) {
                        $consultations[] = $row;
                    }

                    echo json_encode([
                        "success" => true,
                        "patient" => $patient_data,
                        "consultations" => $consultations
                    ]);
                } else {
                    echo json_encode(["error" => "Failed to retrieve consultations: " . $stmt_consultations->error]);
                }

                $stmt_consultations->close();
            } else {
                echo json_encode(["error" => "No patient found with the provided CNP."]);
            }
        } else {
            echo json_encode(["error" => "Failed to retrieve patient: " . $stmt_patient->error]);
        }

        $stmt_patient->close();
    } else {
        echo json_encode(["error" => "CNP parameter is required."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

$conn->close();
?>