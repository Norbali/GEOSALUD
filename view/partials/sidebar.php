<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo  -->
        <div class="logo-header" data-background-color="dark">

            <a href="index.html" class="logo">
                <img src="assets/img/logoGEOSALUD.png" alt="navbar brand" class="navbar-brand" height="70">
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
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#mapa" class="collapsed" aria-expanded="false">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Mapa</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Módulos</h4>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl("Tanques","Tanques","getList")?>" class="collapsed" aria-expanded="false">
                        <i class="fas fa-fish"></i>
                        <p>Tanques</p>
                    </a>
                </li>
      
               <!-- <!- <?php if (array_key_exists("TipoActividades", $permisos)){?> -->
                <li class="nav-item">
                                <a href=" <?php echo getUrl("TipoActividades","ConsultarTipoDeActividades","getConsulta")?>">
                        <i class="fas fa-list-alt"></i>
                        <p>Tipos de actividades</p>
                    </a>
                </li>
                <!-- <?php }?> -->

             
                    <li class="nav-item">
                        <i class="fas fa-pen-square"></i>        
                        <a href="<?php echo getUrl("SeguimientoDeTanques","SeguimientoDeTanques","getConsulta");?>">
                            <p>Seguimiento de tanques</p>
                        </a>
                    </li>
              
                <?php if (array_key_exists("TipoDeTanques", $permisos)){ ?>
                    <li class="nav-item">
                        <a href="<?php echo getUrl("TipoTanques", "TipoTanques", "getConsultar") ?>">
                            <i class="fas fa-th-large"></i>
                            <p>Tipo de tanques</p>
                        </a>
                    </li>
                <?php }?>

                
                <li class="nav-item">
                    <a href="<?php echo getUrl("RegistroUsuarios","RegistroUsuarios","getCreate")?>">
                        <i class="fas fa-user"></i>
                        <p>Registro de usuarios</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#configuracion">
                        <i class="fas fa-cog"></i>
                        <p>Ajustes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="configuracion">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="<?php echo getUrl('InformacionPersonal','InformacionPersonal','getInformacion') ?>">
                                    <span class="sub-item">Informaci&oacute;n Personal</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                    <a href="<?php echo getUrl('VideoManual', 'VideoManual', 'index'); ?>">
                                        <span class="sub-item">Manuales</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo getUrl('Autores', 'Autores', 'index'); ?>">
                                        <span class="sub-item">Autores</span>                                 </a>
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
                            <li>
                                <a href="<?php echo getUrl("ReporteSeguimientoActividades","ReporteSeguimientoActividades","getConsulta")?>">
                                    <span class="sub-item">Seguimiento de actividades</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo getUrl("ReportesNacidosMuertos","ReportesNacidosMuertos","getConsulta")?>">
                                    <span class="sub-item">Nacidos o muertos </span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo getUrl("ReportesTanquesZoocriadero", "ReportesTanquesZoocriadero", "getConsulta");?>">
                                    <span class="sub-item">Tanques por zoocriadero</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>