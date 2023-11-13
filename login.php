<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario'])) {
    // Si el usuario ya está autenticado, redirige a la página principal o a otra página de tu elección.
    header("Location: index.php");
    exit;
}

// Verifica si se envió el formulario de inicio de sesión.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta a la base de datos (debes configurar tus propios datos de conexión).
    include 'global/config.php';
    include 'global/conexion.php';

    // Obtiene las credenciales del formulario.
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica las credenciales en la base de datos.
    $query = "SELECT * FROM usuarios WHERE username = ? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Las credenciales son correctas. Inicia la sesión.
        $_SESSION['usuario'] = $user['username'];
        $_SESSION['id_usuario'] = $user['id'];
        // Redirige al usuario a la página principal o a otra página de tu elección.
        header("Location: index.php");
        exit;
    } else {
        // Las credenciales son incorrectas. Muestra un mensaje de error.
        $error = "Usuario o contraseña incorrectos.";
    }
}

include 'templates/cabecera.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Tienda</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Agrega tus estilos CSS aquí -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: linear-gradient(#141e30, #243b55);
        }

        .login-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            padding: 40px;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, .5);
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0, 0, 0, .6);
            border-radius: 10px;
        }

        .login-box h2 {
            margin: 0 0 30px;
            padding: 0;
            color: #fff;
            text-align: center;
        }

        .login-box .user-box {
            position: relative;
        }

        .login-box .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            margin-bottom: 30px;
            border: none;
            border-bottom: 1px solid #fff;
            outline: none;
            background: transparent;
        }

        .login-box .user-box label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: .5s;
        }

        .login-box .user-box input:focus ~ label,
        .login-box .user-box input:valid ~ label {
            top: -20px;
            left: 0;
            color: #03e9f4;
            font-size: 12px;
        }

        .login-box form a {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            color: #03e9f4;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            transition: .5s;
            margin-top: 40px;
            letter-spacing: 4px;
        }

        .login-box a:hover {
            background: #03e9f4;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px #03e9f4, 0 0 25px #03e9f4, 0 0 50px #03e9f4, 0 0 100px #03e9f4;
        }

        .login-box a span {
            position: absolute;
            display: block;
        }

        .login-box a span:nth-child(1) {
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #03e9f4);
            animation: btn-anim1 1s linear infinite;
        }

        @keyframes btn-anim1 {
            0% {
                left: -100%;
            }
            50%, 100% {
                left: 100%;
            }
        }

        .login-box a span:nth-child(2) {
            top: -100%;
            right: 0;
            width: 2px;
            height: 100%;
            background: linear-gradient(180deg, transparent, #03e9f4);
            animation: btn-anim2 1s linear infinite;
            animation-delay: .25s;
        }

        @keyframes btn-anim2 {
            0% {
                top: -100%;
            }
            50%, 100% {
                top: 100%;
            }
        }

        .login-box a span:nth-child(3) {
            bottom: 0;
            right: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(270deg, transparent, #03e9f4);
            animation: btn-anim3 1s linear infinite;
            animation-delay: .5s;
        }

        @keyframes btn-anim3 {
            0% {
                right: -100%;
            }
            50%, 100% {
                right: 100%;
            }
        }

        .login-box a span:nth-child(4) {
            bottom: -100%;
            left: 0;
            width: 2px;
            height: 100%;
            background: linear-gradient(360deg, transparent, #03e9f4);
            animation: btn-anim4 1s linear infinite;
            animation-delay: .75s;
        }

        @keyframes btn-anim4 {
            0% {
                bottom: -100%;
            }
            50%, 100% {
                bottom: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST">
            <div class="user-box">
                <input type="text" class="form-control" name="username" required>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" class="form-control" name="password" required>
                <label>Password</label>
            </div>
            <a href="#">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <button type="submit" class="btn btn-primary">Submit</button>
                
            </a>
            
            <a href="registro.php" class="btn btn-primary" >Registrarse</a>
            <a href="recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
           
        </form>
    </div>
</div>
</body>
</html>
<?php  ?>
