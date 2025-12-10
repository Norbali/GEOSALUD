<?php
  
   include_once '..'; 
class RegistroDeTiposDeActividadesController{
        
       public function getCreate(){
            $obj = new RegistroDeActividades();
            $sql= "SELECT * FROM actividad";
            $roles = $obj->select($sql);
            include_once '../view/tipoActividades/registroTipoDeActividades.php';
        } 

        public function postCreate(){
            $obj = new RegistroDeActividades();

            $id_actividad  = $_POST['id_actividad'];
            $nombre_actividad  = $_POST['nombre_actividad'];
            $id_estado_actividad = $_POST['id_estado_actividad'];
          
            $sql = "INSERT INTO usuarios (id_actividad, nombre_actividad, id_estado_actividad)
            VALUES ('$id_documento', '$nombre_actividad', '$id_estado_actividad')";

            
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