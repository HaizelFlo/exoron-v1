<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$host = "localhost";
$db = "news";
$user = "devbespoke";
$pass = "admin_bespoke";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Selecciona correos con estado "pendiente"
    $query = $conn->prepare("SELECT id, correo FROM newsletter WHERE status = 'pendiente'");
    $query->execute();
    $correos = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($correos) === 0) {
        echo "No hay correos pendientes.";
        exit;
    }

    foreach ($correos as $registro) {
        $correo = $registro['correo'];
        $id = $registro['id'];

        // Cargar el contenido del cupón
        $cuponPath = __DIR__ . "/cupon.html";
        if (!file_exists($cuponPath)) {
            echo "Error: No se encontró el archivo del cupón.";
            continue;
        }
        $cuponHtml = file_get_contents($cuponPath);

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hola@bespokeadvertising.com.mx';
            $mail->Password = 'Bespoke_2025';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('hola@bespokeadvertising.com.mx', 'Club Eroxon');
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = '¡Gracias por suscribirte!';
            $mail->Body = $cuponHtml;

            if ($mail->send()) {
                // Si el correo se envió correctamente, actualizar el estado a "enviado"
                $update = $conn->prepare("UPDATE newsletter SET status = 'enviado' WHERE id = :id");
                $update->bindParam(":id", $id);
                $update->execute();
                echo "Correo enviado a $correo\n";
            } else {
                echo "Error al enviar a $correo: " . $mail->ErrorInfo . "\n";
            }
        } catch (Exception $e) {
            echo "Error en PHPMailer: " . $e->getMessage() . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}

?>
