<?php
session_start();

include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';

// Obtén la categoría seleccionada desde el formulario de búsqueda
if (isset($_GET['categoria'])) {
    $categoriaSeleccionada = $_GET['categoria'];
} else {
    // Si no se seleccionó ninguna categoría, muestra un mensaje de error
    echo '<div class="alert alert-danger">Por favor, selecciona una categoría.</div>';
    return;
}

// Construye la consulta SQL para obtener los productos de la categoría seleccionada
$sql = "SELECT * FROM `tblproductos`p INNER JOIN `categorias` c ON p.id_categorias = c.id_categoria WHERE categoria = :categoria";
// Ejecuta la consulta SQL y almacena los resultados en una variable
$sentencia = $pdo->prepare($sql);
$sentencia->bindParam(':categoria', $categoriaSeleccionada, PDO::PARAM_STR);
$sentencia->execute();
$productosCategoria = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Muestra los resultados en la página
if (count($productosCategoria) > 0) {
    // Muestra los productos de la categoría
    echo '<div class="container">';
    echo '<h2>Productos de la categoría: ' . $categoriaSeleccionada . '</h2>';
    echo '<div class="row">';

    foreach ($productosCategoria as $producto) {
        echo '<div class="col-3">';
        echo '<div class="card">';
        echo '<img title="' . $producto['Nombre'] . '" alt="' . $producto['Nombre'] . '" class="card-img-top" src="' . $producto['Imagen'] . '" data-toggle="popover" data-trigger="hover" data-content="' . $producto['Descripcion'] . '" height="317px">';
        echo '<div class="card-body">';
        echo '<span>' . $producto['Nombre'] . '</span>';
        echo '<h5 class="card-title">$ ' . $producto['Precio'] . '</h5>';
        echo '<p class="card-text">Categoría: ' . $producto['categoria'] . '</p>';
        echo '<p class="card-text">Descripción: ' . $producto['Descripcion'] . '</p>';
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="id" id="id" value="' . openssl_encrypt($producto['ID'], COD, KEY) . '">';
        echo '<input type="hidden" name="nombre" id="nombre" value="' . openssl_encrypt($producto['Nombre'], COD, KEY) . '">';
        echo '<input type="hidden" name="unidad" id="unidad" value="' . openssl_encrypt($producto['Unidad'], COD, KEY) . '">';
        echo '<input type="hidden" name="precio" id="precio" value="' . openssl_encrypt($producto['Precio'], COD, KEY) . '">';
        echo '<input type="hidden" name="cantidad" id="cantidad" value="' . openssl_encrypt(1, COD, KEY) . '">';
        echo '<button class="btn btn-primary" name="btnAccion" value="Agregar" type="submit">Agregar al carrito</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    

    echo '</div>';
    echo '</div>';
} else {
    // No se encontraron productos en la categoría seleccionada
    echo '<div class="container">';
    echo '<h2>No se encontraron productos en la categoría: ' . $categoriaSeleccionada . '</h2>';
    echo '</div>';
}

include 'templates/pie.php';
?>
