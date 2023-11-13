<?php
include 'global/config.php';
include 'global/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el correo electrónico y la nueva contraseña del formulario.
    $email = $_POST['email'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verifica si el correo electrónico existe en la base de datos.
    $query = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Actualiza la contraseña del usuario.
        $updateQuery = "UPDATE usuarios SET password = ? WHERE email = ?";
        $updateStmt = $pdo->prepare($updateQuery);

        if ($updateStmt->execute([$newPassword, $email])) {
            // Notifica al usuario que la contraseña se ha restablecido con éxito.
            echo "Contraseña restablecida con éxito. Puedes iniciar sesión con tu nueva contraseña.";
        } else {
            echo "Error al restablecer la contraseña.";
        }
    } else {
        echo "Correo electrónico no encontrado en la base de datos.";
    }
}
?>
