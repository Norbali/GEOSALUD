<?php

include_once '../model/ReportesSeguimientoActividades/ReporteSeguimientoActividadesModel.php';

class ReporteSeguimientoActividadesController{

    public function getConsulta(){
        $obj = new ReporteSeguimientoActividadesModel();

        $sqlActividades = "SELECT * FROM actividad WHERE id_estado_actividad=1";
        $actividades = $obj->select($sqlActividades);

        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);
        
        include_once '../view/reportesSeguimientoActividades/reporteSeguimientoActividades.php';
    } 

}

?>