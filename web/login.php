<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <link rel="stylesheet" href="../../web/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../web/assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="../../web/assets/css/fonts.min.css">
    
</head>

<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Iniciar Sesión</h3>
            <h3 class="text-center">Iniciar Sesi&oacute;n</h3>
            <div class="text-center">
                    <img src="assets/img/logoGEOSALUD.png" alt="navbar brand" class="navbar-brand m-2" height="120">
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

            <form>
                <div class="form-group">
                    <label class="placeholder"><b>Usuario</b></label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label class="placeholder"><b>Contraseña</b></label>
                    <input type="password" class="form-control">
                </div>

                <button class="btn btn-primary btn-block mt-3">Entrar</button>
            </form>
        </div>
    </div>

    <script src="../../web/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../../web/assets/js/core/popper.min.js"></script>
    <script src="../../web/assets/js/core/bootstrap.min.js"></script>
</body>
</html>
