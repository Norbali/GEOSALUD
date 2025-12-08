<?php

    session_start();
    function redirect($url){
        echo "<script>".
                "window.location.href = '$url';".
            "</script>";

    } 
    function dd($var){
        echo "<pre>";
        die(print_r($var));
    }
    
    function getUrl($modulo,$controlador,$funcion,$parametros=false,$pagina=false){

        if($pagina==false){
            $pagina = "index";
        }

        $url = "$pagina.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";

        if($parametros!=false){
            foreach($parametros as $key => $valor){
                $url.="&$key=$valor";
            }
        }
        return $url;
    }


    function resolve(){
        
        $modulo = ucwords($_GET['modulo']); // modulo-> carpeta dentro del controlador
        $controlador = ucwords($_GET['controlador']); // controlador -> archivo controller dentro del modulo
        $funcion = $_GET['funcion']; // funcion -> metodo dentro de la clase del controlador

        if(is_dir("../controller/".$modulo)){
            if(is_file("../controller/".$modulo."/".$controlador."Controller.php")){

                require_once("../controller/".$modulo."/".$controlador."Controller.php");

                $controlador = $controlador."Controller";
                

                $objClase = new $controlador();

                if(method_exists($objClase,$funcion)){
                    $objClase->$funcion();
                }else{
                    echo "La funcion especificada no existe";
                }
            }else{
                echo "El controlador especificado no existe";
            }
        }else{
            echo "El modulo especificado no existe";
        }


    }

?>