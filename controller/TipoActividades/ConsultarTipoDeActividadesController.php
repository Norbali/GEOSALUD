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
        if (empty($nombre_actividad) || empty($id_estado_actividad)) {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Debe completar todos los campos antes de guardar la actividad'
            );
            redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
            return;
        }



        // VALIDAR DUPLICADOS
        if ($this->existeActividad($obj, $nombre_actividad)) {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Ya existe una actividad con este nombre, por favor ingresa un nombre diferente'
            );
            redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
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

        redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
    }

    // ACTUALIZAR ACTIVIDAD
    public function postUpdate()
    {
        $obj = new ConsultarTipoDeActividadesModel();

        $id_actividad = isset($_POST['id_actividad']) ? $_POST['id_actividad'] : null;
        $nombre_actividad = isset($_POST['nombre_actividad']) ? $_POST['nombre_actividad'] : null;
        $id_estado_actividad = isset($_POST['id_estado_actividad']) ? $_POST['id_estado_actividad'] : null;

        // VALIDAR CAMPOS
        if (empty($id_actividad) || empty($nombre_actividad)) {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Debe completar todos los campos para actualizar la actividad'
            );
            redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
            return;
        }

        // VALIDAR DUPLICADO 
        $sqlDuplicado = "
        SELECT 1
        FROM actividad
        WHERE LOWER(nombre_actividad) = LOWER('$nombre_actividad')
          AND id_actividad <> $id_actividad
        LIMIT 1
    ";

        $duplicado = $obj->select($sqlDuplicado);

        if (pg_num_rows($duplicado) > 0) {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Ya existe otra actividad con este nombre'
            );
            redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
            return;
        }

        // ACTUALIZAR
        $sql = "
        UPDATE actividad
        SET nombre_actividad = '$nombre_actividad'
        WHERE id_actividad = $id_actividad
    ";

        $ejecutar = $obj->update($sql);

        if ($ejecutar) {
            $_SESSION['alert'] = array(
                'type' => 'success',
                'message' => 'Actividad actualizada correctamente'
            );
        } else {
            $_SESSION['alert'] = array(
                'type' => 'danger',
                'message' => 'Error al actualizar la actividad'
            );
        }

        redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
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
            redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
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

        redirect(getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'getConsulta'));
    }
    private function existeActividad($obj, $nombre_actividad)
    {
        $sql = "
        SELECT 1
        FROM actividad
        WHERE LOWER(nombre_actividad) = LOWER('$nombre_actividad')
        LIMIT 1
    ";

        $resultado = $obj->select($sql);

        return pg_num_rows($resultado) > 0;
    }
}
