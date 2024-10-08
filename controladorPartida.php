<?php
require_once 'partida.php';
require_once 'conexion.php';


class ControladorPartida{
    //preguntar si dejo esto o es una tonteria
    public static function buscarUsuario($correo){
        return Conexion::buscarUsuario($correo);
    }

    public static function iniciarJuegoEstandar($body){
        $usuario=self::buscarUsuario($body->correo);
        if(count(Conexion::buscarPartidaUser($usuario->id_usuario))<2 && $usuario!=null){
            $partida=new Partida($usuario->id_usuario);
            $partida->distribuirTropas();
            $partida->aniadirSobrantes('M');
            $partida->aniadirSobrantes('J');
            $partidaGuardada=self::guardarPartidaBBDD($partida);
            
       echo json_encode($partidaGuardada);
           //echo $resultado ;
        }else{
            self::mostrarError();
        }
       
    }
    public static function iniciarJuegoPersonalizado($body){
        
        $usuario=self::buscarUsuario($body->correo);
       if(count(Conexion::buscarPartidaUser($usuario->id_usuario))<2 && $usuario!=null 
            && $body->longitud%2==0 && $body->canTropas>$body->longitud 
            && $body->canTropas%2==0 && count($body->tropaSituadas)>=$body->longitud/2){
                
                $partida=new Partida($usuario->id_usuario,0 ,0 ,$body->longitud);
                $partida->distribuirTropasCustom($body->tropaSituadas);
                $partida->aniadirSobrantes('M',($body->canTropas-$body->longitud)/2);
                $partidaGuardada=self::guardarPartidaBBDD($partida);
                
           echo json_encode($partidaGuardada);
        }else{
            self::mostrarError();
        }
    }

    public static function guardarPartidaBBDD($partida){
        Conexion::guardarPartida($partida);
        $partidaGuardada=Conexion::buscarUltimaPartida();
        Conexion::guardarTablero($partida->vector,$partidaGuardada->idPartida);
        $partidaGuardada->vector=Conexion::buscarTablero($partidaGuardada->idPartida);
        return $partidaGuardada;
    }

   public static function mostrarError(){
    echo json_encode([
        'error'=>'Los datos introducidos no son correctos'
    ]);
   }
}