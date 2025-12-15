<?php

include_once '../model/ReportesSeguimientoActividades/ReporteSeguimientoActividadesModel.php';

class ReporteSeguimientoActividadesController{

    public function getConsulta(){
        $obj = new ReporteSeguimientoActividadesModel();
        $sqlActividades = "SELECT *FROM actividad";
        //$sql = "SELECT *FROM actividad WHERE id_estado_actividad=1";
        $actividades = $obj->select($sqlActividades);

        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);

        $consultaSeguimiento = $this->listarSeguimiento();
        
        include_once '../view/reportesSeguimientoActividades/reporteSeguimientoActividades.php';
    } 

    public function listarSeguimiento(){
        $obj = new ReporteSeguimientoActividadesModel();

        $sql = "
            SELECT
                z.id_zoocriadero,
                z.nombre_zoocriadero,
                t.id_tanque,
                s.id_seguimiento,
                s.fecha,
                a.id_actividad,
                a.nombre_actividad,
                sd.ph,
                sd.temperatura,
                sd.cloro,
                sd.num_alevines,
                sd.num_muertes,
                sd.num_machos,
                sd.num_hembras,
                sd.total,
                sd.observaciones
            FROM seguimiento s
            INNER JOIN seguimiento_detalle sd 
                ON sd.id_seguimiento = s.id_seguimiento
            INNER JOIN actividad a 
                ON a.id_actividad = sd.id_actividad
            INNER JOIN tanque t 
                ON t.id_tanque = s.id_tanque
            INNER JOIN zoocriadero z 
                ON z.id_zoocriadero = t.id_zoocriadero
            ORDER BY s.fecha DESC
        ";

        return $obj->select($sql);
    }

    public function filtro(){
        $obj = new ReporteSeguimientoActividadesModel();

        $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
        $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
        $actividad = isset($_POST['actividad']) ? $_POST['actividad'] : '';
        $zoocriadero = isset($_POST['zoocriadero']) ? $_POST['zoocriadero'] : '';

        $sql = "
            SELECT
                z.id_zoocriadero, z.nombre_zoocriadero,
                t.id_tanque,
                s.id_seguimiento, s.fecha,
                a.id_actividad, a.nombre_actividad,
                sd.ph, sd.temperatura, sd.cloro,
                sd.num_alevines, sd.num_muertes, sd.num_machos, sd.num_hembras, sd.total, sd.observaciones
            FROM seguimiento s
            INNER JOIN seguimiento_detalle sd ON sd.id_seguimiento = s.id_seguimiento
            INNER JOIN actividad a ON a.id_actividad = sd.id_actividad
            INNER JOIN tanque t ON t.id_tanque = s.id_tanque
            INNER JOIN zoocriadero z ON z.id_zoocriadero = t.id_zoocriadero
            WHERE 1=1
        ";

        if ($fecha_inicio != '' && $fecha_fin != '') {
            $sql .= " AND s.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if ($actividad != '') {
            $sql .= " AND a.id_actividad = $actividad";
        }

        if ($zoocriadero != '') {
            $sql .= " AND z.id_zoocriadero = $zoocriadero";
        }

        $sql .= " ORDER BY s.fecha DESC";


        $consultaSeguimiento = $obj->select($sql);

        $sqlActividades = "SELECT * FROM actividad WHERE id_estado_actividad=1";
        $actividades = $obj->select($sqlActividades);

        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);

        include_once '../view/reportesSeguimientoActividades/reporteSeguimientoActividades.php';
    }
      
}

?>