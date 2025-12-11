<?php

    include_once '../model/Acceso/AccesoModel.php';

    class AccesoController{

        public function login(){
            $obj = new AccesoModel();

            $documento = $_POST['documento'];
            $contraseña = $_POST['contraseña'];

            $sql = "SELECT * FROM usuarios WHERE documento = $documento AND contraseña = '$contraseña'";
            $usuario = $obj->select($sql);

            if(pg_num_rows($usuario)>0){
                $rol;
                while($usu=pg_ftch_assoc($usuario)){
                    $_SESSION['documento'] = $usuario['documento'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['rol'] = $usuario['id_rol'];
                    $_SESSION['auth'] = "ok";
                    $rol = $usuario['id_rol'];
                }
                cargarPermisos($rol);
                redirect("index.php");
            }else{
                $_SESSION['Error'] = "Credenciales invalidas";
                redirect("login.php");
            }
        }

        public function logout(){
            session_destroy();
            redirect("login.php");
        }

        public function cargarPermisos($idRol) {
            $obj = new AccesoModel();

            $sql = "
                SELECT m.nombre_modulo, a.nombre_accion FROM permisos p
                INNER JOIN modulo m ON p.id_modulo = m.id_modulo
                INNER JOIN acciones a ON p.id_accion = a.id_accion
                WHERE p.id_rol = $idRol
            ";

            $result = $obj->select($sql);

            $permisos = [];

            while ($row = pg_fetch_assoc($result)) {
                $modulo = $row['nombre_modulo'];
                $accion = $row['nombre_accion'];

                // si el modulo no está creado en el array, se crea
                if (!isset($permisos[$modulo])) {
                    $permisos[$modulo] = [];
                }

                // sgregar accion sin duplicados
                if (!in_array($accion, $permisos[$modulo])) {
                    $permisos[$modulo][] = $accion;
                }
            }

            // guardar en sesion la matriz[modulo], [acciones]
            $_SESSION['permisos'] = $permisos;
        }


    }

?>