<?php

class ControladorAdmin{
    static public function getAllUsers(){
        $users=Conexion::buscarTodosUsuarios();
        echo json_encode($users);
    }

    static public function insertUser($body){
        $vectorU=[];
        foreach ($body as $value) {
           
            $user=new Usuario($value->email,md5($value->pass));
            echo $user->getPass();
            $vectorU[]=$user;
        }
      
        Conexion::registrarUsuarios($vectorU);
          //Preguntar xq  no me funciona con $this
        self::asignarRolUser($vectorU);
        
    }

    public  static function asignarRolUser($vectorU){
        $id_user;
        foreach ($vectorU as $value) {
            $user=Conexion::buscarUsuario($value->email);
            
            Conexion::asignarRol($user->id_usuario,$user->rol);
        }
       
    }
    public static function asignarRol($body){
        $user=Conexion::buscarUsuario($body->email);
        
        Conexion::updateRol($user->id_usuario,$body->rol);
    }

    public static function deleteUser($body){
        $user=Conexion::buscarUsuario($body->email);
        Conexion::borrarRol($user->id_usuario);
        Conexion::borrar($user->id_usuario);
    }
}