<!-- Filtros -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Filtros del Reporte</h4>
    </div>

    <div class="card-body">
        <form id="filtrosReporte" class="row g-3">

            <!-- Fecha inicio -->
            <div class="col-md-4">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control">
            </div>

            <!-- Fecha fin -->
            <div class="col-md-4">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control">
            </div>

            <!-- Tipo actividad -->
            <div class="col-md-4">
                <label class="form-label">Tipo de actividad</label>
                <select name="tipo" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="nacimiento">Nacimiento</option>
                    <option value="muerte">Muerte</option>
                    <option value="alimentación">Alimentación</option>
                    <option value="traslado">Traslado</option>
                </select>
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
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">
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

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-head-bg-primary mt-3">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Actividad</th>
                        <th>Zoocriadero</th>
                        <th>Tanque</th>
                        <th>Muertes hembras</th>
                        <th>Muertes machos</th>
                        <th>Muertes</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
