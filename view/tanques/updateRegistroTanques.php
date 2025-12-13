<div class="mt-3">
    <div class="display-4 text-center fw-bold">Actualizar Tanque</div>

    <div class="mt-4">
        <?php foreach($tanque as $tq) { ?>
        <form action="<?php echo getUrl('Tanques', 'Tanque', 'postUpdate'); ?>" method="POST">

            <!-- ID oculto -->
            <input type="hidden" name="id_tanque" value="<?php echo $tq['id_tanque']; ?>">

            <div class="row">

                <div class="col-3">
                    <label>Nombre del Tanque:</label>
                    <input type="text" name="nombre_tanque" class="form-control"
                           value="<?php echo $tq['nombre_tanque']; ?>" required>
                </div>

                <div class="col-3">
                    <label>Medida Alto (cm):</label>
                    <input type="number" name="medida_alto" class="form-control"
                           value="<?php echo $tq['medida_alto']; ?>" required>
                </div>

                <div class="col-3">
                    <label>Medida Ancho (cm):</label>
                    <input type="number" name="medida_ancho" class="form-control"
                           value="<?php echo $tq['medida_ancho']; ?>" required>
                </div>

                <div class="col-3">
                    <label>Medida Profundidad (cm):</label>
                    <input type="number" name="medida_profundidad" class="form-control"
                           value="<?php echo $tq['medida_profundidad']; ?>" required>
                </div>

                <div class="col-3 mt-3">
                    <label>Tipo de Tanque:</label>
                    <select name="id_tipo_tanque" class="form-control" required>
                        <option value="">Seleccione...</option>

                        <?php foreach($tipos as $tp) { 
                            $selected = ($tp['id_tipo_tanque'] == $tq['id_tipo_tanque']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $tp['id_tipo_tanque']; ?>" <?php echo $selected; ?>>
                                <?php echo $tp['nombre_tipo']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label>Cantidad de Peces:</label>
                    <input type="number" name="cantidad_peces" class="form-control"
                           value="<?php echo $tq['cantidad_peces']; ?>" required>
                </div>

            </div>

            <div class="col-12 text-center mt-4">
                <input type="submit" value="Actualizar Tanque" class="btn btn-primary">
            </div>

        </form>
        <?php } ?>
    </div>
</div>
