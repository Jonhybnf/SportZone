<?php
include_once('includes/header.php');

$enviado = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    if ($nombre === "" || $email === "" || $mensaje === "") {
        $error = "Por favor, rellena todos los campos.";
    } else {

        // Enviar email directamente sin variables pro
        mail(
            "jonathanmfulgencio@gmail.com",
            "Tienes un nuevo correo de la web SportZone",
            "Nombre: " . $nombre . "\nEmail: " . $email . "\nMensaje:\n" . $mensaje
        );

        $enviado = true;
    }
}
?>

<section class="contacto-seccion">
    <h1 class="seccion-titulo">Contacto</h1>
    <p class="contacto-sub">¿Tienes alguna duda? Escríbenos.</p>

    <?php if ($enviado){ ?>
        <div class="contacto-exito">
            <p>¡Gracias por tu mensaje! Te responderemos lo antes posible.</p>
        </div>

    <?php }else{ ?>

        <?php if ($error){ ?>
            <div class="contacto-error">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php }; ?>

        <form action="contacto.php" method="POST" class="contacto-form">

            <label>Nombre</label>
            <input type="text" name="nombre" placeholder="Tu nombre">

            <label>Email</label>
            <input type="email" name="email" placeholder="tu@email.com">

            <label>Mensaje</label>
            <textarea name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..."></textarea>

            <button type="submit" class="btn btn-enviar">Enviar</button>
        </form>

    <?php }; ?>
</section>

<?php include_once('includes/footer.php'); ?>
