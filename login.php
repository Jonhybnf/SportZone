<?php
include('./includes/conexion.php');
session_start();

// Si ya está logeado, redirigimos a index.php
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$error = '';

// Comprobamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['usuario'];
    $clave  = $_POST['clave'];

    if ($nombre == "" || $clave == "") {
        $error = "ERROR CON EL USUARIO Y/O CONTRASEÑA";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $usuario = $stmt->fetch();

        if ($usuario && $clave === $usuario['contrasena']) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol']     = $usuario['rol'];
            header("Location: index.php");
            exit();
        } else {
            $error = "ERROR CON EL USUARIO Y/O CONTRASEÑA";
        }
    }
}
?>

<body>
    <div class="contenedor">
        <h1>🔐 Acceso al sistema</h1>

        <?php if (isset($_GET["redirigido"])) { ?>
            <p class="alerta">Por favor, identifíquese para acceder a esa página.</p>
        <?php } ?>

        <?php if ($error) { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form action="" method="post" class="login-form">
            <div class="form-grupo">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>

            <div class="form-grupo">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" required>
            </div>

            <div class="form-botones">
                <button type="submit" class="btn btn-nuevo">Entrar</button>
                <a href="index.php" class="btn btn-borrar">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>