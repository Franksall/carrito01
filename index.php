<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Título</title>
    <link rel="stylesheet" href="styles.css"> <link rel="stylesheet" href="styles.css">

</head>

<body>

<?php

session_start();

include 'global/config.php';
include 'global/conexion.php';

include 'carrito.php';
include 'templates/cabecera.php';

    // Verifica si el usuario ha iniciado sesión
    if (isset($_SESSION['usuario'])) {
        // El usuario ha iniciado sesión, permitir agregar productos al carrito
        if ($mensaje != "") {
            echo '<div class="alert alert-success">' . $mensaje . '<a href="mostrarCarrito.php" class="badge badge-success">Ver Carrito</a></div>';
        }
        // Resto del contenido de la página, incluyendo la lista de productos y el formulario de búsqueda
    ?>
        <!-- Formulario de búsqueda -->
        <form method="GET" action="resultados_busqueda.php" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Buscar productos">
                <input type="number" class="form-control" name="precio_min" placeholder="Precio mínimo">
                <input type="number" class="form-control" name="precio_max" placeholder="Precio máximo">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        <form method="GET" action="resultados_categoria.php" class="mb-4">
            <div class="input-group">
                <select name="categoria" class="form-control">
                    <option value="Todas las categorías">Todas las categorías</option> <!-- Cambia el valor aquí -->
                    <?php
                    // Obtener la lista de categorías disponibles desde la base de datos
                    $sql = "SELECT * FROM `categorias`";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($categorias as $categoria) {
                        echo '<option value="' . strtolower($categoria['categoria']) . '">' . $categoria['categoria'] . '</option>';
                    }
                    ?>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit">Buscar por Categoría</button>
                </div>
            </div>
        </form>

        <br>

        <div class="row">
            <?php
            // Consulta SQL para obtener todos los productos con su información de categoría
            $sql = "SELECT p.*, c.categoria FROM `tblproductos` p 
                INNER JOIN `categorias` c ON p.id_categorias = c.id_categoria";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute();
            $listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            // Verifica si se encontraron productos
            if (count($listaProductos) > 0) {
                foreach ($listaProductos as $producto) { ?>
                    <div class="col-3">
                        <div class="card">
                            <img title="<?php echo $producto['Nombre']; ?>" alt="<?php echo $producto['Nombre']; ?>" class="card-img-top" src="<?php echo $producto['Imagen']; ?>" data-toggle="popover" data-trigger="hover" data-content="<?php echo $producto['Descripcion']; ?>" height="317px">
                            <div class="card-body">
                                <span><?php echo $producto['Nombre']; ?></span>
                                <h5 class="card-title">$ <?php echo $producto['Precio']; ?></h5>
                                <p class="card-text">Categoría: <?php echo $producto['categoria']; ?></p>
                                <p class="card-text">Descripción: <?php echo $producto['Descripcion']; ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'], COD, KEY); ?>">
                                    <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['Nombre'], COD, KEY); ?>">
                                    <input type="hidden" name="unidad" id="unidad" value="<?php echo openssl_encrypt($producto['Unidad'], COD, KEY); ?>">
                                    <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['Precio'], COD, KEY); ?>">
                                    <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1, COD, KEY); ?>">

                                    <button class="btn btn-primary" name="btnAccion" value="Agregar" type="submit">Agregar al carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-12">
                    <p>No se encontraron productos.</p>
                </div>
            <?php } ?>
        </div>
        <script>
            $(function() {
                $('[data-toggle="popover"]').popover()
            })
        </script>

    <?php
    } else {
        // El usuario no ha iniciado sesión, muestra un mensaje o redirige a la página de inicio de sesión
        echo '<div class="alert alert-danger">Debes iniciar sesión para agregar productos al carrito.</div>';
        // Puedes agregar aquí un enlace a tu página de inicio de sesión
    ?>
        <!-- Botón de inicio de sesión -->
        <div class="text-center">
            <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        </div>
    <?php
    }

    include 'templates/pie.php';
    ?>
    
    </body>
    </html>