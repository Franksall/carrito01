<?php
// datos_usuario.php

// Incluye el archivo de configuración y conexión a la base de datos
include 'global/config.php';
include 'global/conexion.php';

// Incluye la cabecera
include 'templates/cabecera.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirige a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit;
}

// Obtiene el nombre de usuario de la sesión
$usuario = $_SESSION['usuario'];

// Realiza una consulta para obtener los datos del usuario desde la base de datos
$query = "SELECT username, email, direccion, telefono FROM usuarios WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$usuario]);
$datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica si se obtuvieron resultados
if (!$datosUsuario) {
    echo "No se encontraron datos para el usuario.";
} else {
    // Incluye el HTML y CSS para mostrar los datos del usuario
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
        <title>My Resume</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>

    <body>
        <div class="container">
            <div class="sidebar">
                <div class="sidebar-top">
                    <img class="profile-image" src="https://farm1.staticflickr.com/366/32188572306_843f5cbb90_b.jpg" />
                    <div class="profile-basic">
                        <H1 class="key">NOMBRE : </H1>
                        <p class="name"><?php echo $datosUsuario['username']; ?></p>
         
                    </div>
                </div>
                
                <div class="profile-info">
                    <H1 class="key">TELEFONO : </H1>
                    <p class="value"><?php echo $datosUsuario['telefono']; ?></p>
                </div>
                <div class="profile-info">
                    <H1 class="key">Email : </H1>
                    <p class="value"><?php echo $datosUsuario['email']; ?></p>
                </div>
                <div class="profile-info">
                    <H1 class="key">DIRECCION : </H1>
                    <p class="value"><?php echo $datosUsuario['direccion']; ?></p>
                </div>
                <div class="profile-info">
                    <a class="social-media" href="https://www.facebook.com/profile.php?id=100074492011264" target="_blank">Facebook</a>
                </div>
            </div>
            <div class="content">
                <div class="work-experience">
                    <h3><p style="text-align:center">KEEP SMILE IN YOUR LIFE.</p></h3>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
}

// Incluye el pie de página
include 'templates/pie.php';
?>
