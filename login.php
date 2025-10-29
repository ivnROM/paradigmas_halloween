<?php
// Procesa las credenciales del formulario de login. Verifica al usuario contra la base de datos, inicia la sesión y redirige a la página principal o al panel de administración.
session_start();
require_once "includes/db_config.php";

try {
    $pdo = getClientPDO();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = trim($_POST["usuario"]);
        $contrasena = $_POST["contrasena"];

        if (empty($usuario) || empty($contrasena)) {
            header("Location: login.html?error=campos_vacios");
            exit();
        }

        $stmt = $pdo->prepare(
            "SELECT id, nombre, clave FROM usuarios WHERE nombre = ?",
        );
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($contrasena, $user["clave"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["usuario"] = $user["nombre"];

            if ($user["nombre"] == "admin") {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: login.html?error=credenciales_invalidas");
            exit();
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>