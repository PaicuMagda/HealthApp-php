<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexiune eșuată: " . $conn->connect_error]));
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $role = $data['role'] ?? 'doctor';

    if (!$username || !$email || !$password) {
        echo json_encode(["error" => "Toate câmpurile sunt obligatorii!"]);
        exit;
    }

    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $checkEmailSql = "SELECT * FROM doctor WHERE email = '$email'";
    $result = $conn->query($checkEmailSql);

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Acest email este deja utilizat!"]);
        exit;
    }

    $sql = "INSERT INTO doctor (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Contul a fost creat cu succes!"]);
    } else {
        echo json_encode(["error" => "Eroare: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Metodă HTTP invalidă."]);
}

$conn->close();
