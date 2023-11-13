<?php
session_start();

// Destruye la sesión (cierra la sesión actual)
session_destroy();

// Redirige al usuario a la página de inicio de sesión (login.php)
header("Location: login.php");
exit;
?>
