<?php

include_once '../model/TipoActividades/ConsultarTipoDeActividadesModel.php';
class ConsultarTipoDeActividadesController{
        
       public function getConsulta(){
            $obj = new ConsultarTipoDeActividadesModel();
            $sql= "SELECT 
  a.id_actividad,
  a.nombre_actividad,
  a.id_estado_actividad,
  ea.nombre_estado_actividades
FROM actividad a
JOIN estado_actividad ea
  ON a.id_estado_actividad = ea.id_estado_actividades
ORDER BY a.id_actividad ASC;

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

        public function postInhabilitar()
{
    $obj = new ConsultarTipoDeActividadesModel();

    if (!isset($_GET['id'])) {
        $_SESSION['alert'] = array(
            'type' => 'danger',
            'message' => 'ID no recibido'
        );
        redirect(getUrl('TipoActividades','ConsultarTipoDeActividades','getConsulta'));
        return;
    }

    $id = $_GET['id'];

    $sql = "
        UPDATE actividad
        SET id_estado_actividad = 2
        WHERE id_actividad = $id
    ";

    $ejecutar = $obj->update($sql);

    if ($ejecutar) {
        $_SESSION['alert'] = array(
            'type' => 'success',
            'message' => 'Actividad inhabilitada correctamente'
        );
    } else {
        $_SESSION['alert'] = array(
            'type' => 'danger',
            'message' => 'Error al inhabilitar la actividad'
    );
    }

    redirect(getUrl('TipoActividades','ConsultarTipoDeActividades','getConsulta'));
}


    }
?>
