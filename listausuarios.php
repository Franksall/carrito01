<?php
include 'global/config.php';
include 'global/conexion.php';

// Realiza una consulta SQL para obtener los datos de los usuarios
$query = "SELECT username, password , email FROM usuarios";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
</head>
<body>
    <h1>Lista de Usuarios</h1>
    
    <!-- Genera una tabla HTML para mostrar los datos de los usuarios -->
    <table border="1">
        <tr>
            <th>Nombre de Usuario</th>
            <th>Correo Electrónico</th>
        </tr>
        <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td><?php echo $usuario['username']; ?></td>
                <td><?php echo $usuario['password']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
