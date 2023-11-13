<?php
include 'global/config.php';
include 'global/conexion.php';

// Obtén los valores de búsqueda desde el formulario
$query = isset($_GET['query']) ? $_GET['query'] : "";
$precio_min = isset($_GET['precio_min']) ? $_GET['precio_min'] : "";
$precio_max = isset($_GET['precio_max']) ? $_GET['precio_max'] : "";

include 'carrito.php';
include 'templates/cabecera.php';

if ($mensaje != "") {
    echo '<div class="alert alert-success">' . $mensaje . '<a href="mostrarCarrito.php" class="badge badge-success">Ver Carrito</a></div>';
}
?>
<div class="container">
    <h2>Resultados de la Búsqueda</h2>

    <!-- Aquí puedes mostrar los valores de precio mínimo y máximo seleccionados por el usuario si es necesario -->

    <?php
    // Construye la consulta SQL para buscar productos que coincidan con la búsqueda y el rango de precios
    $sql = "SELECT * FROM tblproductos WHERE (Nombre LIKE :query OR Descripcion LIKE :query) ";

    // Agrega condiciones para el rango de precios si se especifican
    if (!empty($precio_min)) {
        $sql .= "AND Precio >= :precio_min ";
    }
    if (!empty($precio_max)) {
        $sql .= "AND Precio <= :precio_max ";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    
    // Asigna los valores de precio mínimo y máximo si se especificaron
    if (!empty($precio_min)) {
        $stmt->bindValue(':precio_min', $precio_min, PDO::PARAM_INT);
    }
    if (!empty($precio_max)) {
        $stmt->bindValue(':precio_max', $precio_max, PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (count($resultados) > 0) { ?>
        <ul>
            <?php foreach ($resultados as $producto) { ?>
                <li>
                    <h3><?php echo $producto['Nombre']; ?></h3>
                    <img src="<?php echo $producto['Imagen']; ?>" alt="<?php echo $producto['Nombre']; ?>" width="200">
                    <p>Descripción: <?php echo $producto['Descripcion']; ?></p>
                    <p>Precio: $<?php echo $producto['Precio']; ?></p>
                    
                    <!-- Aquí puedes mostrar más detalles del producto si es necesario -->
                    <a href="detalles_producto.php?id=<?php echo $producto['ID']; ?>">Ver detalles</a>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No se encontraron resultados para "<?php echo $query; ?>" y el rango de precios seleccionado.</p>
    <?php } ?>
</div>
<?php include 'templates/pie.php'; ?>
