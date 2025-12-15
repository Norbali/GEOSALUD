<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

include_once '../model/InformacionPersonal/InformacionPersonalModel.php';

class InformacionPersonalController {

    private $model;

    public function __construct() {
        $this->model = new InformacionPersonalModel();
    }

    public function getInformacion() {
        $documento = $_SESSION['documento'];

        $sql = "SELECT 
                    u.documento,
                    u.nombre,
                    u.apellido,
                    u.telefono,
                    u.correo,
                    u.contrasena,
                    u.id_rol,
                    r.nombre_rol
                FROM usuarios u
                LEFT JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.documento = '".$documento."'";

        $usuario = $this->model->select($sql);

        if ($usuario && pg_num_rows($usuario) > 0) {
            $datosUsuario = pg_fetch_assoc($usuario);
        } else {
            $datosUsuario = null;
        }

        include_once "C:/ms4w/Apache/htdocs/GEOSALUD/view/informacionPersonal/InformacionPersonal.php";
    }

    public function postUpdate() {
        $documento = $_SESSION['documento'];
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
        $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
        $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

        if (!$this->camposObligatorios($nombre, $apellido, $telefono, $correo)) {
            $this->alerta('danger', 'Debe completar todos los campos obligatorios');
            return;
        }

        if (!$this->soloTexto($nombre) || !$this->soloTexto($apellido)) {
            $this->alerta('danger', 'El nombre y apellido solo deben contener letras');
            return;
        }

        if (!$this->soloNumeros($telefono) || strlen($telefono) != 10) {
            $this->alerta('danger', 'El telefono debe contener exactamente 10 digitos');
            return;
        }

        // VALIDACIÓN PROFESIONAL DEL CORREO
        $resultadoValidacion = $this->validarCorreoProfesional($correo);
        if ($resultadoValidacion !== true) {
            $this->alerta('danger', $resultadoValidacion);
            return;
        }

        $sql = "UPDATE usuarios SET 
                    nombre = '".pg_escape_string($nombre)."', 
                    apellido = '".pg_escape_string($apellido)."', 
                    telefono = '".pg_escape_string($telefono)."', 
                    correo = '".pg_escape_string(strtolower($correo))."' 
                WHERE documento = '".$documento."'";

        if ($this->model->update($sql)) {
            $_SESSION['nombreCompleto'] = $nombre . ' ' . $apellido;
            $this->alerta('success', 'Informacion actualizada correctamente');
        } else {
            $this->alerta('danger', 'Error al actualizar la informacion');
        }
    }

    public function postCambiarContrasena() {
        $documento = $_SESSION['documento'];
        $contrasenaActual = isset($_POST['contrasena_actual']) ? trim($_POST['contrasena_actual']) : '';
        $contrasenaNueva = isset($_POST['contrasena_nueva']) ? trim($_POST['contrasena_nueva']) : '';
        $contrasenaConfirmar = isset($_POST['contrasena_confirmar']) ? trim($_POST['contrasena_confirmar']) : '';

        if (empty($contrasenaActual) || empty($contrasenaNueva) || empty($contrasenaConfirmar)) {
            $this->alerta('danger', 'Complete todos los campos');
            return;
        }

        $sqlVerificar = "SELECT contrasena FROM usuarios WHERE documento = '".$documento."'";
        $result = $this->model->select($sqlVerificar);
        
        if (!$result || pg_num_rows($result) == 0) {
            $this->alerta('danger', 'Usuario no encontrado');
            return;
        }
        
        $row = pg_fetch_assoc($result);

        if ($row['contrasena'] !== $contrasenaActual) {
            $this->alerta('danger', 'La contrasena actual no es correcta');
            return;
        }

        if ($contrasenaNueva !== $contrasenaConfirmar) {
            $this->alerta('danger', 'Las contrasenas nuevas no coinciden');
            return;
        }

        if (strlen($contrasenaNueva) < 6) {
            $this->alerta('danger', 'La contrasena debe tener al menos 6 caracteres');
            return;
        }

        $sql = "UPDATE usuarios SET contrasena = '".pg_escape_string($contrasenaNueva)."' WHERE documento = '".$documento."'";

        if ($this->model->update($sql)) {
            $this->alerta('success', 'Contrasena actualizada correctamente');
        } else {
            $this->alerta('danger', 'Error al actualizar la contrasena');
        }
    }

    /* ============================
       MÉTODOS DE VALIDACIÓN
    ============================ */

    private function camposObligatorios($nombre, $apellido, $telefono, $correo) {
        return !empty($nombre) && !empty($apellido) && !empty($telefono) && !empty($correo);
    }

    private function soloTexto($texto) {
        return preg_match('/^[a-zA-Z\s]+$/', $texto);
    }

    private function soloNumeros($numero) {
        return preg_match('/^[0-9]+$/', $numero);
    }

