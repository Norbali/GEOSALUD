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

            $documento   = $_POST['documento'];
            $nombre  = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $telefono  = $_POST['telefono'];
            $correo  = $_POST['correo'];
            $contraseña = $_POST['contraseña'];
            $id_rol = $_POST['id_rol'];

            $sql = "INSERT INTO usuarios (documento, nombre, apellido, telefono, correo, contraseña, id_rol)
            VALUES ('$documento', '$nombre', '$apellido', '$telefono', '$correo', '$contraseña', '$id_rol')";

            
            $ejecutar = $obj->insert($sql);
            if ($ejecutar) {
                echo "usuario registrado exitosamente";
                //redirect(getUrl("","",""));

            }else{
                echo "error en la insercion";
            }
        }
    }
?>