<?php
header('Content-Type: application/json');

$correo = $_POST['correo'] ?? null;

if (!$correo) {
    echo json_encode(["status" => "error", "message" => "Correo inválido."]);
    exit;
}

$servername = "localhost";
$username = "root"; // Usuario de XAMPP
$password = ""; // Contraseña vacía de XAMPP
$dbname = "faqs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión."]);
    exit;
}

$query_check = "SELECT id FROM suscripciones WHERE correo = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El correo ya está registrado."]);
    exit;
}

$query = "INSERT INTO suscripciones (correo) VALUES (?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $correo);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Correo guardado exitosamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al guardar el correo."]);
}

$stmt->close();
$conn->close();
?>
