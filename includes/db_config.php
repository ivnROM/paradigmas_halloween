<?php
// Configuración de la conexión a la base de datos. Define dos funciones para obtener conexiones PDO con diferentes privilegios (cliente y administrador).
$host = "localhost";
$dbname = "halloweendb";
$charset = "utf8";

$user_client = "userclient";
$pass_client = "userpass";

$user_admin = "useradmin";
$pass_admin = "admin";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function getClientPDO() {
    global $host, $dbname, $charset, $user_client, $pass_client, $options;
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    return new PDO($dsn, $user_client, $pass_client, $options);
}

function getAdminPDO() {
    global $host, $dbname, $charset, $user_admin, $pass_admin, $options;
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    return new PDO($dsn, $user_admin, $pass_admin, $options);
}
?>