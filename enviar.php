<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar el campo honeypot
    if (!empty($_POST["website"])) {
        echo json_encode(["success" => false, "message" => "Detección de spam."]);
        exit;
    }

    // Sanitizar datos
    $nombre = htmlspecialchars($_POST["name"] ?? '');
    $email = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars($_POST["message"] ?? '');

    // Validación básica
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Configuración del correo
    $destino = "tucorreo@ejemplo.com"; // ← CAMBIA ESTO POR TU CORREO REAL
    $asunto = "Nuevo mensaje desde el formulario web";
    $contenido = "Nombre: $nombre\nCorreo: $email\nMensaje:\n$mensaje";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Enviar correo
    if (mail($destino, $asunto, $contenido, $headers)) {
        echo json_encode(["success" => true, "message" => "Mensaje enviado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo enviar el mensaje."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
