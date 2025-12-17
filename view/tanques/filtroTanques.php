
<?php while ($t = pg_fetch_assoc($tanques)) { ?>
                <tr>
                    <td><?php echo $t['id_tanque']; ?></td>
                    <td><?php echo $t['nombre_tanque']; ?></td>
                    <td><?php echo $t['nombre_tipo_tanque']; ?></td>

                    <td>
                        <?php if ($t['id_estado_tanque'] == 1) { ?>
                        <span class="badge bg-success">Habilitado</span>
                        <?php } else { ?>
                        <span class="badge bg-danger">Inhabilitado</span>
                        <?php } ?>
                    </td>

                    <td style="white-space: nowrap;">
                        <!-- VER -->
                        <button class="btn btn-action btn-ver btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#modalVer"
                            data-nombre="<?php echo $t['nombre_tanque']; ?>"
                            data-tipo="<?php echo $t['nombre_tipo_tanque']; ?>"
                            data-alto="<?php echo $t['medida_alto']; ?>"
                            data-ancho="<?php echo $t['medida_ancho']; ?>"
                            data-profundidad="<?php echo $t['medida_profundidad']; ?>"
                            data-cantidad="<?php echo $t['cantidad_peces']; ?>">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </button>

                        <!-- EDITAR -->
                        <button class="btn btn-action btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditar"
                            data-id="<?php echo $t['id_tanque']; ?>"
                            data-nombre="<?php echo $t['nombre_tanque']; ?>"
                            data-alto="<?php echo $t['medida_alto']; ?>"
                            data-ancho="<?php echo $t['medida_ancho']; ?>"
                            data-profundidad="<?php echo $t['medida_profundidad']; ?>"
                            data-cantidad="<?php echo $t['cantidad_peces']; ?>"
                            data-tipo="<?php echo $t['id_tipo_tanque']; ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>

                        <!-- INHABILITAR / HABILITAR -->
                        <?php if ($t['id_estado_tanque'] == 1) { ?>
                        <a class="btn btn-action btn-disable btn-danger"
                            href="<?php echo getUrl('Tanques','Tanques','updateStatus',array('id_tanque' => $t['id_tanque'],'estado' => 2)); ?>">
                            <i class="fas fa-trash"></i> Inhabilitar
                        </a>
                        <?php } else { ?>
                        <a class="btn btn-action btn-enable"
                            href="<?php echo getUrl('Tanques','Tanques','updateStatus',array('id_tanque' => $t['id_tanque'],'estado' => 1)); ?>">
                            <i class="fas fa-check"></i> Habilitar
                        </a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
