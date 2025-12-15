<div style="position: relative; top: -70px;">
    <div class="card mx-auto mt-0">
        <div class="card-header">
            <h4 class="card-title">Filtros Reporte Seguimiento de Tanques</h4>
        </div>

        <div class="card-body"> 
            <form method="POST" action="<?php echo getUrl("ReporteSeguimientoActividades","ReporteSeguimientoActividades","filtro" ); ?>" id="filtrosReporte">
                <div class="row g-3">

                    <!-- Fecha inicio -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio"  id="fecha_inicio"class="form-control"
                            value="<?php echo $fecha_inicio; ?>">
                        <small id="error-fechaInicio" class="text-danger"></small>
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin"  id="fecha_fin" class="form-control"
                            value="<?php echo $fecha_fin; ?>">
                        <small id="error-fechaFin" class="text-danger"></small>
                    </div>

                    <!-- Actividad -->
                    <div class="col-md-3">
                        <label class="form-label">Actividad</label>
                        <select name="actividad" id="actividad" class="form-select">
                            <option value="">Todas</option>
                            <?php while ($act = pg_fetch_assoc($actividades)) { ?>
                                <option value="<?php echo $act['id_actividad']; ?>"
                                    <?php
                                    if ($actividad_sel == $act['id_actividad']) {
                                        echo "selected";
                                    }
                                    ?>>
                                    <?php echo $act['nombre_actividad']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <small id="error-actividad" class="text-danger"></small>
                    </div>

                    <!-- Zoocriadero -->
                    <div class="col-md-3">
                        <label class="form-label">Zoocriadero</label>
                        <select name="zoocriadero" id="zoocriadero" class="form-select">
                            <option value="">Todos</option>
                            <?php while ($zoo = pg_fetch_assoc($zoocriaderos)) { ?>
                                <option value="<?php echo $zoo['id_zoocriadero']; ?>"
                                    <?php
                                    if ($zoocriadero_sel == $zoo['id_zoocriadero']) {
                                        echo "selected";
                                    }
                                    ?>>
                                    <?php echo $zoo['nombre_zoocriadero']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <small id="error-zoocriadero" class="text-danger"></small>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" name="tipo" value="reporte"
                                class="btn btn-primary btn-sm">
                            Generar Reporte
                        </button>

                        <button type="submit" name="excel" value="excel"
                                class="btn btn-success btn-sm">
                            Exportar Excel
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Resultados del Reporte</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-head-bg-primary mt-3">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Actividad</th>
                            <th>Zoocriadero</th>
                            <th>Tanque</th>
                            <th>Accciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while ($row = pg_fetch_assoc($consultaSeguimiento)) {
                                echo "<tr>";
                                echo "<td>".$row['fecha']."</td>";
                                echo "<td>".$row['nombre_actividad']."</td>";
                                echo "<td>".$row['nombre_zoocriadero']."</td>";
                                echo "<td>".$row['id_tanque']."</td>";
                                echo "<td>";
                            ?>

                                <!-- BOTÓN MODAL -->
                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetalle<?= $row['id_seguimiento'] ?>">
                                    Ver detalle
                                </button>

                                <!-- MODAL -->
                                <div class="modal fade" id="modalDetalle<?= $row['id_seguimiento'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalle del seguimiento</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <p><strong>Fecha:</strong> <?= $row['fecha'] ?></p>
                                                <p><strong>Actividad:</strong> <?= $row['nombre_actividad'] ?></p>
                                                <p><strong>Zoocriadero:</strong> <?= $row['nombre_zoocriadero'] ?></p>
                                                <p><strong>Tanque:</strong> <?= $row['id_tanque'] ?></p>

                                                <hr>

                                                <p><strong>PH:</strong> <?= $row['ph'] ?></p>
                                                <p><strong>Temperatura:</strong> <?= $row['temperatura'] ?></p>
                                                <p><strong>Cloro:</strong> <?= $row['cloro'] ?></p>
                                                <p><strong>Nacidos:</strong> <?= $row['num_alevines'] ?></p>
                                                <p><strong>Muertes:</strong> <?= $row['num_muertes'] ?></p>
                                                <p><strong>Machos:</strong> <?= $row['num_machos'] ?></p>
                                                <p><strong>Hembras:</strong> <?= $row['num_hembras'] ?></p>
                                                <p><strong>Observaciones:</strong> <?= $row['observaciones'] ?></p>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            <?php
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.getElementById('filtrosReporte').addEventListener('submit', function (e) {
        e.preventDefault();

        // Limpiar errores
        document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
        let isValid = true;

        const formData = new FormData(this);

        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        
        if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
            document.getElementById('error-fechaFin').textContent =
                'La fecha fin no puede ser menor que la fecha inicio';
            isValid = false;
        }

        const hoy = new Date().toISOString().split('T')[0];
        if (fechaInicio && fechaInicio > hoy) {
            document.getElementById('error-fechaInicio').textContent =
                'La fecha inicio no puede ser futura';
            isValid = false;
        }

        if (fechaFin && fechaFin > hoy) {
            document.getElementById('error-fechaFin').textContent =
                'La fecha fin no puede ser futura';
            isValid = false;
        }

        if ((fechaInicio && !fechaFin) || (!fechaInicio && fechaFin)) {
            if (!fechaInicio) {
                document.getElementById('error-fechaInicio').textContent =
                    'Debe seleccionar la fecha inicio';
            }
            if (!fechaFin) {
                document.getElementById('error-fechaFin').textContent =
                    'Debe seleccionar la fecha fin';
            }
            isValid = false;
        }


        if (!isValid) return;
        const accion = formData.get('accion');
        console.log('Acción:', accion);

        // Enviar formulario
        this.submit();
    });
    
</script>