    private function validarCorreoProfesional($correo) {
        
        if (empty($correo)) {
            return 'El correo electronico es obligatorio';
        }

        $correo = strtolower(trim($correo));

        if (strlen($correo) < 10) {
            return 'El correo electronico debe tener al menos 10 caracteres';
        }
        
        if (strlen($correo) > 100) {
            return 'El correo electronico es demasiado largo (maximo 100 caracteres)';
        }

        if (substr_count($correo, '@') != 1) {
            return 'El correo debe contener exactamente un simbolo @';
        }

        $partes = explode('@', $correo);
        $local = $partes[0];
        $dominio = $partes[1];

        if (strlen($local) < 6) {
            return 'La parte del correo antes del @ debe tener al menos 6 caracteres';
        }

        if (strlen($local) > 64) {
            return 'La parte del correo antes del @ es demasiado larga';
        }

        if (preg_match('/^[0-9]+$/', $local)) {
            return 'El correo no puede contener solo numeros antes del @';
        }

        if (!preg_match('/[a-z]/', $local)) {
            return 'El correo debe contener al menos una letra antes del @';
        }

        if (preg_match('/^[._-]/', $local) || preg_match('/[._-]$/', $local)) {
            return 'El correo no puede empezar o terminar con puntos, guiones o guiones bajos';
        }

        if (strpos($local, '..') !== false) {
            return 'El correo no puede contener puntos consecutivos';
        }

        if (!preg_match('/^[a-z0-9._+-]+$/', $local)) {
            return 'El correo contiene caracteres no validos antes del @';
        }

        if (strlen($dominio) < 4) {
            return 'El dominio del correo debe tener al menos 4 caracteres';
        }

        if (strpos($dominio, '.') === false) {
            return 'El dominio debe contener al menos un punto (ejemplo: gmail.com)';
        }

        if (preg_match('/^[.-]/', $dominio) || preg_match('/[.-]$/', $dominio)) {
            return 'El dominio no puede empezar o terminar con punto o guion';
        }

        if (strpos($dominio, '..') !== false || strpos($dominio, '--') !== false) {
            return 'El dominio no puede tener puntos o guiones consecutivos';
        }

        if (!preg_match('/^[a-z0-9.-]+$/', $dominio)) {
            return 'El dominio contiene caracteres no validos';
        }

        $partesDominio = explode('.', $dominio);
        
        if (count($partesDominio) < 2) {
            return 'El dominio debe tener al menos un punto (ejemplo: gmail.com)';
        }

        foreach ($partesDominio as $parte) {
            if (strlen($parte) < 2) {
                return 'Cada parte del dominio debe tener al menos 2 caracteres';
            }
            
            if (!preg_match('/[a-z]/', $parte)) {
                return 'Cada parte del dominio debe contener al menos una letra';
            }
        }

        $extension = end($partesDominio);
        
        if (strlen($extension) < 2 || strlen($extension) > 6) {
            return 'La extension del dominio debe tener entre 2 y 6 caracteres';
        }

        if (!preg_match('/^[a-z]+$/', $extension)) {
            return 'La extension del dominio solo puede contener letras';
        }

        $extensionesValidas = array(
            'com', 'net', 'org', 'edu', 'gov', 'mil', 'int',
            'info', 'biz', 'name', 'pro', 'aero', 'coop', 'museum',
            'travel', 'mobi', 'tel', 'asia', 'cat', 'jobs', 'post',
            'tech', 'io', 'ai', 'app', 'dev', 'cloud', 'digital',
            'co', 'mx', 'ar', 'cl', 'pe', 'uy', 'ec', 've', 'py', 'bo',
            'cr', 'pa', 'gt', 'hn', 'sv', 'ni', 'do', 'cu', 'pr',
            'es', 'uk', 'fr', 'de', 'it', 'ca', 'au', 'nz', 'jp', 'cn',
            'in', 'br', 'ru', 'za', 'us'
        );

        if (!in_array($extension, $extensionesValidas)) {
            return 'La extension del dominio no es valida o no esta soportada';
        }

        $nombreDominio = $partesDominio[count($partesDominio) - 2];
        
        if (strlen($nombreDominio) < 3) {
            return 'El nombre del dominio es demasiado corto';
        }

        $dominiosGenericos = array(
            'correo', 'email', 'mail', 'test', 'prueba', 'ejemplo',
            'example', 'demo', 'temp', 'temporal', 'fake', 'falso'
        );

        if (in_array($nombreDominio, $dominiosGenericos)) {
            return 'No se permiten correos con dominios genericos o de prueba';
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return 'El formato del correo electronico no es valido';
        }

        $patron = '/^[a-z0-9]([a-z0-9._+-]*[a-z0-9])?@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$/';
        if (!preg_match($patron, $correo)) {
            return 'El formato del correo no cumple con los estandares profesionales';
        }

        $dominiosTemporales = array(
            'tempmail', '10minutemail', 'guerrillamail', 'mailinator',
            'maildrop', 'throwaway', 'trash-mail', 'getnada', 'yopmail',
            'fakeinbox', 'trashmail', 'mailnesia', 'spamgourmet'
        );

        foreach ($dominiosTemporales as $tempDomain) {
            if (strpos($dominio, $tempDomain) !== false) {
                return 'No se permiten correos temporales o desechables';
            }
        }

        return true;
    }

    private function alerta($tipo, $mensaje) {
        $_SESSION['alert'] = array('type' => $tipo, 'message' => $mensaje);
        echo "<script>window.location.href='".getUrl("InformacionPersonal","InformacionPersonal","getInformacion")."';</script>";
        exit;
    }
}
?>