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
                    u.foto_perfil,
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

    public function postActualizarFoto() {
        $documento = $_SESSION['documento'];
        
        if (!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] != 0) {
            $this->alerta('danger', 'Debe seleccionar una foto');
            return;
        }

        $archivo = $_FILES['foto_perfil'];
        $nombreArchivo = $archivo['name'];
        $tmpArchivo = $archivo['tmp_name'];
        $tamano = $archivo['size'];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($extension, $extensionesPermitidas)) {
            $this->alerta('danger', 'Solo se permiten imagenes JPG, PNG o GIF');
            return;
        }

        if ($tamano > 5242880) {
            $this->alerta('danger', 'La imagen no debe superar 5MB');
            return;
        }

        $carpetaDestino = "C:/ms4w/Apache/htdocs/GEOSALUD/uploads/fotos_perfil/";
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreUnico = $documento . '_' . time() . '.' . $extension;
        $rutaCompleta = $carpetaDestino . $nombreUnico;

        if (move_uploaded_file($tmpArchivo, $rutaCompleta)) {
            
            $sqlFotoAnterior = "SELECT foto_perfil FROM usuarios WHERE documento = '".$documento."'";
            $result = $this->model->select($sqlFotoAnterior);
            if ($result && pg_num_rows($result) > 0) {
                $row = pg_fetch_assoc($result);
                if (!empty($row['foto_perfil']) && file_exists($carpetaDestino . $row['foto_perfil'])) {
                    unlink($carpetaDestino . $row['foto_perfil']);
                }
            }

            $sql = "UPDATE usuarios SET foto_perfil = '".pg_escape_string($nombreUnico)."' WHERE documento = '".$documento."'";
            
            if ($this->model->update($sql)) {
                $this->alerta('success', 'Foto actualizada correctamente');
            } else {
                $this->alerta('danger', 'Error al guardar la foto en la base de datos');
            }
        } else {
            $this->alerta('danger', 'Error al subir la foto');
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

    /**
     * VALIDACIÓN PROFESIONAL Y EXHAUSTIVA DE CORREO ELECTRÓNICO
     * Bloquea correos como: 1@correo.com, a@b.com, test@test.com, etc.
     * 
     * @param string $correo - Correo electrónico a validar
     * @return mixed - true si es válido, string con mensaje de error si no lo es
     */
    private function validarCorreoProfesional($correo) {
        
        // 1. VALIDAR QUE NO ESTÉ VACÍO
        if (empty($correo)) {
            return 'El correo electronico es obligatorio';
        }

        // 2. CONVERTIR A MINÚSCULAS Y ELIMINAR ESPACIOS
        $correo = strtolower(trim($correo));

        // 3. VALIDAR LONGITUD TOTAL
        if (strlen($correo) < 8) {
            return 'El correo electronico debe tener al menos 8 caracteres';
        }
        
        if (strlen($correo) > 100) {
            return 'El correo electronico es demasiado largo (maximo 100 caracteres)';
        }

        // 4. VALIDAR QUE CONTENGA EXACTAMENTE UN @
        if (substr_count($correo, '@') != 1) {
            return 'El correo debe contener exactamente un simbolo @';
        }

        // 5. SEPARAR LOCAL Y DOMINIO
        $partes = explode('@', $correo);
        $local = $partes[0];
        $dominio = $partes[1];

        // 6. VALIDAR PARTE LOCAL (antes del @)
        // Mínimo 3 caracteres en la parte local
        if (strlen($local) < 6) {
            return 'La parte del correo antes del @ debe tener al menos 6 caracteres';
        }

        if (strlen($local) > 30) {
            return 'La parte del correo antes del @ es demasiado larga';
        }

        // No puede ser solo números
        if (preg_match('/^[0-9]+$/', $local)) {
            return 'El correo no puede contener solo numeros antes del @';
        }

        // Debe contener al menos una letra
        if (!preg_match('/[a-z]/', $local)) {
            return 'El correo debe contener al menos una letra antes del @';
        }

        // No puede empezar o terminar con punto, guión o guión bajo
        if (preg_match('/^[._-]/', $local) || preg_match('/[._-]$/', $local)) {
            return 'El correo no puede empezar o terminar con puntos, guiones o guiones bajos';
        }

        // No puede tener puntos consecutivos
        if (strpos($local, '..') !== false) {
            return 'El correo no puede contener puntos consecutivos';
        }

        // Validar caracteres permitidos en la parte local
        if (!preg_match('/^[a-z0-9._+-]+$/', $local)) {
            return 'El correo contiene caracteres no validos antes del @';
        }

        // 7. VALIDAR DOMINIO (después del @)
        if (strlen($dominio) < 4) {
            return 'El dominio del correo debe tener al menos 4 caracteres';
        }

        // Debe contener al menos un punto
        if (strpos($dominio, '.') === false) {
            return 'El dominio debe contener al menos un punto (ejemplo: gmail.com)';
        }

        // No puede empezar o terminar con punto o guión
        if (preg_match('/^[.-]/', $dominio) || preg_match('/[.-]$/', $dominio)) {
            return 'El dominio no puede empezar o terminar con punto o guion';
        }

        // No puede tener puntos o guiones consecutivos
        if (strpos($dominio, '..') !== false || strpos($dominio, '--') !== false) {
            return 'El dominio no puede tener puntos o guiones consecutivos';
        }

        // Validar caracteres del dominio
        if (!preg_match('/^[a-z0-9.-]+$/', $dominio)) {
            return 'El dominio contiene caracteres no validos';
        }

        // 8. VALIDAR ESTRUCTURA DEL DOMINIO
        $partesDominio = explode('.', $dominio);
        
        // Debe tener al menos 2 partes (ejemplo: gmail.com)
        if (count($partesDominio) < 2) {
            return 'El dominio debe tener al menos un punto (ejemplo: gmail.com)';
        }

        // Validar cada parte del dominio
        foreach ($partesDominio as $parte) {
            if (strlen($parte) < 2) {
                return 'Cada parte del dominio debe tener al menos 2 caracteres';
            }
            
            // Cada parte debe contener al menos una letra
            if (!preg_match('/[a-z]/', $parte)) {
                return 'Cada parte del dominio debe contener al menos una letra';
            }
        }

        // 9. VALIDAR EXTENSIÓN DEL DOMINIO (TLD - Top Level Domain)
        $extension = end($partesDominio);
        
        if (strlen($extension) < 2 || strlen($extension) > 6) {
            return 'La extension del dominio debe tener entre 2 y 6 caracteres';
        }

        // La extensión debe ser solo letras
        if (!preg_match('/^[a-z]+$/', $extension)) {
            return 'La extension del dominio solo puede contener letras';
        }

        // 10. VALIDAR EXTENSIONES CONOCIDAS
        $extensionesValidas = array(
            // Genéricas
            'com', 'net', 'org', 'edu', 'gov', 'mil', 'int',
            // Nuevas genéricas
            'info', 'biz', 'name', 'pro', 'aero', 'coop', 'museum',
            'travel', 'mobi', 'tel', 'asia', 'cat', 'jobs', 'post',
            // Tecnología
            'tech', 'io', 'ai', 'app', 'dev', 'cloud', 'digital',
            // América Latina
            'co', 'mx', 'ar', 'cl', 'pe', 'uy', 'ec', 've', 'py', 'bo',
            'cr', 'pa', 'gt', 'hn', 'sv', 'ni', 'do', 'cu', 'pr',
            // Otros países comunes
            'es', 'uk', 'fr', 'de', 'it', 'ca', 'au', 'nz', 'jp', 'cn',
            'in', 'br', 'ru', 'za', 'us'
        );

        if (!in_array($extension, $extensionesValidas)) {
            return 'La extension del dominio no es valida o no esta soportada';
        }

        // 11. VALIDAR DOMINIOS COMUNES PROFESIONALES
        $nombreDominio = $partesDominio[count($partesDominio) - 2];
        
        // Evitar dominios muy cortos o genéricos
        if (strlen($nombreDominio) < 3) {
            return 'El nombre del dominio es demasiado corto';
        }

        // 12. LISTA DE DOMINIOS GENÉRICOS NO PERMITIDOS
        $dominiosGenericos = array(
            'correo', 'email', 'mail', 'test', 'prueba', 'ejemplo',
            'example', 'demo', 'temp', 'temporal', 'fake', 'falso'
        );

        if (in_array($nombreDominio, $dominiosGenericos)) {
            return 'No se permiten correos con dominios genericos o de prueba';
        }

        // 13. VALIDAR CON FILTER_VAR DE PHP
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return 'El formato del correo electronico no es valido';
        }

        // 14. VALIDACIÓN ADICIONAL: Expresión regular completa
        $patron = '/^[a-z0-9]([a-z0-9._+-]*[a-z0-9])?@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$/';
        if (!preg_match($patron, $correo)) {
            return 'El formato del correo no cumple con los estandares profesionales';
        }

        // 15. VALIDAR DOMINIOS TEMPORALES/DESECHABLES
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

        // 16. TODO CORRECTO
        return true;
    }

    private function alerta($tipo, $mensaje) {
        $_SESSION['alert'] = array('type' => $tipo, 'message' => $mensaje);
        echo "<script>window.location.href='".getUrl("InformacionPersonal","InformacionPersonal","getInformacion")."';</script>";
        exit;
    }
}
?>