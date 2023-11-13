<?php
$servidor = "mysql:dbname=" . BD . ";host=" . SERVIDOR;

try {
    $pdo = new PDO(
        $servidor,
        USUARIO,
        PASSWORD,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
    
    // Establecer el modo de error de PDO a excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si quieres mostrar un mensaje de éxito, puedes hacerlo aquí
    // echo "<script>alert('Conectado...')</script>";
} catch (PDOException $e) {
    // En caso de error, muestra el mensaje de error y detén la ejecución del script
    die("Error de conexión: " . $e->getMessage());
}
?>
