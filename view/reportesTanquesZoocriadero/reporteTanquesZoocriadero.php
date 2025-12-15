<?php
    include_once '../lib/helpers.php';
?>

<div style="position: relative; top: -100px;">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtros Reporte de Tanques por zoocriadero</h4>
        </div>

        <div class="card-body">
            <form id="filtrosReporte" class="row g-3">

                <!-- Zoocriadero -->
                <div class="col-md-4">
                    <label class="form-label">Zoocriadero</label>
                     <div class="col-3 mb-3 ">
                    <input type="text" class="form-control" placeholder="Ingrese el nombre de un zoocriadero..." style="width: 300px;" id="filtro" 
                        data-url="<?php echo getUrl("ReportesTanquesZoocriadero", "ReportesTanquesZoocriadero", "filtro", false, "ajax");?>">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Resultados del Reporte</h4>
        </div>
        <div class="text-end mt-2 me-4">
           <button type="button" class="btn btn-success btn-sm" onclick="exportarExcelTanquesZoocriadero()">
                Generar Excel
            </button>

        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-head-bg-primary mt-3">
                    <thead>
                        <tr>
                            <th>Id zoocriadero</th>
                            <th>Estado</th>
                            <th>Tanque</th>
                            <th>Tipo de tanque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($row = pg_fetch_assoc($cosultaTanquesZoocriadero)) {
                                echo "<tr>";
                                    echo "<td>" . $row['nombre_zoocriadero'] . "</td>";
                                    echo "<td>" . $row['estado_zoocriadero'] . "</td>";
                                    echo "<td>" . $row['id_tanque'] . "</td>";
                                    echo "<td>" . $row['nombre_tipo_tanque'] . "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
                if (isset($_SESSION['sinResultados'])) {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            ' . $_SESSION['sinResultados'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    unset($_SESSION['sinResultados']);
                }
            ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function exportarExcelTanquesZoocriadero() {

    // OBTENER FILTRO DE BÚSQUEDA
    const filtroInput = document.getElementById('filtro');
    const filtroTexto = filtroInput?.value || 'Todos los zoocriaderos';

    // OBTENER TABLA
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

    // ENCABEZADO DEL EXCEL
    datos.push(['REPORTE DE TANQUES POR ZOOCRIADERO']);
    datos.push([]);
    datos.push(['Filtro aplicado']);
    datos.push(['Zoocriadero', filtroTexto]);
    datos.push([]);

    // ENCABEZADOS DE LA TABLA
    datos.push([
        'Zoocriadero',
        'Estado',
        'Tanque',
        'Tipo de Tanque'
    ]);

    // DATOS DE LA TABLA
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length === 4) {
            datos.push([
                celdas[0].textContent.trim(),
                celdas[1].textContent.trim(),
                celdas[2].textContent.trim(),
                celdas[3].textContent.trim()
            ]);
        }
    });

    // CREAR EXCEL
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(datos);

    // ANCHO DE COLUMNAS
    ws['!cols'] = [
        { wch: 25 }, // Zoocriadero
        { wch: 15 }, // Estado
        { wch: 10 }, // Tanque
        { wch: 20 }  // Tipo Tanque
    ];

    XLSX.utils.book_append_sheet(wb, ws, 'Tanques por Zoocriadero');

    // DESCARGAR ARCHIVO
    const fechaActual = new Date().toISOString().split('T')[0];
    XLSX.writeFile(
        wb,
        `Reporte_Tanques_Zoocriadero_${fechaActual}.xlsx`
    );
}
</script>
