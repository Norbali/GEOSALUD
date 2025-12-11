<?php 

    include_once '../lib/helpers.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="assets/css/fonts.min.css">
    
</head>

<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Iniciar Sesión</h3>
            <div class="text-center">
                <a href="index.html" class="logo">
                    <img src="assets/img/logoGEOSALUD.png" alt="navbar brand" class="navbar-brand m-5" height="120">
                </a>
            </div>

            <form actions="<?php echo getUrl("Acceso","Acceso","login", false, "ajax")?>" method="post">
                <div class="form-group">
                    <label class=""><b>Documento</b></label>
                    <input type="number" class="form-control" id="documento" name="documento">
                </div>

                <div class="form-group">
                    <label class=""><b>Contraseña</b></label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña">
                </div>

                <?php
                    if(isset($_SESSION['error'])){
                        echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                        unset($_SESSION['error']);
                    }
                ?>

                <div class="text-center">
                     <button class="btn btn-primary btn-block mt-3">Entrar</button>
                </div>
                </form>
        </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>
