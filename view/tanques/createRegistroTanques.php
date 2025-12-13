<div class="mt-3">
    <div class="display-4 text-center fw-bold">Registro de Tanques</div>

    <div class="mt-4">
        <form action="<?php echo getUrl('Tanques','Tanque','postCreate'); ?>" method="POST">

            <div class="row">

                <div class="col-3">
                    <label>Nombre del Tanque:</label>
                    <input type="text" name="nombre_tanque" class="form-control" required>
                </div>

                <div class="col-3">
                    <label>Medida Alto (cm):</label>
                    <input type="number" name="medida_alto" class="form-control" required>
                </div>

                <div class="col-3">
                    <label>Medida Ancho (cm):</label>
                    <input type="number" name="medida_ancho" class="form-control" required>
                </div>

                <div class="col-3">
                    <label>Medida Profundidad (cm):</label>
                    <input type="number" name="medida_profundidad" class="form-control" required>
                </div>

                <div class="col-3 mt-3">
                    <label>Tipo de Tanque:</label>
                    <select name="id_tipo_tanque" class="form-control" required>
                        <option value="">Seleccione...</option>

                        <?php foreach($tipos as $t){ ?>
                            <option value="<?php echo $t['id_tipo_tanque']; ?>">
                                <?php echo $t['nombre_tipo']; ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label>Cantidad de Peces:</label>
                    <input type="number" name="cantidad_peces" class="form-control" required>
                </div>

            </div>

            <div class="col-12 text-center mt-4">
                <input type="submit" value="Registrar Tanque" class="btn btn-success">
            </div>

        </form>
    </div>
</div>
