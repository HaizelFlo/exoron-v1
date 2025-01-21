<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faqs";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT p.id AS pregunta_id, p.nombre_alias, p.edad, p.mensaje AS pregunta_mensaje, 
        c.id AS comentario_id, c.nombre_aliasComent, c.correoComent, c.mensaje AS comentario_mensaje 
        FROM preguntas p 
        LEFT JOIN comentarios c ON p.id = c.pregunta_id 
        ORDER BY p.id, c.id";

$result = $conn->query($sql);

$preguntas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pregunta_id = $row['pregunta_id'];
        
        if (!isset($preguntas[$pregunta_id])) {
            $preguntas[$pregunta_id] = [
                'id' => $pregunta_id,
                'nombre_alias' => $row['nombre_alias'],
                'edad' => $row['edad'],
                'mensaje' => $row['pregunta_mensaje'],
                'comentarios' => []
            ];
        }

        if (!empty($row['comentario_id'])) {
            $preguntas[$pregunta_id]['comentarios'][] = [
                'id' => $row['comentario_id'],
                'nombre_aliasComent' => $row['nombre_aliasComent'],
                'correoComent' => $row['correoComent'],
                'mensaje' => $row['comentario_mensaje']
            ];
        }
    }
}

echo json_encode(array_values($preguntas));

$conn->close();
?>
