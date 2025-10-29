<?php
// Página principal que muestra los disfraces disponibles para votar. Requiere sesión activa, obtiene los disfraces de la base de datos y los presenta en tarjetas con su información y un botón para votar.
require_once "includes/verificar_sesion.php";
require_once "includes/db_config.php";

$disfraces = [];

try {
    $pdo = getClientPDO();

    $stmt = $pdo->prepare(
        "SELECT id, nombre, descripcion, votos FROM disfraces WHERE eliminado = 0 ORDER BY votos DESC",
    );
    $stmt->execute();
} catch (PDOException $e) {
    $error_db = "Error al conectar con la base de datos: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Halloween</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <nav class="navbar">
            <a href="#">Inicio</a>
            <a href="logout.php">Cerrar Sesión</a>
            <?php if (
                isset($_SESSION["usuario"]) &&
                $_SESSION["usuario"] == "admin"
            ) {
                echo '<a href="admin.php">Admin Dashboard</a>';
            } ?>
        </nav>

        <div class="hero">
            <?php if (isset($error_db)): ?>
            <p style="color: red;"><?php echo $error_db; ?></p>
            <?php elseif ($stmt->rowCount() == 0): ?>
            <p style="color: black; font-size: 1.2rem;">
                Todavía no hay disfraces. El admin debe agregar algunos!
            </p>
            <?php else: ?>
            <?php while ($disfraz = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="costume-card">
                <h2><?php echo htmlspecialchars($disfraz["nombre"]); ?></h2>

                <img
                    src="get_image.php?id=<?php echo $disfraz["id"]; ?>"
                    alt="<?php echo htmlspecialchars($disfraz["nombre"]); ?>"
                    style="
                    width: 200px;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 10px;
                    "
                />

                <p><?php echo htmlspecialchars($disfraz["descripcion"]); ?></p>
                <p class="votos-conteo" style="font-weight: bold; font-size: 1.1rem;">
                    Votos: <?php echo $disfraz["votos"]; ?>
                </p>

                <form
                    class="votar-form"
                    action="votar.php"
                    method="post"
                    style="margin-top: 10px;"
                >

                    <input
                        type="hidden"
                        name="id_disfraz"
                        value="<?php echo $disfraz["id"]; ?>"
                    />
                    <button type="submit">Votar</button>
                </form>
            </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <footer></footer>
        <script src="js/index.js"></script>
    </body>
</html>
