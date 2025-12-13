<div class="card">
    <div class="card-header">
        <div class="card-title">Registro de Usuario</div>
    </div>

  <form action="<?php echo getUrl("RegistroUsuarios","RegistroUsuarios","postCreate") ?>" method="POST">
        <div class="card-body">

            <div class="form-group">
                <label for="documento">Documento*</label>
                <input type="text" class="form-control" id="documento" name="documento" placeholder="Ingrese un n&uacute;mero de documento" required>
                <small class="text-danger"></small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre*</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese un nombre" required>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apellido">Apellido*</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese un apellido" required>
                        <small class="text-danger"></small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="telefono">Tel&eacute;fono*</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese un n&uacute;mero de tel&eacute;fono" required>
                <small class="text-danger"></small>
            </div>

            <div class="form-group">
                <label for="correo">Correo*</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese un correo electronico"required>
                <small class="text-danger"></small>
            </div>

            <div class="form-group">
                <label for="contraseña">Contrase&ntilde;a*</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Ingrese una ccontrase&ntilde;a" required>
                <small class="text-danger"></small>
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
                <small class="text-danger"></small>
            </div>

        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>


<script src="/geosalud/web/assets/validaciones.js"></script>