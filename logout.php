<?php
// Cierra la sesión del usuario. Destruye la sesión actual y redirige al formulario de login.
session_start();

session_unset();

session_destroy();

header("Location: login.html");
exit();
?>