<?php
// Panel de administración para agregar nuevos disfraces. Verifica la sesión de administrador.
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["usuario"] !== "admin") {
    header("Location: login.html");
    exit();
}

$mensaje = "";
if (isset($_GET["exito"])) {
    $mensaje = "¡Disfraz agregado exitosamente!";
} elseif (isset($_GET["error"])) {
    if ($_GET["error"] == "campos_vacios") {
        $mensaje = "Error: Todos los campos son obligatorios.";
    } elseif ($_GET["error"] == "db") {
        $mensaje = "Error al conectar o insertar en la base de datos.";
    } elseif ($_GET["error"] == "no_foto") {
        $mensaje = "Error: No se subió ningún archivo de foto.";
    } elseif ($_GET["error"] == "foto_nombre_largo") {
        $mensaje =
            "Error: El nombre del archivo de foto es muy largo (máx 20 chars).";
    }
}
?>

<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <nav class="navbar">
            <a href="index.php">Inicio (Votación)</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>

        <div class="hero">
            <form
                class="login-container"
                action="agregar_disfraz.php"
                method="post"
                enctype="multipart/form-data"
            >
                <h2>Agregar Nuevo Disfraz</h2>

                <label for="nombre">Nombre del Disfraz</label>
                <input type="text" id="nombre" name="nombre" required />

                <label for="descripcion">Descripción</label>
                <input
                    type="text"
                    id="descripcion"
                    name="descripcion"
                    required
                />

                <label for="foto_disfraz">Foto (para el BLOB)</label>
                <input
                    type="file"
                    id="foto_disfraz"
                    name="foto_disfraz"
                    accept="image/jpeg, image/png"
                    required
                />

                <button type="submit">Agregar Disfraz</button>

                <?php if (!empty($mensaje)): ?>
                <p style="color: white; margin-top: 15px;">
                    <?php echo $mensaje; ?>
                </p>
                <?php endif; ?>
            </form>
        </div>

        <footer></footer>
    </body>
</html>