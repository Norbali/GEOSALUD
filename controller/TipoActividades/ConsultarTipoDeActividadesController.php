<?php

include_once '../model/TipoActividades/ConsultarTipoDeActividadesModel.php';

class ConsultarTipoDeActividadesController
{
    public function getConsulta()
    {
        $obj = new ConsultarTipoDeActividadesModel();

        $sql = "
            SELECT 
                a.id_actividad,
                a.nombre_actividad,
                a.fecha_creacion,
                a.id_estado_actividad,
                ea.nombre_estado_actividades
            FROM actividad a
            JOIN estado_actividad ea
                ON a.id_estado_actividad = ea.id_estado_actividades
            ORDER BY a.id_actividad ASC
        ";

        $actividades = $obj->select($sql);
        include_once '../view/tipoActividades/ConsultarActividades.php';
    }

    // CREAR ACTIVIDAD
    public function postCreate()
    {
        $obj = new ConsultarTipoDeActividadesModel();

        $nombre_actividad = $_POST['nombre_actividad'];
        $id_estado_actividad = $_POST['id_estado_actividad'];

        // CAMPOS OBLIGATORIOS 
        if (!$this->camposObligatorios($nombre, $estado)) {
            $this->alerta(
                'danger',
                'Debe completar todos los campos antes de guardar la actividad'
            );
            return;
        }

        // VALIDACIÓN DE CARACTERES 
        if (!$this->soloTexto($nombre)) {
            $this->alerta(
                'danger',
                'Los datos ingresados contienen caracteres no permitidos, debe ingresar solo texto'
            );
            return;
        }

        //VALIDAR DUPLICADOS 
        if ($this->existeActividad($obj, $nombre)) {
            $this->alerta(
                'danger',
                'Ya existe una actividad con este nombre, por favor ingresa un nombre diferente'
            );
            return;
        }

        //REGISTRO
        $sql = "
            INSERT INTO actividad (nombre_actividad, fecha_creacion, id_estado_actividad)
            VALUES ('$nombre_actividad', NOW(), $id_estado_actividad)
        ";

        $ejecutar = $obj->insert($sql);

        if ($ejecutar) {
            $_SESSION['alert'] = array(
                'type' => 'success',
                'message' => 'Actividad registrada correctamente'
            );
        } else {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Error al registrar la actividad'
            );
        }

        redirect(getUrl('TipoActividades','ConsultarTipoDeActividades','getConsulta'));
    }

    // INHABILITAR
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
