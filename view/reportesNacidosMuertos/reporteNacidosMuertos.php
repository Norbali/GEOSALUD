<div style="position: relative; top: -70px;">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtros Reporte Peces Nacidos y Muertos</h4>
        </div>

        <div class="card-body">
            <form id="filtrosReporte" class="row g-3" method="POST" action="<?php echo getUrl("ReportesNacidosMuertos","ReportesNacidosMuertos","filtro")?>">

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

                <!-- Botón -->
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

    <div class="card-body">
        <form id="filtrosReporte" class="row g-3">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-head-bg-primary mt-3">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Zoocriadero</th>
                            <th>Id tanque</th>
                            <th>Nacidos</th>
                            <th>Muertes hembras</th>
                            <th>Muertes machos</th>
                            <th>Total Muertes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = pg_fetch_assoc($listadoNacidosMuertos)) { ?>
                            <tr>
                                <td><?php echo $row['fecha']; ?></td>
                                <td><?php echo $row['nombre_zoocriadero']; ?></td>
                                <td><?php echo $row['id_tanque']; ?></td>
                                <td><?php echo $row['nacidos']; ?></td>
                                <td><?php echo $row['muertes_hembras']; ?></td>
                                <td><?php echo $row['muertes_machos']; ?></td>
                                <td><?php echo $row['total_muertes']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>

            <!-- Fecha fin -->
            <div class="col-md-4">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control">
            </div>


            <!-- Zoocriadero -->
            <div class="col-md-4">
                <label class="form-label">Zoocriadero</label>
                <select name="zoocriadero" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Zoocriadero El Edén</option>
                    <option value="2">Acuarios San Luis</option>
                </select>
            </div>

            <!-- Botón -->
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
