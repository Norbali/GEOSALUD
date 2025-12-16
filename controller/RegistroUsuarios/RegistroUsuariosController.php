<?php
    include_once '../model/RegistroUsuarios/registroUsuariosModel.php';

    class RegistroUsuariosController{
        
       public function getCreate(){
            $obj = new RegistroUsuario();
            $sql= "SELECT * FROM rol";
            $roles = $obj->select($sql);
            include_once '../view/registroUsuarios/registrarUsuario.php';
        } 

        public function postCreate(){

            $obj = new RegistroUsuario();

            $documento  = $_POST['documento'];
            $nombre     = $_POST['nombre'];
            $apellido   = $_POST['apellido'];
            $telefono   = $_POST['telefono'];
            $correo     = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $id_rol     = $_POST['id_rol'];

            $errores = array();

            //validacion doc
            if ($this->validarDocumento($documento)) {
                $errores['documento'] = 'El documento ya est&aacute; registrado.';
            }
            //validacion correo
            if ($this->validarCorreo($correo)) {
                $errores['correo'] = 'El correo ya est&aacute; registrado.';
            }

            if (count($errores) > 0) {
                $_SESSION['errors'] = $errores;
                redirect(getUrl("RegistroUsuarios","RegistroUsuarios","getCreate"));
                return;
            }

            //hash contraseña
            $hash = sha1($contrasena);
           
            $sql = "
                INSERT INTO usuarios
                (documento, nombre, apellido, telefono, correo, contrasena, id_rol)
                VALUES
                ('$documento', '$nombre', '$apellido', '$telefono', '$correo', '$hash', '$id_rol')
            ";

            if ($obj->insert($sql)) {
                $_SESSION['success'] = 'Usuario registrado exitosamente.';
            } else {
                $_SESSION['errors'] = array(
                    'general' => 'Error al registrar el usuario.'
                );
            }

            redirect(getUrl("RegistroUsuarios","RegistroUsuarios","getCreate"));
        }

        private function validarDocumento($documento){
            $obj = new RegistroUsuario();
            $sql = "SELECT * FROM usuarios WHERE documento = '$documento' LIMIT 1";
            $res = $obj->select($sql);
            return (pg_num_rows($res) > 0);
        }

        private function validarCorreo($correo){
            $obj = new RegistroUsuario();
            $sql = "SELECT * FROM usuarios WHERE correo = '$correo' LIMIT 1";
            $res = $obj->select($sql);
            return (pg_num_rows($res) > 0);
        }

    }
?>