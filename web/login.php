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
            <h3 class="text-center">Iniciar Sesi&oacute;n</h3>
            <div class="text-center">
                    <img src="assets/img/logoGEOSALUD2.png" alt="navbar brand" class="navbar-brand m-5" height="120">
            </div>
            <!--MENSAJE DE ERROR CON VARIABLE DE SESION-->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo getUrl("Acceso","Acceso","login", false, "ajax")?>" method="post">
                <div class="form-group">
                    <label class=""><b>Documento*</b></label>
                    <input type="text" class="form-control" id="documento" name="documento" minlength="9" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label class=""><b>Contrase&ntilde;a*</b></label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" minlength="8" maxlength="16" required>
                </div>

                <div class="text-center">
                     <button type="submit" class="btn btn-primary" >Entrar</button>
                </div>
                </form>
        </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    
</body>
</html>
