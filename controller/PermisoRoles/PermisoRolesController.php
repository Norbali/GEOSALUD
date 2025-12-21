<?php

include_once '../model/PermisoRoles/PermisoRolesModel.php';

class PermisoRolesController
{

    private $model;
    private $errores = array();


    //  Constructor

    public function __construct()
    {
        $this->model = new PermisoRolesModel();
    }


    //   Consultar permisos de roles, Vista principal

    public function getConsultar()
    {
        try {
            // Obtener todos los datos necesarios
            $roles = $this->obtenerRoles();
            $modulos = $this->obtenerModulos();
            $acciones = $this->obtenerAcciones();
            $permisos = $this->obtenerPermisos();

            // Obtener mensajes de sesión
            $mensaje = $this->obtenerMensajeSesion();
            $tipo_mensaje = $this->obtenerTipoMensajeSesion();

            // Limpiar mensajes de sesión
            $this->limpiarMensajesSesion();

            // Cargar la vista
            include_once '../view/PermisoRoles/PermisoRoles.php';
        } catch (Exception $e) {
            $this->manejarError("Error al consultar permisos: " . $e->getMessage());
        }
    }


    //   Registrar nuevo rol con permisos

    public function postRegistrar()
    {
        try {
            // Validar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->establecerMensaje('error', 'Método de solicitud no válido');
                $this->redirigir();
            }

            // Validar datos recibidos ANTES de sanitizar
            if (!$this->validarDatosRegistro()) {
                $erroresTexto = implode('<br>', $this->errores);
                $this->establecerMensaje('error', $erroresTexto);
                $this->redirigir();
            }


            $nombreRol = $this->sanitizarTexto($_POST['nombre_rol']);
            $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : array();

            // Validar permisos
            if (!$this->validarPermisos($permisos)) {
                $this->establecerMensaje('error', 'Los permisos seleccionados no son válidos');
                $this->redirigir();
            }

            // Verificar si el rol ya existe
            if ($this->rolExiste($nombreRol)) {
                $this->establecerMensaje('error', 'El rol "' . htmlspecialchars($nombreRol) . '" ya existe en el sistema');
                $this->redirigir();
            }

            // Insertar el nuevo rol
            $idRol = $this->insertarRol($nombreRol);

            if (!$idRol || !is_numeric($idRol)) {
                throw new Exception('No se pudo crear el rol');
            }

            // Insertar los permisos asociados
            if (!empty($permisos)) {
                $permisosInsertados = $this->insertarPermisos($idRol, $permisos);
                if (!$permisosInsertados) {
                    // Rollback: eliminar el rol creado
                    $this->eliminarRol($idRol);
                    throw new Exception('Error al asignar permisos al rol');
                }
            }

            $this->establecerMensaje('success', 'Rol "' . htmlspecialchars($nombreRol) . '" registrado exitosamente con ' . count($permisos) . ' permisos');
            $this->redirigir();
        } catch (Exception $e) {
            $this->manejarError("Error al registrar rol: " . $e->getMessage());
        }
    }


    // Actualizar permisos de un rol existente

    public function putActualizar()
    {
        try {
            // Validar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->establecerMensaje('error', 'Método de solicitud no válido');
                $this->redirigir();
            }

            // Validar datos requeridos
            if (!isset($_POST['id_rol']) || !isset($_POST['permisos'])) {
                $this->establecerMensaje('error', 'Datos incompletos para actualizar permisos');
                $this->redirigir();
            }

            $idRol = $this->validarIdNumerico($_POST['id_rol'], 'ID de rol');
            if (!$idRol) {
                $this->establecerMensaje('error', 'ID de rol no válido');
                $this->redirigir();
            }

            // Verificar que el rol existe
            if (!$this->rolExistePorId($idRol)) {
                $this->establecerMensaje('error', 'El rol especificado no existe');
                $this->redirigir();
            }

            $permisos = is_array($_POST['permisos']) ? $_POST['permisos'] : array();

            // Validar permisos
            if (!empty($permisos) && !$this->validarPermisos($permisos)) {
                $this->establecerMensaje('error', 'Los permisos seleccionados no son válidos');
                $this->redirigir();
            }

            // Eliminar permisos anteriores
            $this->eliminarPermisosRol($idRol);

            // Insertar nuevos permisos
            if (!empty($permisos)) {
                $this->insertarPermisos($idRol, $permisos);
            }

            $this->establecerMensaje('success', 'Permisos actualizados correctamente (' . count($permisos) . ' permisos asignados)');
            $this->redirigir();
        } catch (Exception $e) {
            $this->manejarError("Error al actualizar permisos: " . $e->getMessage());
        }
    }


    //   Eliminar un rol y sus permisos

    public function deleteEliminar()
    {
        try {
            // Validar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->establecerMensaje('error', 'Método de solicitud no válido');
                $this->redirigir();
            }

            if (!isset($_POST['id_rol'])) {
                $this->establecerMensaje('error', 'ID de rol no especificado');
                $this->redirigir();
            }

            $idRol = $this->validarIdNumerico($_POST['id_rol'], 'ID de rol');
            if (!$idRol) {
                $this->establecerMensaje('error', 'ID de rol no válido');
                $this->redirigir();
            }

            // Verificar que el rol existe
            if (!$this->rolExistePorId($idRol)) {
                $this->establecerMensaje('error', 'El rol especificado no existe');
                $this->redirigir();
            }

            // Verificar que el rol no esté asignado a usuarios
            if ($this->rolTieneUsuarios($idRol)) {
                $this->establecerMensaje('error', 'No se puede eliminar el rol porque tiene usuarios asignados. Primero reasigne los usuarios a otro rol.');
                $this->redirigir();
            }

            // Obtener nombre del rol antes de eliminar
            $nombreRol = $this->obtenerNombreRol($idRol);

            // Eliminar permisos del rol
            $this->eliminarPermisosRol($idRol);

            // Eliminar el rol
            $this->eliminarRol($idRol);

            $this->establecerMensaje('success', 'Rol "' . htmlspecialchars($nombreRol) . '" eliminado correctamente');
            $this->redirigir();
        } catch (Exception $e) {
            $this->manejarError("Error al eliminar rol: " . $e->getMessage());
        }
    }

    //   Obtener permisos de un rol específico (AJAX)

    public function getPermisosPorRol()
    {
        try {
            header('Content-Type: application/json');

            if (!isset($_GET['id_rol'])) {
                echo json_encode(array('error' => 'ID de rol no especificado'));
                exit;
            }

            $idRol = $this->validarIdNumerico($_GET['id_rol'], 'ID de rol');
            if (!$idRol) {
                echo json_encode(array('error' => 'ID de rol no válido'));
                exit;
            }

            // Verificar que el rol existe
            if (!$this->rolExistePorId($idRol)) {
                echo json_encode(array('error' => 'El rol especificado no existe'));
                exit;
            }

            $sql = "SELECT 
                        p.id_permiso,
                        p.id_modulo,
                        p.id_accion,
                        m.nombre_modulo,
                        a.nombre_accion
                    FROM permisos p
                    JOIN modulo m ON p.id_modulo = m.id_modulo
                    JOIN acciones a ON p.id_accion = a.id_accion
                    WHERE p.id_rol = $idRol
                    ORDER BY m.nombre_modulo, a.nombre_accion";

            $permisos = $this->model->select($sql);

            // Convertir a array
            $resultado = array();
            if ($permisos && pg_num_rows($permisos) > 0) {
                while ($permiso = pg_fetch_assoc($permisos)) {
                    $resultado[] = $permiso;
                }
            }

            echo json_encode(array('success' => true, 'permisos' => $resultado));
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => $e->getMessage()));
            exit;
        }
    }




    // Validar datos de registro de rol

    private function validarDatosRegistro()
    {
        $this->errores = array();

        // Validar nombre del rol
        if (!isset($_POST['nombre_rol'])) {
            $this->errores[] = 'El nombre del rol es requerido';
            return false;
        }

        $nombreRol = trim($_POST['nombre_rol']);

        if (empty($nombreRol)) {
            $this->errores[] = 'El nombre del rol no puede estar vacío';
        }

        if (strlen($nombreRol) < 3) {
            $this->errores[] = 'El nombre del rol debe tener al menos 3 caracteres';
        }

        if (strlen($nombreRol) > 50) {
            $this->errores[] = 'El nombre del rol no puede exceder 50 caracteres';
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombreRol)) {
            $this->errores[] = 'El nombre del rol solo puede contener letras y espacios';
        }

        return empty($this->errores);
    }


    // Validar ID numérico

    private function validarIdNumerico($valor, $nombreCampo = 'ID')
    {
        if (!isset($valor) || $valor === '') {
            $this->errores[] = $nombreCampo . ' no puede estar vacío';
            return false;
        }

        if (!is_numeric($valor)) {
            $this->errores[] = $nombreCampo . ' debe ser un número';
            return false;
        }

        $id = intval($valor);

        if ($id <= 0) {
            $this->errores[] = $nombreCampo . ' debe ser un número positivo';
            return false;
        }

        return $id;
    }


    // Validar array de permisos

    private function validarPermisos($permisos)
    {
        if (!is_array($permisos)) {
            $this->errores[] = 'Los permisos deben ser un array';
            return false;
        }

        if (count($permisos) > 100) {
            $this->errores[] = 'Demasiados permisos seleccionados';
            return false;
        }

        foreach ($permisos as $permiso) {
            if (!is_string($permiso)) {
                $this->errores[] = 'Formato de permiso inválido';
                return false;
            }

            // Validar formato: debe ser "texto-texto"
            if (!preg_match('/^[a-zA-Z0-9]+\-[a-zA-Z0-9]+$/', $permiso)) {
                $this->errores[] = 'Formato de permiso inválido: ' . htmlspecialchars($permiso);
                return false;
            }
        }

        return true;
    }


    // Sanitizar texto

    private function sanitizarTexto($texto)
    {
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
        return $texto;
    }

    // MÉTODOS DE CONSULTA 


    // Obtener todos los roles

    private function obtenerRoles()
    {
        $sql = "SELECT id_rol, nombre_rol 
                FROM rol 
                ORDER BY nombre_rol";
        return $this->model->select($sql);
    }


    // Obtener todos los módulos

    private function obtenerModulos()
    {
        $sql = "SELECT id_modulo, nombre_modulo 
                FROM modulo 
                ORDER BY nombre_modulo";
        return $this->model->select($sql);
    }


    // Obtener todas las acciones

    private function obtenerAcciones()
    {
        $sql = "SELECT id_accion, nombre_accion 
                FROM acciones 
                ORDER BY 
                    CASE nombre_accion
                        WHEN 'Registrar' THEN 1
                        WHEN 'Consultar' THEN 2
                        WHEN 'Editar' THEN 3
                        WHEN 'Eliminar' THEN 4
                        ELSE 5
                    END";
        return $this->model->select($sql);
    }


    // Obtener todos los permisos con información completa

    private function obtenerPermisos()
    {
        $sql = "SELECT 
                    p.id_permiso,
                    p.id_rol,
                    p.id_modulo,
                    p.id_accion,
                    r.nombre_rol,
                    m.nombre_modulo,
                    a.nombre_accion
                FROM permisos p
                JOIN rol r ON p.id_rol = r.id_rol
                JOIN modulo m ON p.id_modulo = m.id_modulo
                JOIN acciones a ON p.id_accion = a.id_accion
                ORDER BY r.nombre_rol, m.nombre_modulo, 
                    CASE a.nombre_accion
                        WHEN 'Registrar' THEN 1
                        WHEN 'Consultar' THEN 2
                        WHEN 'Editar' THEN 3
                        WHEN 'Eliminar' THEN 4
                        ELSE 5
                    END";
        return $this->model->select($sql);
    }


    // Obtener nombre de un rol por ID

    private function obtenerNombreRol($idRol)
    {
        $idRol = intval($idRol);
        $sql = "SELECT nombre_rol FROM rol WHERE id_rol = $idRol";
        $resultado = $this->model->select($sql);

        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            return $row['nombre_rol'];
        }

        return 'Desconocido';
    }


    // Verificar si un rol ya existe por nombre

    private function rolExiste($nombreRol)
    {
        $nombreRol = pg_escape_string($nombreRol);
        $sql = "SELECT id_rol FROM rol WHERE LOWER(nombre_rol) = LOWER('$nombreRol')";
        $resultado = $this->model->select($sql);
        return $resultado && pg_num_rows($resultado) > 0;
    }

    // Verificar si un rol existe por ID

    private function rolExistePorId($idRol)
    {
        $idRol = intval($idRol);
        $sql = "SELECT id_rol FROM rol WHERE id_rol = $idRol";
        $resultado = $this->model->select($sql);
        return $resultado && pg_num_rows($resultado) > 0;
    }



    // Verificar si un rol tiene usuarios asignados

    private function rolTieneUsuarios($idRol)
    {
        $idRol = intval($idRol);
        $sql = "SELECT id_usuario FROM usuario WHERE id_rol = $idRol LIMIT 1";
        $resultado = $this->model->select($sql);
        return $resultado && pg_num_rows($resultado) > 0;
    }


    // Obtener ID de módulo por nombre

    private function obtenerIdModuloPorNombre($nombre)
    {
        $nombre = pg_escape_string($nombre);
        $sql = "SELECT id_modulo FROM modulo WHERE LOWER(nombre_modulo) = LOWER('$nombre')";
        $resultado = $this->model->select($sql);

        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            return intval($row['id_modulo']);
        }

        return false;
    }


    // Obtener ID de acción por nombre

    private function obtenerIdAccionPorNombre($nombre)
    {
        $nombre = pg_escape_string($nombre);
        $sql = "SELECT id_accion FROM acciones WHERE LOWER(nombre_accion) = LOWER('$nombre')";
        $resultado = $this->model->select($sql);

        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            return intval($row['id_accion']);
        }

        return false;
    }




    //Insertar un nuevo rol

    private function insertarRol($nombreRol)
    {
        $nombreRol = pg_escape_string($nombreRol);

        $sql = "INSERT INTO rol (nombre_rol) 
                VALUES ('$nombreRol') 
                RETURNING id_rol";

        $resultado = $this->model->insert($sql);

        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            return intval($row['id_rol']);
        }

        return false;
    }


    // Insertar permisos para un rol

    private function insertarPermisos($idRol, $permisos)
    {
        $idRol = intval($idRol);
        $permisosInsertados = 0;

        foreach ($permisos as $permiso) {
            // El formato esperado es: "modulo-accion"
            $partes = explode('-', $permiso);

            if (count($partes) !== 2) {
                continue;
            }

            $moduloNombre = trim($partes[0]);
            $accionNombre = trim($partes[1]);

            // Validar que no estén vacíos
            if (empty($moduloNombre) || empty($accionNombre)) {
                continue;
            }

            // Buscar los IDs
            $idModulo = $this->obtenerIdModuloPorNombre($moduloNombre);
            $idAccion = $this->obtenerIdAccionPorNombre($accionNombre);

            if ($idModulo && $idAccion) {
                // Verificar que el permiso no exista ya
                $sqlCheck = "SELECT id_permiso FROM permisos 
                            WHERE id_rol = $idRol 
                            AND id_modulo = $idModulo 
                            AND id_accion = $idAccion";
                $existe = $this->model->select($sqlCheck);

                if (!$existe || pg_num_rows($existe) == 0) {
                    $sql = "INSERT INTO permisos (id_rol, id_modulo, id_accion) 
                            VALUES ($idRol, $idModulo, $idAccion)";
                    $resultado = $this->model->insert($sql);

                    if ($resultado) {
                        $permisosInsertados++;
                    }
                }
            }
        }

        return $permisosInsertados > 0;
    }


    // Eliminar todos los permisos de un rol

    private function eliminarPermisosRol($idRol)
    {
        $idRol = intval($idRol);
        $sql = "DELETE FROM permisos WHERE id_rol = $idRol";
        return $this->model->delete($sql);
    }


    // Eliminar un rol

    private function eliminarRol($idRol)
    {
        $idRol = intval($idRol);
        $sql = "DELETE FROM rol WHERE id_rol = $idRol";
        return $this->model->delete($sql);
    }

    //  MÉTODOS DE SESIÓN 


    // Establecer mensaje en sesión

    private function establecerMensaje($tipo, $mensaje)
    {
        @session_start();
        $_SESSION['tipo_mensaje'] = $tipo;
        $_SESSION['mensaje'] = $mensaje;
    }


    // Obtener mensaje de sesión

    private function obtenerMensajeSesion()
    {
        @session_start();
        return isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
    }


    //Obtener tipo de mensaje de sesión

    private function obtenerTipoMensajeSesion()
    {
        @session_start();
        return isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : null;
    }

    // Limpiar mensajes de sesión

    private function limpiarMensajesSesion()
    {
        @session_start();
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
    }

    //  MÉTODOS DE UTILIDAD 


    // Manejar errores

    private function manejarError($mensaje)
    {
        error_log($mensaje);
        $this->establecerMensaje('error', 'Ha ocurrido un error. Por favor, intente nuevamente.');
        $this->redirigir();
    }


    // Redirigir a la vista principal

    private function redirigir()
    {
        header('Location: ../view/PermisoRoles/PermisoRoles.php');
        exit;
    }
}
