<?php

include_once '../model/TipoActividades/ConsultarTipoDeActividadesModel.php';
class ConsultarTipoDeActividadesController{
        
       public function getConsulta(){
            $obj = new ConsultarTipoDeActividadesModel();
            $sql= "SELECT 
  a.id_actividad,
  a.nombre_actividad,
  ea.nombre_estado_actividades
FROM actividad a
JOIN estado_actividad ea
  ON a.id_estado_actividad = ea.id_estado_actividades;
";
            $actividades = $obj->select($sql);
            include_once '../view/tipoActividades/ConsultarActividades.php';
        } 
        public function postConsulta(){
            $obj = new ConsultarTipoDeActividadesModel();

            $id_actividad  = $_POST['id_actividad'];
            $nombre_actividad  = $_POST['nombre_actividad'];
            $id_estado_actividad = $_POST['id_estado_actividad'];
          
            $sql = "INSERT INTO actividad (id_actividad, nombre_actividad, id_estado_actividad)
            VALUES ('$id_actividad', '$nombre_actividad', '$id_estado_actividad')";

            
            $ejecutar = $obj->insert($sql);
            if ($ejecutar) {
                echo "actividad registrada  exitosamente";
                //redirect(getUrl("","",""));

            }else{
                echo "error en la insercion";
            }
        }

    }
?>
