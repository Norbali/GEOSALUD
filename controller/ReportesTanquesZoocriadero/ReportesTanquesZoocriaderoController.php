<?php
    session_start();
    include_once '../model/ReportesTanquesZoocriadero/ReportesTanquesZoocriaderoModel.php';

    class ReportesTanquesZoocriaderoController{

        public function getConsulta(){
            $obj = new ReportesTanquesZoocriaderoModel();
            $sqlZoocriaderos = "SELECT *FROM zoocriadero";
            $zoocriaderos = $obj->select($sqlZoocriaderos);

            $cosultaTanquesZoocriadero = $this->cosultaTanquesZoocriadero();

            include_once '../view/reportesTanquesZoocriadero/reporteTanquesZoocriadero.php';
        } 

        public function cosultaTanquesZoocriadero(){
            $obj = new ReportesTanquesZoocriaderoModel();

            $sql = "
                    SELECT
                    z.id_zoocriadero,
                    z.nombre_zoocriadero,
                    ez.nombre_estado_zoocriaderos AS estado_zoocriadero,
                    t.id_tanque,
                    tt.nombre_tipo_tanque
                FROM zoocriadero z
                INNER JOIN estado_zoocriaderos ez
                    ON ez.id_estado_zoocriaderos = z.id_estado_zoocriadero
                INNER JOIN tanque t
                    ON t.id_zoocriadero = z.id_zoocriadero
                INNER JOIN tipo_tanque tt
                    ON tt.id_tipo_tanque = t.id_tipo_tanque
                ORDER BY z.nombre_zoocriadero, t.id_tanque;

            ";

            return $obj->select($sql);
        }

        public function filtro(){
            $obj = new ReportesTanquesZoocriaderoModel();

            $buscar = $_GET['buscar'];

            $sql = "
                   SELECT
                    z.id_zoocriadero,
                    z.nombre_zoocriadero,
                    ez.nombre_estado_zoocriaderos AS estado_zoocriadero,
                    t.id_tanque,
                    tt.nombre_tipo_tanque
                FROM zoocriadero z
                INNER JOIN estado_zoocriaderos ez
                    ON ez.id_estado_zoocriaderos = z.id_estado_zoocriadero
                INNER JOIN tanque t
                    ON t.id_zoocriadero = z.id_zoocriadero
                INNER JOIN tipo_tanque tt
                    ON tt.id_tipo_tanque = t.id_tipo_tanque
                WHERE z.nombre_zoocriadero ILIKE '%$buscar%'
                ORDER BY z.nombre_zoocriadero, t.id_tanque
                ";

            $cosultaTanquesZoocriadero = $obj->select($sql);
            if(pg_affected_rows($cosultaTanquesZoocriadero) == 0){
                $_SESSION['sinResultados'] = "No se encontraron registros.";
            }

            include_once "../view/reportesTanquesZoocriadero/filtro.php";
        }

    }

?>