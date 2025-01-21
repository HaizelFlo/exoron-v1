<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['nombreAlias']);
    $edad = htmlspecialchars($_POST['edad']);
    $correo = htmlspecialchars($_POST['correo']);
    $mensaje = nl2br(htmlspecialchars($_POST['mensaje'])); // nl2br para respetar saltos de l¨ªnea

    $to = "roberto@bespokeadvertising.com.mx"; 
    $subject = "Nueva pregunta";

    // Contenido del correo en HTML
    $body = "
        <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        text-align: center;
                        background-color: #000012;
                        padding: 20px;
                        border-radius: 8px 8px 0 0;
                    }
                    .header img {
                        max-width: 150px;
                    }
                    .header h1 {
                        color: #ffffff;
                        font-size: 24px;
                        margin: 10px 0 0 0;
                    }
                    .content {
                        padding: 20px;
                        text-align: left;
                        color: #333333;
                    }
                    .content p {
                        margin: 5px 0;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 20px;
                        color: #888888;
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <img src='https://bespokeadvertising.com.mx/exoron/assets/images/LOGOCLUB.webp' alt='Eroxon Logo'>
                        <h1>Nueva Pregunta Recibida</h1>
                    </div>
                    <div class='content'>
                        <p><strong>Nombre o Alias:</strong> $nombre</p>
                        <p><strong>Edad:</strong> $edad</p>
                        <p><strong>Correo:</strong> $correo</p>
                        <p><strong>Mensaje:</strong></p>
                        <p>$mensaje</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2025 Eroxon. Todos los derechos reservados.</p>
                    </div>
                </div>
            </body>
        </html>
    ";

    // Encabezados
    $headers = "From: $correo\r\n";
    $headers .= "Reply-To: $correo\r\n";
    $headers .= "CC: hola@bespokeadvertising.com.mx\r\n"; // Copia visible
    $headers .= "BCC: hola@bespokeadvertising.com.mx\r\n"; // Copia oculta
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success", "message" => "Correo enviado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al enviar el correo."]);
    }
}
?>
