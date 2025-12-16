<?php

include_once '../model/PermisoRoles/PermisoRolesModel.php';

class PermisoRolesController {
    
    // Consultar permisos de roles
    public function getConsultar() {
        $obj = new PermisoRolesModel();
        
        // Obtener todos los roles
        $sqlRoles = "SELECT id_rol, nombre_rol FROM rol ORDER BY id_rol";
        $roles = $obj->select($sqlRoles);
        
        // Obtener todos los módulos
        $sqlModulos = "SELECT id_modulo, nombre_modulo FROM modulo ORDER BY nombre_modulo";
        $modulos = $obj->select($sqlModulos);
        
        // Obtener todas las acciones
        $sqlAcciones = "SELECT id_accion, nombre_accion FROM acciones ORDER BY nombre_accion";
        $acciones = $obj->select($sqlAcciones);
        
        // Obtener todos los permisos
        $sqlPermisos = "SELECT 
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
                        ORDER BY r.id_rol, m.nombre_modulo, a.nombre_accion";
        
        $permisos = $obj->select($sqlPermisos);
        
        @session_start();
        
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
        $tipo_mensaje = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : null;
        
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        
        include_once '../view/PermisoRoles/PermisoRoles.php';
    }
}

?>