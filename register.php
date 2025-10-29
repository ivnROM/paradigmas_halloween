<?php
// Procesa el formulario de registro. Valida los datos, verifica que el usuario no exista, hashea la contraseña y crea el nuevo usuario en la base de datos. Inicia sesión automáticamente.
session_start();
require_once "includes/db_config.php";

try {
    $pdo = getAdminPDO();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = trim($_POST["usuario"]);
        $contrasena = $_POST["contrasena"];
        $contrasena_confirmar = $_POST["contrasena_confirmar"];

        if (empty($usuario) || empty($contrasena)) {
            header("Location: register.html?error=campos_vacios");
            exit();
        }

        if (strlen($contrasena) < 6) {
            header("Location: register.html?error=contrasena_corta");
            exit();
        }

        if ($contrasena !== $contrasena_confirmar) {
            header("Location: register.html?error=contrasenas_no_coinciden");
            exit();
        }

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
        $stmt->execute([$usuario]);

        if ($stmt->fetch()) {
            header("Location: register.html?error=usuario_existe");
            exit();
        }
        $clave_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios(nombre, clave) VALUES(?, ?)",
        );
        $stmt->execute([$usuario, $clave_hash]);

        $_SESSION["user_id"] = $pdo->lastInsertId();
        $_SESSION["usuario"] = $usuario;
        header("Location: index.php?registro=exitoso");
        exit();
    }
} catch (PDOException $e) {
    die("Error " . $e->getMessage());
}
?>