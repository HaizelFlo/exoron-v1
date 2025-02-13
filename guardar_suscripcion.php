<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Correo inválido."]);
        exit;
    }

    // Conexión a la base de datos
    $host = "localhost";
    $db = "news";
    $user = "devbespoke";
    $pass = "admin_bespoke";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica si el correo ya existe
        $query = $conn->prepare("SELECT id FROM newsletter WHERE correo = :correo");
        $query->bindParam(":correo", $correo);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "El correo ya está registrado."]);
            exit;
        }

        // Insertar el correo con estado "pendiente"
        $insert = $conn->prepare("INSERT INTO newsletter (correo, status) VALUES (:correo, 'pendiente')");
        $insert->bindParam(":correo", $correo);
        $insert->execute();

        echo json_encode(["status" => "success", "message" => "Correo guardado correctamente. Se enviará pronto."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
    }
}
?>
