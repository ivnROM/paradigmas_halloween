<?php
// Script para verificar la sesión de usuario. Inicia la sesión y redirige a login.html si el usuario no está autenticado.
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
?>