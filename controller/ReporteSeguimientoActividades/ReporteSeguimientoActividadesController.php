<?php

include_once '../model/ReportesSeguimientoActividades/ReporteSeguimientoActividadesModel.php';

class ReporteSeguimientoActividadesController{

    public function getConsulta(){
        $obj = new ReporteSeguimientoActividadesModel();

        $sqlActividades = "SELECT * FROM actividad WHERE id_estado_actividad=1";
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

        public function exportarEnExcel(){
    
    // CRÍTICO: Limpiar cualquier output buffer previo
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $obj = new ReporteSeguimientoActividadesModel();

    // Recibir datos del formulario
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $actividad = isset($_POST['actividad']) ? $_POST['actividad'] : '';
    $zoocriadero = isset($_POST['zoocriadero']) ? $_POST['zoocriadero'] : '';

    $sql = "
        SELECT
            s.fecha,
            a.nombre_actividad,
            z.nombre_zoocriadero,
            t.id_tanque,
            sd.ph, sd.temperatura, sd.cloro,
            sd.num_alevines, sd.num_muertes, sd.num_machos, sd.num_hembras,
            sd.observaciones
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
        $sql .= " AND a.id_actividad = " . intval($actividad);
    }

    if ($zoocriadero != '') {
        $sql .= " AND z.id_zoocriadero = " . intval($zoocriadero);
    }

    $sql .= " ORDER BY s.fecha DESC";

    $result = $obj->select($sql);

    // ========== EXPORTAR A EXCEL (CSV) ==========

    // Generar nombre de archivo con fecha
    $nombre_archivo = 'reporte_seguimiento_' . date('Ymd_His') . '.csv';

    // Configurar headers para descarga CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    // Crear salida
    $output = fopen('php://output', 'w');

    // BOM para UTF-8 (para que Excel lea correctamente los acentos)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Encabezados de las columnas
    $encabezados = array(
        'Fecha',
        'Actividad',
        'Zoocriadero',
        'ID Tanque',
        'pH',
        'Temperatura',
        'Cloro',
        'Num Alevines',
        'Num Muertes',
        'Num Machos',
        'Num Hembras',
        'Observaciones'
    );

    fputcsv($output, $encabezados);

    // Escribir datos
    if (is_array($result) && count($result) > 0) {
        foreach ($result as $row) {
            $fila = array(
                isset($row['fecha']) ? $row['fecha'] : '',
                isset($row['nombre_actividad']) ? $row['nombre_actividad'] : '',
                isset($row['nombre_zoocriadero']) ? $row['nombre_zoocriadero'] : '',
                isset($row['id_tanque']) ? $row['id_tanque'] : '',
                isset($row['ph']) ? $row['ph'] : '',
                isset($row['temperatura']) ? $row['temperatura'] : '',
                isset($row['cloro']) ? $row['cloro'] : '',
                isset($row['num_alevines']) ? $row['num_alevines'] : '',
                isset($row['num_muertes']) ? $row['num_muertes'] : '',
                isset($row['num_machos']) ? $row['num_machos'] : '',
                isset($row['num_hembras']) ? $row['num_hembras'] : '',
                isset($row['observaciones']) ? $row['observaciones'] : ''
            );
            fputcsv($output, $fila);
        }
    } else {
        // Si no hay datos, mostrar mensaje
        fputcsv($output, array('No se encontraron registros con los criterios seleccionados'));
    }

    fclose($output);
    exit();
}
}

?>