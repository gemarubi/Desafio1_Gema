<?php
require_once 'controladorUsuario.php';
require_once './models/usuario.php';
require_once 'controladorPartida.php';
class ControladorAdmin{
    static public function getAllUsers($body){
        
        $usuario=ControladorUsuario::buscarUsuario($body->correo,$body->pass);
        if($usuario instanceof Usuario && self::comprobarRol($usuario->id_usuario)){
            $users=Conexion::buscarTodosUsuarios();
            if($users!=0 && $users!=1){
                echo json_encode($users);
            }else{
                ControladorPartida::mostrarError();
            }
        }
       
       
    }

    static public function insertUser($body){
        $vectorU=[];
       
        $usuario=ControladorUsuario::buscarUsuario($body->correo, $body->pass);
        if($usuario instanceof Usuario && self::comprobarRol($usuario->id_usuario)){
            foreach ($body->registro as $value) {
           
                $user=new Usuario($value->email,md5($value->pass));
                $vectorU[]=$user;
            }
            self::asignarRolUser($vectorU);
            $respuesta= Conexion::registrarUsuarios($vectorU);
            if($respuesta==1){
                echo json_encode(["mensaje"=>"Usuario registrado correctamente"]);
            }else{
                echo json_encode([
                    'cod'=> 404,
                    'data' => 'Registro Incorrecto'
                ]);
            }

        }else{
            ControladorPartida::mostrarError();
        }
        
    }
    public static function comprobarRol($id){
        $rol=Conexion::buscarRol($id);
        
        $respuesta=false;
        if($rol[0]=='Administrador'){
            $respuesta=true;
        }
        return $respuesta;
    }

    public  static function asignarRolUser($vectorU){
       
        foreach ($vectorU as $value) {
            $user=Conexion::buscarUsuario($value->email);
           if($user)  Conexion::asignarRol($user->id_usuario,$user->rol);
          
        }
       
    }
    public static function updateRol($body){
        $usuario=ControladorUsuario::buscarUsuario($body->correo,$body->pass);
        
        $user=Conexion::buscarUsuario($body->usUpdated);
        
        if($usuario instanceof Usuario && self::comprobarRol($usuario->id_usuario)){
            $res=Conexion::updateRol($user->id_usuario,$body->rol);
            if($user instanceof Usuario && $res==1){
                $user=Conexion::buscarUsuario($body->usUpdated);
                $user->rol=Conexion::buscarRol($user->id_usuario);
                    echo json_encode(['mensaje'=>'Rol actualizado',
                                       'usuario'=>$user]);
              
                   
            }else{
                    //ControladorPartida::mostrarError();
                    echo 'falla la consulta';
                }
        }else{
           // ControladorPartida::mostrarError();
           echo 'falla el usuario  luka';
        }
        
        
        
        
    }

    public static function deleteUser($body){
        
        $usuario=ControladorUsuario::buscarUsuario($body->correo,$body->pass);
        $user=Conexion::buscarUsuario($body->usBorrado);
        if($usuario instanceof Usuario && self::comprobarRol($usuario->id_usuario)){
           $partidas=Conexion::buscarPartidaUser($user->id_usuario);
           if($partidas){
                foreach ($partidas as  $value) {
                   Conexion::borrarTerritorios($value->idPartida); 
                }
           }
           Conexion::borrarPartidas($user->id_usuario);
            $res1= Conexion::borrarRol($user->id_usuario);
           $res2= Conexion::borrar($user->id_usuario);

           if($res1==1 && $res2==1){
                echo json_encode(['mensaje'=>'Usuario borrado',
                                    'usuario'=>$user]);
           }else{
            
                ControladorPartida::mostrarError();
           }
        }else{
            
            ControladorPartida::mostrarError();
        }
       
       
    }
}