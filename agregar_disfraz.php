<?php
// Procesa el formulario de `admin.php` para agregar un nuevo disfraz. Valida los datos, maneja la subida de la imagen (BLOB) y la inserta en la base de datos.
session_start();
require_once "includes/db_config.php";

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["usuario"] !== "admin" ||
    $_SERVER["REQUEST_METHOD"] !== "POST"
) {
    header("Location: login.html");
    exit();
}

$nombre_disfraz = trim($_POST["nombre"]);
$descripcion_disfraz = trim($_POST["descripcion"]);

if (empty($nombre_disfraz) || empty($descripcion_disfraz)) {
    header("Location: admin.php?error=campos_vacios");
    exit();
}

if (isset($_FILES["foto_disfraz"]) && $_FILES["foto_disfraz"]["error"] == 0) {
    $foto_contenido_blob = file_get_contents(
        $_FILES["foto_disfraz"]["tmp_name"],
    );

    $foto_nombre = basename($_FILES["foto_disfraz"]["name"]);

    if (strlen($foto_nombre) > 20) {
        header("Location: admin.php?error=foto_nombre_largo");
        exit();
    }
} else {
    header("Location: admin.php?error=no_foto");
    exit();
}

try {
    $pdo = getAdminPDO();

    $stmt = $pdo->prepare(
        "INSERT INTO disfraces(nombre, descripcion, votos, foto, foto_blob)
         VALUES(?, ?, 0, ?, ?)",
    );

    $stmt->execute([
        $nombre_disfraz,
        $descripcion_disfraz,
        $foto_nombre,
        $foto_contenido_blob,
    ]);

    header("Location: admin.php?exito=1");
    exit();
} catch (PDOException $e) {
    // die("Error: " . $e->getMessage()); // Descomentar para depurar
    header("Location: admin.php?error=db");
    exit();
}
?>
