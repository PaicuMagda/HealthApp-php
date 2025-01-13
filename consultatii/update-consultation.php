<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

// Conexiunea la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Eroare la conectarea la baza de date: " . $conn->connect_error
    ]));
}

$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->nr_consultatie) &&
    isset($data->diagnostic) &&
    isset($data->data_consultatie) &&
    isset($data->medicamentatie)
) {
    $nr_consultatie = $data->nr_consultatie;
    $diagnostic = $data->diagnostic;
    $data_consultatie = $data->data_consultatie;
    $medicamentatie = $data->medicamentatie;

    $sql = "UPDATE consultatie 
            SET diagnostic = ?, data_consultatie = ?, medicamentatie = ? 
            WHERE nr_consultatie = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $diagnostic, $data_consultatie, $medicamentatie, $nr_consultatie);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Consultația a fost actualizată cu succes."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Eroare la actualizarea consultației: " . $stmt->error
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Eroare la pregătirea interogării: " . $conn->error
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Datele necesare nu au fost furnizate."
    ]);
}

$conn->close();
?>