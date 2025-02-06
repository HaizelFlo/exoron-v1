<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    // Validar que el correo sea válido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Correo inválido."]);
        exit;
    }

    // Guardar en base de datos (opcional)
    $host = "localhost";
    $db = "faqs";
    $user = "root";
    $pass = "";

    try {
        // Conectar a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si el correo ya existe
        $query = $conn->prepare("SELECT id FROM suscripciones WHERE correo = :correo");
        $query->bindParam(":correo", $correo);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "El correo ya está registrado."]);
            exit;
        }

        // Insertar el correo
        $insert = $conn->prepare("INSERT INTO suscripciones (correo) VALUES (:correo)");
        $insert->bindParam(":correo", $correo);
        $insert->execute();

        // Leer el contenido de `cupon.html`
        $cuponPath = __DIR__ . "/cupon.html"; // Asegúrate de que `cupon.html` esté en el mismo directorio
        if (!file_exists($cuponPath)) {
            echo json_encode(["status" => "error", "message" => "No se pudo encontrar el archivo del cupón."]);
            exit;
        }

        $cuponHtml = file_get_contents($cuponPath);

        // Configuración del correo
        $to = $correo;
        $subject = "¡Gracias por suscribirte! Aquí está tu cupón";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@localhost\r\n";

        // Enviar el correo
        if (mail($to, $subject, $cuponHtml, $headers)) {
            echo json_encode(["status" => "success", "message" => "¡Gracias por suscribirte! Revisa tu correo para descargar tu cupón."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo enviar el correo."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
    }
}
?>
