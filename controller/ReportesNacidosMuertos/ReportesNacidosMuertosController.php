<?php
    session_start();
    include_once '../model/ReportesNacidosMuertos/ReportesNacidosMuertosModel.php';

class ReportesNacidosMuertosController{

    public function getConsulta(){
        $obj = new ReportesNacidosMuertosModel();
        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);

        $listadoNacidosMuertos = $this->listarNacidosMuertos();
        include_once '../view/reportesNacidosMuertos/reporteNacidosMuertos.php';
    } 

    public function listarNacidosMuertos(){
        $obj = new ReportesNacidosMuertosModel();

        $sql = "
                SELECT
                    s.fecha,
                    z.nombre_zoocriadero,
                    t.id_tanque,

                    -- Nacidos del día
                    COALESCE(SUM(sd.num_alevines), 0) AS nacidos,

                    -- Muertes hembras del día
                    COALESCE(SUM(sd.num_hembras), 0) AS muertes_hembras,

                    -- Muertes machos del día
                    COALESCE(SUM(sd.num_machos), 0) AS muertes_machos,

                    -- Total muertes del día
                    COALESCE(SUM(sd.num_muertes), 0) AS total_muertes

                FROM seguimiento s
                INNER JOIN seguimiento_detalle sd 
                    ON sd.id_seguimiento = s.id_seguimiento
                INNER JOIN tanque t 
                    ON t.id_tanque = s.id_tanque
                INNER JOIN zoocriadero z 
                    ON z.id_zoocriadero = t.id_zoocriadero

                GROUP BY
                    s.fecha,
                    z.nombre_zoocriadero,
                    t.id_tanque

                ORDER BY
                    s.fecha DESC,
                    t.id_tanque;
        ";

        return $obj->select($sql);
    }

    public function filtro(){
        $obj = new ReportesNacidosMuertosModel();

        // Inicializar variables
        $fecha_inicio = '';
        $fecha_fin = '';
        $zoocriadero = '';

        // Recibir POST
        if (isset($_POST['fecha_inicio'])) {
            $fecha_inicio = $_POST['fecha_inicio'];
        }

        if (isset($_POST['fecha_fin'])) {
            $fecha_fin = $_POST['fecha_fin'];
        }

        if (isset($_POST['zoocriadero'])) {
            $zoocriadero = $_POST['zoocriadero'];
        }

        $sql = "
            SELECT
                s.fecha,
                z.nombre_zoocriadero,
                t.id_tanque,

                COALESCE(SUM(sd.num_alevines), 0) AS nacidos,
                COALESCE(SUM(sd.num_hembras), 0) AS muertes_hembras,
                COALESCE(SUM(sd.num_machos), 0) AS muertes_machos,
                COALESCE(SUM(sd.num_muertes), 0) AS total_muertes

            FROM seguimiento s
            INNER JOIN seguimiento_detalle sd 
                ON sd.id_seguimiento = s.id_seguimiento
            INNER JOIN tanque t 
                ON t.id_tanque = s.id_tanque
            INNER JOIN zoocriadero z 
                ON z.id_zoocriadero = t.id_zoocriadero
            WHERE 1=1
        ";

        // Filtro por rango de fechas
        if ($fecha_inicio != '' && $fecha_fin != '') {
            $sql .= " AND s.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        // Filtro por zoocriadero
        if ($zoocriadero != '') {
            $sql .= " AND z.id_zoocriadero = $zoocriadero";
        }

        $sql .= "
            GROUP BY s.fecha, z.nombre_zoocriadero, t.id_tanque
            ORDER BY s.fecha DESC, t.id_tanque
        ";

        // Consulta principal
        $listadoNacidosMuertos = $obj->select($sql);
        if (pg_num_rows($listadoNacidosMuertos) == 0) {
            $_SESSION['sinResultadoNacidosMuertos'] = 'No se encontraron registros con los filtros seleccionados.';
        }

        // Para volver a cargar selects
        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);



        // Mostrar vista
        include_once '../view/reportesNacidosMuertos/reporteNacidosMuertos.php';
    }

}

?>