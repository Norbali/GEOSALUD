<?php

    include_once '../model/Mapa/MapaModel.php';

    class MapaController{

          public function vistaIndex(){
            include_once '../view/Mapa/indexMapa.php';
        }


        public function vistaMapa(){
            include_once '../view/Mapa/visorCaliMapa.php';
        }

        public function registrarZoocriadero(){
            
        }

    }

?>