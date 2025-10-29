<?php
// Endpoint para obtener y mostrar la imagen de un disfraz desde el campo BLOB de la base de datos. Requiere sesión activa y un ID de disfraz válido.
session_start();
require_once "includes/db_config.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    exit();
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id_disfraz = $_GET["id"];

    try {
        $pdo = getClientPDO();

        $stmt = $pdo->prepare(
            "SELECT foto_blob FROM disfraces WHERE id = ? AND eliminado = 0",
        );
        $stmt->execute([$id_disfraz]);
        $imagen = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($imagen && !empty($imagen["foto_blob"])) {
            header("Content-Type: image/jpeg");

            echo $imagen["foto_blob"];
        } else {
            http_response_code(404);
            echo "Imagen no encontrada.";
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error de base de datos.";
    }
} else {
    http_response_code(400); // Bad Request
    echo "ID inválido.";
}
?>