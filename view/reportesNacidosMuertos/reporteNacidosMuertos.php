<!-- Filtros -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Filtros Reporte Nacidos Muertos peces</h4>
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


            <!-- Zoocriadero -->
            <div class="col-md-4">
                <label class="form-label">Zoocriadero</label>
                <select name="zoocriadero" class="form-select">
                    <option value="">Todos</option>
                    <select name="zoocriadero" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($zoocriaderos as $zoocriadero) { ?>
                        <option value="<?= $zoocriadero['id_zoocriadero'] ?>"><?= $act['nombre_zoocriadero'] ?></option>
                    <?php } ?>
                </select>
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

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-head-bg-primary mt-3">
                <thead>
                    <tr>
                        <th>Zoocriadero</th>
                        <th>Id tanque</th>
                        <th>Nacidos</th>
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
