<?php

include_once '../model/SeguimientoDeTanques/SeguimientoDeTanquesModel.php';

class SeguimientoDeTanquesController
{
    /* ================================
       MOSTRAR FORMULARIO
    ================================ */
    public function getConsulta()
    {
        session_start();

        if (!isset($_SESSION['auth'])) {
            redirect(getUrl("Login", "Login", "getLogin"));
            exit;
        }

        if (!isset($_SESSION['permisos']['SeguimientoTanques'])) {
           // redirect(getUrl("Errores", "Errores", "get403"));
            exit;
        }

        $obj = new SeguimientoDeTanquesModel();

        $sqlActividades = "
            SELECT id_actividad, nombre_actividad
            FROM actividad
            WHERE id_estado_actividad = 1
            ORDER BY nombre_actividad
        ";

        $actividades = $obj->select($sqlActividades);

        include_once '../view/seguimientoTanques/seguimientoDeTanques.php';
    }

    /* ================================
       ALIAS POST
    ================================ */
    public function postConsulta()
    {
        $this->postCreate();
    }

    /* ================================
       GUARDAR SEGUIMIENTO
    ================================ */
    public function postCreate()
    {
        session_start();

        // if (!isset($_SESSION['auth'])) {
        //     redirect(getUrl("Login", "Login", "getLogin"));
        //     exit;
        // }

        // if (
        //     !isset($_SESSION['permisos']['SeguimientoTanques']) ||
        //     !in_array('crear', $_SESSION['permisos']['SeguimientoTanques'])
        // ) {
        //   //  redirect(getUrl("Errores", "Errores", "get403"));
        //     exit;
        // }

        $obj = new SeguimientoDeTanquesModel();

        // 🔴 PHP ANTIGUO → isset()
        $id_seguimiento = isset($_POST['id_seguimiento']) ? $_POST['id_seguimiento'] : '';
        $id_actividad   = isset($_POST['id_actividad']) ? $_POST['id_actividad'] : '';
        $ph             = isset($_POST['ph']) ? $_POST['ph'] : '';
        $temperatura    = isset($_POST['temperatura']) ? $_POST['temperatura'] : '';

        if (!$this->camposObligatorios($id_seguimiento, $id_actividad, $ph, $temperatura)) {
            $this->alerta('danger', 'Debe completar los campos obligatorios');
            return;
        }

        $num_alevines  = isset($_POST['num_alevines']) ? (int) $_POST['num_alevines'] : 0;
        $num_machos    = isset($_POST['num_machos']) ? (int) $_POST['num_machos'] : 0;
        $num_hembras   = isset($_POST['num_hembras']) ? (int) $_POST['num_hembras'] : 0;
        $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

        $num_muertes = $num_machos + $num_hembras;
        $total       = $num_alevines - $num_muertes;

        $sql = "
            INSERT INTO seguimiento_detalle (
                id_seguimiento,
                id_actividad,
                ph,
                temperatura,
                num_alevines,
                num_muertes,
                num_machos,
                num_hembras,
                total,
                observaciones
            ) VALUES (
                $id_seguimiento,
                $id_actividad,
                '$ph',
                '$temperatura',
                $num_alevines,
                $num_muertes,
                $num_machos,
                $num_hembras,
                $total,
                '$observaciones'
            )
        ";

        if ($obj->insert($sql)) {
            $this->alerta('success', 'Seguimiento registrado correctamente');
        } else {
            $this->alerta('danger', 'No se pudo registrar el seguimiento');
        }
    }

    /* ================================
       VALIDACIONES
    ================================ */
    private function camposObligatorios($tanque, $actividad, $ph, $temperatura)
    {
        return $tanque != '' && $actividad != '' && $ph != '' && $temperatura != '';
    }

    /* ================================
       ALERTAS
    ================================ */
    private function alerta($tipo, $mensaje)
    {
        $_SESSION['alert'] = array(
            'type' => $tipo,
            'message' => $mensaje
        );

        redirect(getUrl(
    "SeguimientoDeTanques",
    "SeguimientoDeTanques",
    "getConsulta"


        ));
        exit;
    }
}
