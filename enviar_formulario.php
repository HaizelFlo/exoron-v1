<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir PHPMailer (asegúrate de usar la ruta correcta)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreAlias = htmlspecialchars($_POST['nombreAlias'] ?? '');
    $edad = htmlspecialchars($_POST['edad'] ?? '');
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? '');

    if (empty($nombreAlias) || empty($edad) || empty($correo) || empty($mensaje)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Correo no válido."]);
        exit;
    }

    // Configuración de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de GoDaddy
        $mail->isSMTP();
        $mail->Host = 'smtpout.secureserver.net'; // Servidor SMTP de GoDaddy
        $mail->SMTPAuth = true;
        $mail->Username = 'haizel.flores.aguilar@gmail.com'; // Reemplaza con tu correo personal
        $mail->Password = ''; // Contraseña de tu correo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Puerto SMTP para GoDaddy

        // Remitente y destinatario
        $mail->setFrom('delia_hemosa@hotmail.com', 'Prueba'); // Reemplaza con tu correo
        $mail->addAddress('haizel.flores.aguilar@gmail.com'); // Correo donde recibirás las preguntas

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nueva Pregunta Recibida';
        $mail->Body = "
            <h1>Nueva Pregunta</h1>
            <p><strong>Nombre o Alias:</strong> $nombreAlias</p>
            <p><strong>Edad:</strong> $edad</p>
            <p><strong>Correo:</strong> $correo</p>
            <p><strong>Mensaje:</strong></p>
            <p>$mensaje</p>
        ";

        // Enviar correo
        $mail->send();
        echo json_encode(["status" => "success", "message" => "Correo enviado correctamente."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error al enviar el correo: {$mail->ErrorInfo}"]);
    }
}
?>
