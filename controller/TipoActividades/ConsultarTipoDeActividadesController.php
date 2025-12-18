<?php

include_once '../model/TipoActividades/ConsultarTipoDeActividadesModel.php';

class ConsultarTipoDeActividadesController
{

    //  CONSULTAR ACTIVIDADES
    public function getConsulta()
    {
        $obj = new ConsultarTipoDeActividadesModel();

        // ORDEN PERMITIDOS
        $ordenPermitido = array(
            'id_asc'   => 'a.id_actividad ASC',
            'id_desc'  => 'a.id_actividad DESC',
            'nom_asc'  => 'a.nombre_actividad ASC',
            'nom_desc' => 'a.nombre_actividad DESC'
        );

        // ORDEN POR DEFECTO
        $orden = 'a.id_actividad ASC';

        // VALIDAR ORDEN RECIBIDO
        if (isset($_GET['orden']) && array_key_exists($_GET['orden'], $ordenPermitido)) {
            $orden = $ordenPermitido[$_GET['orden']];
        }

        $sql = "
        SELECT 
            a.id_actividad,
            a.nombre_actividad,
            a.id_estado_actividad,
            ea.nombre_estado_actividades
        FROM actividad a
        JOIN estado_actividad ea
            ON a.id_estado_actividad = ea.id_estado_actividades
        ORDER BY $orden
    ";

        $actividades = $obj->select($sql);

        $sqlEstados = "
        SELECT id_estado_actividades, nombre_estado_actividades 
        FROM estado_actividad
    ";
        $estados = $obj->select($sqlEstados);

        include_once '../view/tipoActividades/ConsultarActividades.php';
    }

    //  CREAR ACTIVIDAD
    public function postCreate()
    {
        session_start();
        $obj = new ConsultarTipoDeActividadesModel();

        $nombre = isset($_POST['nombre_actividad']) ? trim($_POST['nombre_actividad']) : '';
        $estado = isset($_POST['id_estado_actividad']) ? trim($_POST['id_estado_actividad']) : '';

        // CAMPOS OBLIGATORIOS 
        if (!$this->camposObligatorios($nombre, $estado)) {
            $this->alerta(
                'danger',
                'Debe completar todos los campos antes de guardar la actividad'
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
            INSERT INTO actividad (nombre_actividad, id_estado_actividad)
            VALUES ('$nombre', '$estado')
        ";

        if ($obj->insert($sql)) {
            $this->alerta(
                'success',
                'Actividad registrada correctamente'
            );
        } else {
            $this->alerta(
                'danger',
                'No se pudo registrar la actividad'
            );
        }
    }

    // EDITAR ACTIVIDAD

    public function postUpdate()
    {
        session_start();
        $obj = new ConsultarTipoDeActividadesModel();

        $id     = isset($_POST['id_actividad']) ? trim($_POST['id_actividad']) : '';
        $nombre = isset($_POST['nombre_actividad']) ? trim($_POST['nombre_actividad']) : '';
        $estado = isset($_POST['id_estado_actividad']) ? trim($_POST['id_estado_actividad']) : '';

        // CAMPOS OBLIGATORIOS 
        if ($id === '' || !$this->camposObligatorios($nombre, $estado)) {
            $this->alerta(
                'danger',
                'Debe completar todos los campos antes de guardar los cambios'
            );
            return;
        }

        // VALIDACIÓN DE TEXTO 
        if (!$this->soloTexto($nombre)) {
            $this->alerta(
                'danger',
                'Los datos ingresados contienen caracteres no permitidos, debe ingresar solo texto'
            );
            return;
        }

        // VALIDAR DUPLICADOS 
        $sqlDuplicado = "
        SELECT 1
        FROM actividad
        WHERE LOWER(nombre_actividad) = LOWER('$nombre')
        AND id_actividad <> $id
    ";

        if (pg_num_rows($obj->select($sqlDuplicado)) > 0) {
            $this->alerta(
                'danger',
                'Ya existe una actividad con este nombre, por favor ingresa un nombre diferente'
            );
            return;
        }

        // UPDATE 
        $sql = "
        UPDATE actividad
        SET nombre_actividad = '$nombre',
            id_estado_actividad = '$estado'
        WHERE id_actividad = $id
    ";

        if ($obj->update($sql)) {
            $this->alerta(
                'success',
                'Actividad actualizada correctamente'
            );
        } else {
            $this->alerta(
                'danger',
                'No se pudo actualizar la actividad'
            );
        }
    }
    // INHABILITAR ACTIVIDAD
    public function postInhabilitar()
    {
        session_start();
        $obj = new ConsultarTipoDeActividadesModel();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // VALIDAR ID PERMITIDO
        if ($id <= 0) {
            $this->alerta('danger', 'ID inválido');
            return;
        }

        // VERIFICAR ESTADO ACTUAL
        $sqlCheck = "
        SELECT id_estado_actividad
        FROM actividad
        WHERE id_actividad = $id
    ";

        $result = $obj->select($sqlCheck);

        if (pg_num_rows($result) === 0) {
            $this->alerta('danger', 'La actividad no existe');
            return;
        }

        $row = pg_fetch_assoc($result);

        // SI YA ESTÁ INACTIVA 
        if ($row['id_estado_actividad'] == 2) {
            $this->alerta('danger', 'La actividad ya se encuentra inhabilitada');
            return;
        }

        // INHABILITAR
        $sql = "
        UPDATE actividad
        SET id_estado_actividad = 2
        WHERE id_actividad = $id
    ";

        if ($obj->update($sql)) {
            $this->alerta('success', 'Actividad inhabilitada correctamente');
        } else {
            $this->alerta('danger', 'No se pudo inhabilitar la actividad');
        }
    }

    //  MÉTODOS DE VALIDACIÓN

    //CAMPOS OBLIGATORIOS
    private function camposObligatorios($nombre, $estado)
    {
        return $nombre !== '' && $estado !== '';
    }

    // SOLO TEXTO SIN NÚMEROS U OTROS CARACTERES
    private function soloTexto($texto)
    {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $texto);
    }

    // VALIDAR DUPLICADOS
    private function existeActividad($obj, $nombre)
    {
        $sql = "
            SELECT 1
            FROM actividad
            WHERE LOWER(nombre_actividad) = LOWER('$nombre')
        ";

        $result = $obj->select($sql);
        return pg_num_rows($result) > 0;
    }


    // ALERTA Y REDIRECCIÓN DE LA PÁGINA

    private function alerta($tipo, $mensaje)
    {
        $_SESSION['alert'] = array(
            'type' => $tipo,
            'message' => $mensaje
        );

        redirect(getUrl(
            "TipoActividades",
            "ConsultarTipoDeActividades",
            "getConsulta"
        ));
        exit;
    }
}
