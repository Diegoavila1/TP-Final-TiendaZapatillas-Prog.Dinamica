<?php
include '../estructura/cabecera.php';
include '../../../config.php';

$session = new Session();
?>

<body>
    <main class="container w-32 h-100 mt-5 pt-5 h-100">
        <?php
        if(isset($_GET['error'])){
            echo '<div class="alert alert-danger" role="alert">
            Usuario o contraseña incorrectos
          </div>';
        }
        ?>
        <section class="login-container bg-form rouded-modify shadow">
            <div class="text-center fs-2 pt-4 pb-4">
                <span>Login</span>
            </div>
            <form class="w-100 d-flex flex-column" action='./action.php' method="post">
                <div class="mt-4 ml-2 mb-2 mr-2">
                    <input type="text" name="usnombre" id="usnombre" class="fancy-input rounded-pill img-input-usuario" placeholder="Usuario">
                </div>
                <div class="mt-4 ml-2 mb-2 mr-2">
                    <input type="text" name="uspass" id="uspass" class="fancy-input rounded-pill img-input-contraseña" placeholder="Contraseña">
                </div>
                <div class="mt-4 ml-2 mb-5 mr-2">
                    <input type="mail" name="usmail" id="usmail" class="fancy-input rounded-pill" placeholder="Mail">
                </div>
                <div class="d-flex w-70 align-self-center mb-2">
                    <input type="submit" name="btnenviar" id="btnenviar" class="btn-enviar rounded-pill" value="Enviar">
                </div>
                <div class="d-flex w-100 h-100 align-content-start justify-content-center mb-5">
                    <span>¿No tenes cuenta?<a href="../register/index.php"> Registrarte</a></span>
                </div>
            </form>
        </section>

    </main>
</body>
</html>

