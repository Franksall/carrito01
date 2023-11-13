<?php
// Incluir archivos de configuración y conexión a la base de datos
include 'global/config.php';
include 'global/conexion.php';

// Verificar si se proporcionó un ID de producto válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtener el ID del producto desde la URL
    $idProducto = $_GET['id'];

    // Consultar la base de datos para obtener los detalles del producto
    $sql = "SELECT * FROM tblproductos WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comprobar si se encontró el producto
        if ($producto) {
            // Incluir la cabecera
            include 'templates/cabecera.php';

            // Mostrar los detalles del producto
            echo '<div class="container">';
            echo '<h2>Detalles del Producto</h2>';
            echo '<h3>' . $producto['Nombre'] . '</h3>';
            echo '<p>Descripción: ' . $producto['Descripcion'] . '</p>';
            echo '<p>Precio: $' . $producto['Precio'] . '</p>';
            // Puedes mostrar más detalles del producto aquí

            // Botón para agregar al carrito
            echo '<form action="mostrarCarrito.php" method="post">';
            echo '<input type="hidden" name="id" value="' . openssl_encrypt($producto['ID'], COD, KEY) . '">';
            echo '<input type="hidden" name="nombre" value="' . openssl_encrypt($producto['Nombre'], COD, KEY) . '">';
            echo '<input type="hidden" name="unidad" value="' . openssl_encrypt($producto['Unidad'], COD, KEY) . '">';
            echo '<input type="hidden" name="precio" value="' . openssl_encrypt($producto['Precio'], COD, KEY) . '">';
            echo '<input type="hidden" name="cantidad" value="' . openssl_encrypt(1, COD, KEY) . '">';
            echo '<button class="btn btn-primary" type="submit" name="btnAccion" value="Agregar">Agregar al carrito</button>';
            echo '</form>';

            // Cerrar la etiqueta del contenedor
            echo '</div>';

            // Incluir el pie de página
            include 'templates/pie.php';
        } else {
            // Producto no encontrado, puedes mostrar un mensaje de error o redirigir
            header("Location: index.php");
            exit;
        }
    } else {
        // Error al ejecutar la consulta, muestra un mensaje de error
        echo "Error al consultar la base de datos.";
    }
} else {
    // ID de producto no proporcionado o no válido en la URL
    header("Location: index.php");
    exit;
}
?>
