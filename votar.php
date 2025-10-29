<?php
// Procesa un voto para un disfraz. Utiliza transacciones para registrar el voto y actualizar el conteo, previniendo votos duplicados. Devuelve una respuesta JSON para ser consumida por AJAX.
require_once "includes/verificar_sesion.php";
require_once "includes/db_config.php";

header("Content-Type: application/json");

if (
    $_SERVER["REQUEST_METHOD"] !== "POST" ||
    !isset($_POST["id_disfraz"]) ||
    !is_numeric($_POST["id_disfraz"])
) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Solicitud inválida."]);
    exit();
}

$id_usuario = $_SESSION["user_id"];
$id_disfraz = $_POST["id_disfraz"];

try {
    $pdo = getAdminPDO();
    $pdo->beginTransaction();

    $stmt_check = $pdo->prepare(
        "SELECT id FROM votos WHERE id_usuario = ? AND id_disfraz = ?",
    );
    $stmt_check->execute([$id_usuario, $id_disfraz]);

    if ($stmt_check->fetch()) {
        $pdo->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => "Error: Ya votaste por este disfraz.",
        ]);
        exit();
    }

    $stmt_insert_voto = $pdo->prepare(
        "INSERT INTO votos (id_usuario, id_disfraz) VALUES (?, ?)",
    );
    $stmt_insert_voto->execute([$id_usuario, $id_disfraz]);

    $stmt_update_conteo = $pdo->prepare(
        "UPDATE disfraces SET votos = votos + 1 WHERE id = ?",
    );
    $stmt_update_conteo->execute([$id_disfraz]);

    $stmt_get_votos = $pdo->prepare("SELECT votos FROM disfraces WHERE id = ?");
    $stmt_get_votos->execute([$id_disfraz]);
    $nuevos_votos = $stmt_get_votos->fetchColumn();

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "¡Voto registrado exitosamente!",
        "nuevos_votos" => $nuevos_votos,
    ]);
    exit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "status" => "error",
        "message" => "Error en la base de datos.",
    ]);
    exit();
}
?>
