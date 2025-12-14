<?php
    include_once '../model/ReportesTanquesZoocriadero/ReportesTanquesZoocriaderoModel.php';

    class ReportesTanquesZoocriaderoController{

        public function getConsulta(){
            $obj = new ReportesTanquesZoocriaderoModel();
            $sqlZoocriaderos = "SELECT *FROM zoocriadero";
            $zoocriaderos = $obj->select($sqlZoocriaderos);
            //
            include_once '../view/reportesTanquesZoocriadero/reporteTanquesZoocriadero.php';
        } 

    }

?>