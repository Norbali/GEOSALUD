<?php
    $permisos = $_SESSION['permisos'];
?>

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo  -->
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo m-4">
                <img src="assets/img/logoGEOSALUD.png" alt="navbar brand" class="navbar-brand px-5" height="70">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>

        </div>
        <!-- End Logo Header -->	
    </div>	
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
               
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Módulos</h4>
                </li>

                <li class="nav-item active">
                    <a href="<?php echo getUrl("Mapa","Mapa","vistaIndex")?>" class="collapsed" aria-expanded="false">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Mapa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl("Tanques","Tanques","getList")?>" class="collapsed" aria-expanded="false">
                        <i class="fas fa-fish"></i>
                        <p>Tanques</p>
                    </a>
                </li>

                <?php if (array_key_exists("SeguimientoDeTanques", $permisos)){ ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#actividades">
                            <i class="fas fa-pen-square"></i>
                            <p>Seguimiento de tanques</p>
                        </a>
                    </li>
                <?php }?>
                <?php if (array_key_exists("TipoDeTanques", $permisos)){ ?>
                    <li class="nav-item">
                        <a href="<?php echo getUrl("TipoTanques", "TipoTanques", "getConsultar") ?>">
                            <i class="fas fa-th-large"></i>
                            <p>Tipo de tanques</p>
                        </a>
                    </li>
                <?php }?>

                
                <?php if (array_key_exists("TiposDeActividades", $permisos)){ ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#tipoactividades">
                            <i class="fas fa-list-alt"></i>
                            <p>Tipos de actividades</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="tipoactividades">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="forms/forms.html">
                                        <span class="sub-item">Registro de actividades</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="forms/forms.html">
                                        <span class="sub-item">Consultar tipos de actividades</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>
                <?php }?>
                
                <?php if (array_key_exists("RegistroDeUsuarios", $permisos)){ ?>
                    <li class="nav-item">
                        <a href="<?php echo getUrl("RegistroUsuarios","RegistroUsuarios","getCreate")?>">
                            <i class="fas fa-user"></i>
                            <p>Registro de usuarios</p>
                        </a>
                    </li>
                <?php }?>
                
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#configuracion">
                        <i class="fas fa-cog"></i>
                        <p>Ajustes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="configuracion">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="../view/partials/actualizarInformacion.php">
                                    <span class="sub-item">Actualizar Informaci&oacute;n</span>
                                </a>
                            </li>
                            <li>
                                <a href="../view/partials/manualDeUsuario.php">
                                    <span class="sub-item">Manual de Usuario</span>
                                </a>
                            </li>
                            <?php if (array_key_exists("ManualInstalacion", $permisos)){ ?>
                                <li>
                                    <a href="../view/partials/manualDeInstalacion.php">
                                        <span class="sub-item">Manual de Instalación</span>
                                    </a>
                                </li>
                            <?php }?>

                            <?php if (array_key_exists("ManualSistema", $permisos)){ ?>
                                <li>
                                    <a href="../view/partials/manualDeSistema.php">
                                        <span class="sub-item">Manual de Sistema</span>
                                    </a>
                                </li>
                            <?php }?>
                            <li class="nav-item">
                                    <a href="<?php echo getUrl('VideoManual', 'VideoManual', 'index'); ?>">
                                         <i class="fas fa-video"></i>
                                        <p>Video Manual</p>
                                    </a>
                             </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#reportes">
                        <i class="fas fa-newspaper"></i>
                        <p>Reportes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="reportes">
                        <ul class="nav nav-collapse">
                            <?php if (array_key_exists("ReporteSeguimientoAc", $permisos)){ ?>
                                <li>
                                    <a href="<?php echo getUrl("ReporteSeguimientoActividades","ReporteSeguimientoActividades","getConsulta")?>">
                                        <span class="sub-item">Seguimiento de actividades</span>
                                    </a>
                                </li>
                            <?php }?>

                            <?php if (array_key_exists("ReporteNacidosOMuert", $permisos)){ ?>
                                <li>
                                    <a href="<?php echo getUrl("ReportesNacidosMuertos","ReportesNacidosMuertos","getConsulta")?>">
                                        <span class="sub-item">Peces Nacidos y muertos </span>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if (array_key_exists("ReporteTanquesPorZoo", $permisos)){ ?>
                                <li>
                                    <a href="<?php echo getUrl("ReportesTanquesZoocriadero", "ReportesTanquesZoocriadero", "getConsulta");?>">
                                        <span class="sub-item">Tanques por zoocriadero</span>
                                    </a>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                 <a href="<?php echo getUrl('Autores', 'Autores', 'index'); ?>">
                    <i class="fas fa-info"></i>
                    <p>Autores</p>
                 </a>
                </li>
            </ul>
        </div>
    </div>
</div>