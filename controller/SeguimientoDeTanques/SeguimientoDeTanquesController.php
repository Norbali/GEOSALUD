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
        if (!isset($_SESSION['documento'])) {
            redirect(getUrl("Login", "Login", "index"));
            exit;
        }

        $obj = new SeguimientoDeTanquesModel();

        /* ===============================
           ACTIVIDADES
        ================================*/
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

        /* ===============================
           TANQUES
        ================================*/
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

        /* ===============================
           RESPONSABLE (NOMBRE + APELLIDO)
        ================================*/
        $nombreResponsable = '';

        $rsResponsable = $obj->select("
            SELECT 
                COALESCE(nombre,'') || ' ' || COALESCE(apellido,'') AS nombre_completo
            FROM usuarios
            WHERE documento = '{$_SESSION['documento']}'
        ");

        if ($rsResponsable && $row = pg_fetch_assoc($rsResponsable)) {
            $nombreResponsable = $row['nombre_completo'];
        }

        /* ===============================
           CARGAR VISTA
        ================================*/
        include_once '../view/seguimientoTanques/seguimientoDeTanques.php';
    }

    /* ===============================
       REGISTRAR SEGUIMIENTO
    ================================*/
    public function postCreate()
    {
        if (!isset($_SESSION['documento'])) {
            redirect(getUrl("Login", "Login", "index"));
            exit;
        }

        $obj = new SeguimientoDeTanquesModel();

        $id_tanque     = $_POST['id_tanque'];
        $id_actividad  = $_POST['id_actividad'];
        $ph            = $_POST['ph'] !== '' ? $_POST['ph'] : null;
        $temperatura   = $_POST['temperatura'] !== '' ? $_POST['temperatura'] : null;
        $cloro         = $_POST['cloro'] !== '' ? $_POST['cloro'] : null;
        $num_alevines  = (int)$_POST['num_alevines'];
        $num_machos    = (int)$_POST['num_machos'];
        $num_hembras   = (int)$_POST['num_hembras'];
        $observaciones = $_POST['observaciones'];

        $documento_usuario = $_SESSION['documento'];
        $fecha = date('Y-m-d');

        /* ===============================
           INSERT SEGUIMIENTO
        ================================*/
        $sqlSeguimiento = "
            INSERT INTO seguimiento (id_tanque, id_user_responsable, fecha)
            VALUES ($id_tanque, '$documento_usuario', '$fecha')
        ";

        if (!$obj->insert($sqlSeguimiento)) {
            redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
            exit;
        }

        /* ===============================
           OBTENER ID (MISMA SESIÓN)
        ================================*/
        $rsId = $obj->select("
            SELECT currval(pg_get_serial_sequence('seguimiento','id_seguimiento')) AS id
        ");

        if (!$rsId || !($row = pg_fetch_assoc($rsId))) {
            redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
            exit;
        }

        $id_seguimiento = $row['id'];

        /* ===============================
           INSERT DETALLE
        ================================*/
        $num_muertes = $num_machos + $num_hembras;
        $total = $num_alevines - $num_muertes;

        $sqlDetalle = "
            INSERT INTO seguimiento_detalle (
                id_seguimiento,
                id_actividad,
                ph,
                temperatura,
                cloro,
                num_alevines,
                num_muertes,
                num_machos,
                num_hembras,
                total,
                observaciones
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

        $obj->insert($sqlDetalle);

        redirect(getUrl("SeguimientoDeTanques", "SeguimientoDeTanques", "getConsulta"));
        exit;
    }
}
