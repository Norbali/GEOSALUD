<style>
.main-title {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #1f2937;
        }
</style>

<?php 
    include_once '../lib/helpers.php';
?>
    <?php if (!empty($_SESSION['success'])){ ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php } ?>

    <?php if (!empty($_SESSION['errors'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php } ?>
    <div style="position: relative; top: -70px;">

           <h1 class="main-title">Registro de Usuarios</h1>
 
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <form id="registroForm" class="" action="<?php echo getUrl("RegistroUsuarios","RegistroUsuarios","postCreate") ?>" method="POST">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="documento">Documento*</label>
                            <input type="text" class="form-control" id="documento" name="documento" placeholder="Ingrese un número de documento" minlength="9" maxlength="10" required>
                            <small id="error-documento" class="text-danger"></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre*</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese un nombre" minlength="2" maxlength="20" required>
                                    <small id="error-nombre" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido">Apellido*</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese un apellido" minlength="2" maxlength="20" required>
                                    <small id="error-apellido" class="text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono*</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese un número de teléfono" minlength="9" maxlength="10" required>
                            <small id="error-telefono" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo*</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese un correo electronico" required>
                            <small id="error-correo" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="contrasena">Contrasena*</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese una contraseña" minlength="8" maxlength="16" required>
                            <small id="error-contrasena" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="id_rol">Rol*</label>
                            <select class="form-control" id="id_rol" name="id_rol" required>
                                <option value="">Seleccione un rol</option>
                                <?php while ($rol = pg_fetch_assoc($roles)) { ?>
                                    <option value="<?php echo $rol['id_rol']; ?>">
                                        <?php echo $rol['nombre_rol']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <small id="error-id_rol" class="text-danger"></small>
                        </div>

                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    document.getElementById('registroForm').addEventListener('submit', function (event) {
        event.preventDefault(); // SIEMPRE detener primero

        // Limpiar errores
        document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

        let valido = true;

        const documento  = document.getElementById('documento').value.trim();
        const nombre     = document.getElementById('nombre').value.trim();
        const apellido   = document.getElementById('apellido').value.trim();
        const telefono   = document.getElementById('telefono').value.trim();
        const correo     = document.getElementById('correo').value.trim();
        const contrasena = document.getElementById('contrasena').value;
        const rol        = document.getElementById('id_rol').value;

        //documento
        if (!/^\d{9,10}$/.test(documento)) {
            document.getElementById('error-documento').textContent =
                'El documento debe tener 9 o 10 dígitos numéricos.';
            valido = false;
        }

        //nombre
        if (!/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{2,20}$/.test(nombre)) {
            document.getElementById('error-nombre').textContent =
                'El nombre debe tener entre 2 y 20 letras.';
            valido = false;
        }

        //apellido
        if (!/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{2,20}$/.test(apellido)) {
            document.getElementById('error-apellido').textContent =
                'El apellido debe tener entre 2 y 20 letras.';
            valido = false;
        }

        //telefono
        if (!/^\d{9,10}$/.test(telefono)) {
            document.getElementById('error-telefono').textContent =
                'Número de documento invalido, el número de documento debe tener 9 o 10 dígitos numíricos.';
            valido = false;
        }

        //correo
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            document.getElementById('error-correo').textContent =
                '“Correo inválido. Debe incluir una extensión y dominio, por ejemplo: usuario@dominio.extension.';
            valido = false;
        }

        //contrasena
        if (
            contrasena.length < 8 ||contrasena.length > 16 || !/[A-Z]/.test(contrasena) || !/[a-z]/.test(contrasena) ||
            !/[0-9]/.test(contrasena) || !/[!@#$%^&*(),.?":{}|<>_\-+=`[\]\\;/]/.test(contrasena)
        ) {
            document.getElementById('error-contrasena').textContent =
                'Su contraseña no es segura (mínimo 8 caracteres, al menos un número, una letra mayúscula y un símbolo)';
            valido = false;
        }

        //rol
        if (rol === '') {
            document.getElementById('error-id_rol').textContent =
                'Debe seleccionar un rol.';
            valido = false;
        }

        if (valido) {
            // SOLO aqui se envia el formulario
            this.submit();
        }
    });
</script>
