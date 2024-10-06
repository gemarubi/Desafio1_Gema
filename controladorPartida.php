<?php
require_once 'partida.php';
require_once 'conexion.php';


class ControladorPartida{
    public static function buscarUsuario($correo){
        return Conexion::buscarUsuario($correo);
    }

    public static function iniciarJuegoEstandar($body){
        $usuario=self::buscarUsuario($body->correo);
        if(count(Conexion::buscarPartidaUser($usuario->id_usuario))<2){
            $partida=new Partida($usuario->id_usuario);
            $partida->distribuirTropas();
            Conexion::guardarPartida($partida);
            $idPartida=Conexion::buscarPartida();
          
            foreach ($partida->vector as  $value) {
              
               $resultado= Conexion::guardarTablero($value,$idPartida);
            }
           
            echo $resultado ;
            
        }
       
    }

   
}