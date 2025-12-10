<?php

include_once '../model/ReportesSeguimientoActividades/ReporteSeguimientoActividadesModel.php';

class ReporteSeguimientoActividadesController{

    public function getConsulta(){
        $obj = new ReporteSeguimientoActividadesModel();
        $sql = "SELECT *FROM actividad";
        $actividades = $obj->select($sql);
        //$sql = "SELECT *FROM actividad WHERE id_estado_actividad=1";
        include_once '../view/reportesSeguimientoActividades/reporteSeguimientoActividades.php';
    } 

}

?>