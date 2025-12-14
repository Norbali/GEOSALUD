<!-- Filtros -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Filtros del Reporte</h4>
    </div>

    <div class="card-body">
        <form id="filtrosReporte" class="row g-3">

            <!-- Zoocriadero -->
            <div class="col-md-4">
                <label class="form-label">Zoocriadero</label>
                <select name="zoocriadero" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($zoocriaderos as $zoocriadero) { ?>
                        <option value="<?= $zoocriadero['id_zoocriadero'] ?>"><?= $act['nombre_zoocriadero'] ?></option>
                    <?php } ?>
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
                        <th>Id zoocriadero</th>
                         <th>Tanque</th>
                        <th>Tipo de tanque</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
