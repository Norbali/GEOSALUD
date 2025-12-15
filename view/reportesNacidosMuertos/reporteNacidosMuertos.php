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
                    <button type="submit" name="accion" value="reporte" class="btn btn-primary btn-sm">
                        Generar Reporte
                    </button>
                </div>   
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Resultados del Reporte</h4>
        </div>
        <div class="text-end mt-2 me-4">
            <button type="button" onclick="exportarExcelXLSX()" class="btn btn-success btn-sm">
                Generar Excel
            </button>
        </div>
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
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportarExcelXLSX() {
        //OBTENER FILTROS
        const fechaInicio = document.getElementById('fecha_inicio')?.value || 'Sin filtro';
        const fechaFin = document.getElementById('fecha_fin')?.value || 'Sin filtro';
        const zoocriaderoSelect = document.getElementById('zoocriadero');
        const zoocriadero = zoocriaderoSelect?.selectedOptions[0]?.text || 'Todos';

        //OBTENER TABLA
        const tabla = document.querySelector('.table-responsive table tbody');
        if (!tabla) {
            alert('No hay datos para exportar');
            return;
        }

        const filas = tabla.querySelectorAll('tr');
        if (filas.length === 0) {
            alert('No hay registros para exportar');
            return;
        }

        let datos = [];

        //ENCABEZADO DEL EXCEL
        datos.push(['REPORTE PECES NACIDOS Y MUERTOS']);
        datos.push([]);
        datos.push(['Filtros aplicados']);
        datos.push(['Fecha inicio', fechaInicio]);
        datos.push(['Fecha fin', fechaFin]);
        datos.push(['Zoocriadero', zoocriadero]);
        datos.push([]);

        // ENCABEZADOS DE LA TABLA
        datos.push([
            'Fecha',
            'Zoocriadero',
            'Id Tanque',
            'Nacidos',
            'Muertes Hembras',
            'Muertes Machos',
            'Total Muertes'
        ]);

        // DATOS DE LA TABLA
        filas.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            if (celdas.length === 7) {
                datos.push([
                    celdas[0].textContent.trim(),
                    celdas[1].textContent.trim(),
                    celdas[2].textContent.trim(),
                    celdas[3].textContent.trim(),
                    celdas[4].textContent.trim(),
                    celdas[5].textContent.trim(),
                    celdas[6].textContent.trim()
                ]);
            }
        });


        //CREAR EXCEL
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(datos);

        //ANCHO DE COLUMNAS
        ws['!cols'] = [
            { wch: 12 }, // Fecha
            { wch: 25 }, // Zoocriadero
            { wch: 10 }, // Id Tanque
            { wch: 10 }, // Nacidos
            { wch: 18 }, // Muertes Hembras
            { wch: 18 }, // Muertes Machos
            { wch: 15 }  // Total Muertes
        ];

        XLSX.utils.book_append_sheet(wb, ws, 'Nacidos y Muertos');

        const fechaActual = new Date().toISOString().split('T')[0];
        XLSX.writeFile(
            wb,
            `Reporte_Nacidos_Muertos_${fechaActual}.xlsx`
        );
    }


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