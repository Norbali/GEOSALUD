<div style="position: relative; top: -70px;">
    <div class="card mx-auto mt-0">
        <div class="card-header">
            <h4 class="card-title">Filtros Reporte Seguimiento de Tanques</h4>
        </div>

        <div class="card-body">
            <form id="filtrosReporte" class="row g-3">

                <!-- Fecha inicio -->
                <div class="col-md-4">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control">
                    <small id="error-fechaInicio" class="text-danger"></small>
                </div>

                <!-- Fecha fin -->
                <div class="col-md-4">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                    <small id="error-fechaFin" class="text-danger"></small>
                </div>

                <!-- Tipo actividad -->
                <div class="col-md-4">
                    <label class="form-label">Tipo de actividad</label>
                    <select name="actividad" id="actividad" class="form-select">
                        <option value="">Todos</option>
                        <?php while ($act = pg_fetch_assoc($actividades)) { ?>
                            <option value="<?php echo $act['id_actividad']; ?>">
                                <?php echo $act['nombre_actividad']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <small id="error-actividad" class="text-danger"></small>
                </div>

                <!-- Zoocriadero -->
                <div class="col-md-4">
                    <label class="form-label">Zoocriadero</label>
                    <select name="zoocriadero" id="zoocriadero" class="form-select">
                        <option value="">Todos</option>
                        <?php while ($zoocriadero = pg_fetch_assoc($zoocriaderos)) { ?>
                            <option value="<?php echo $zoocriadero['id_zoocriadero']; ?>">
                                <?php echo $zoocriadero['nombre_zoocriadero']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <small id="error-zoocriadero" class="text-danger"></small>
                </div>

                <!-- Boton -->
                <div class="d-flex justify-content-end gap-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Generar Reporte
                    </button>

                    <button type="submit" class="btn btn-success btn-sm">
                        Generar Excel
                    </button>
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

        
        if (!fechaInicio && !fechaFin && !actividad && !zoocriadero) {
            document.getElementById('error-fechaInicio').textContent =
                'Debe seleccionar al menos un filtro';
            isValid = false;
        }

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

        const actividad = document.getElementById('actividad').value;
        if (actividad === '') {
            document.getElementById('error-actividad').textContent =
                'Debe seleccionar una actividad';
            isValid = false;
        }

        const zoocriadero = document.getElementById('zoocriadero').value;
        if (zoocriadero === '') {
            document.getElementById('error-zoocriadero').textContent =
                'Debe seleccionar un zoocriadero';
            isValid = false;
        }


        if (!isValid) return;
        const accion = formData.get('accion');
        console.log('Acción:', accion);

        // Enviar formulario
        this.submit();
    });
</script>

