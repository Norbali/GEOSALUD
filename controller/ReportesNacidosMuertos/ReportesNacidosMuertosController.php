<?php

 include_once '../model/ReportesNacidosMuertos/ReportesNacidosMuertosModel.php';

class ReportesNacidosMuertosController{

    public function getConsulta(){
        $obj = new ReportesNacidosMuertosModel();
        $sqlZoocriaderos = "SELECT * FROM zoocriadero";
        $zoocriaderos = $obj->select($sqlZoocriaderos);
        include_once '../view/reportesNacidosMuertos/reporteNacidosMuertos.php';
    } 


}

?>