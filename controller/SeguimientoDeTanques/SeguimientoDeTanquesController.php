<?php
session_start();
include_once '../model/SeguimientoDeTanques/SeguimientoDeTanquesModel.php';

class SeguimientoDeTanquesController
{
    /* ===============================
       MOSTRAR FORMULARIO
    ================================*/
    public function getConsulta()
    {
        $obj = new SeguimientoDeTanquesModel();

        /* ACTIVIDADES */
        $actividades = array();
        $rsActividades = $obj->select("
            SELECT id_actividad, nombre_actividad
            FROM actividad
            WHERE id_estado_actividad = 1
            ORDER BY nombre_actividad
        ");
        if ($rsActividades) {
            while ($row = pg_fetch_assoc($rsActividades)) {
                $actividades[] = $row;
            }
        }

        /* TANQUES */
        $tanques = array();
        $rsTanques = $obj->select("
            SELECT id_tanque, nombre_tanque
            FROM tanque
            WHERE id_estado_tanque = 1
            ORDER BY nombre_tanque
        ");
        if ($rsTanques) {
            while ($row = pg_fetch_assoc($rsTanques)) {
                $tanques[] = $row;
            }
        }

        /* RESPONSABLE */
        $nombreResponsable = '';
        if (isset($_SESSION['documento'])) {
            $rsResp = $obj->select("
                SELECT COALESCE(nombre,'') || ' ' || COALESCE(apellido,'') AS nombre
                FROM usuarios
                WHERE documento = '{$_SESSION['documento']}'
            ");
            if ($rsResp && $row = pg_fetch_assoc($rsResp)) {
                $nombreResponsable = $row['nombre'];
            }
        }

        include_once '../view/seguimientoTanques/seguimientoDeTanques.php';
    }

    /* ===============================
       REGISTRAR SEGUIMIENTO
    ================================*/
    public function postCreate()
    {
        $obj = new SeguimientoDeTanquesModel();
        $errores = array();

        /* ===============================
           VALIDACIONES USUARIO
        ================================*/
        if (empty($_POST['id_tanque'])) {
            $errores[] = 'Debe seleccionar un tanque';
        }

        if (empty($_POST['id_actividad'])) {
            $errores[] = 'Debe seleccionar una actividad';
        }

        if (isset($_POST['ph']) && $_POST['ph'] !== '' && $_POST['ph'] < 0) {
            $errores[] = 'El pH no puede ser negativo';
        }

        if (isset($_POST['cloro']) && $_POST['cloro'] !== '' && $_POST['cloro'] < 0) {
            $errores[] = 'El cloro no puede ser negativo';
        }

        if ((int)$_POST['num_alevines'] < 0) {
            $errores[] = 'Los alevines no pueden ser negativos';
        }

        if ((int)$_POST['num_machos'] < 0 || (int)$_POST['num_hembras'] < 0) {
            $errores[] = 'Las muertes no pueden ser negativas';
        }

        if (((int)$_POST['num_machos'] + (int)$_POST['num_hembras']) > (int)$_POST['num_alevines']) {
            $errores[] = 'Las muertes no pueden ser mayores que los alevines';
        }

        /* ===============================
           SI HAY ERRORES → VOLVER
        ================================*/
        if (!empty($errores)) {
            $_SESSION['errores_formulario'] = $errores;
            redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
            exit;
        }

        /* ===============================
           DATOS LIMPIOS
        ================================*/
        $id_tanque = (int)$_POST['id_tanque'];
        $id_actividad = (int)$_POST['id_actividad'];
        $ph = ($_POST['ph'] !== '') ? $_POST['ph'] : null;
        $temperatura = ($_POST['temperatura'] !== '') ? $_POST['temperatura'] : null;
        $cloro = ($_POST['cloro'] !== '') ? $_POST['cloro'] : null;
        $num_alevines = (int)$_POST['num_alevines'];
        $num_machos = (int)$_POST['num_machos'];
        $num_hembras = (int)$_POST['num_hembras'];
        $observaciones = $_POST['observaciones'];
        $documento = $_SESSION['documento'];
        $fecha = date('Y-m-d');

        /* ===============================
           INSERT SEGUIMIENTO
        ================================*/
        $sql = "
            INSERT INTO seguimiento (id_tanque, id_user_responsable, fecha)
            VALUES ($id_tanque, '$documento', '$fecha')
        ";

        if (!$obj->insert($sql)) {
            $_SESSION['errores_formulario'] = array(
                'Error al registrar el seguimiento',
                pg_last_error()
            );
            redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
            exit;
        }

        /* ===============================
           OBTENER ID
        ================================*/
        $rs = $obj->select("
            SELECT currval(pg_get_serial_sequence('seguimiento','id_seguimiento')) AS id
        ");
        $row = pg_fetch_assoc($rs);
        $id_seguimiento = $row['id'];

        /* ===============================
           INSERT DETALLE
        ================================*/
        $num_muertes = $num_machos + $num_hembras;
        $total = $num_alevines - $num_muertes;

        $sqlDetalle = "
            INSERT INTO seguimiento_detalle (
                id_seguimiento, id_actividad, ph, temperatura, cloro,
                num_alevines, num_muertes, num_machos, num_hembras, total, observaciones
            ) VALUES (
                $id_seguimiento,
                $id_actividad,
                " . ($ph !== null ? "'$ph'" : "NULL") . ",
                " . ($temperatura !== null ? "'$temperatura'" : "NULL") . ",
                " . ($cloro !== null ? "'$cloro'" : "NULL") . ",
                $num_alevines,
                $num_muertes,
                $num_machos,
                $num_hembras,
                $total,
                '$observaciones'
            )
        ";

        if (!$obj->insert($sqlDetalle)) {
            $_SESSION['errores_formulario'] = array(
                'Error al registrar el detalle del seguimiento',
                pg_last_error()
            );
            redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
            exit;
        }

        /* ===============================
           ÉXITO
        ================================*/
        $_SESSION['exito'] = 'Seguimiento registrado correctamente';
        redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
        exit;
    }
}
