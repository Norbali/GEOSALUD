<div style="position: relative; top: -70px;">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtros Reporte de Tanques por zoocriadero</h4>
        </div>

        <div class="card-body">
            <form id="filtrosReporte" class="row g-3">

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
                            <th>Id zoocriadero</th>
                            <th>Estado</th>
                            <th>Tanque</th>
                            <th>Tipo de tanque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = pg_fetch_assoc($cosultaTanquesZoocriadero)) { ?>
                            <tr>
                                <td><?php echo $row['nombre_zoocriadero']; ?></td>
                                <td><?php echo $row['estado_zoocriadero']; ?></td>
                                <td><?php echo $row['id_tanque']; ?></td>
                                <td><?php echo $row['nombre_tipo_tanque']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
