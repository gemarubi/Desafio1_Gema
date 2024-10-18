<?php

require_once 'conexion.php';


class ControladorUsuario{

    public static function consultarDatosUser($correo){
      
        $usuario= Conexion::buscarUsuario($correo);
        echo json_encode($usuario);
    }

    public static function verEstadisticas($correo){
        $user=Conexion::buscarUsuario($correo);
        $partidas=Conexion::buscarPartidaUser($user->id_usuario);
        $respuesta=[];
        foreach ($partidas as $value) {
            
            $respuesta[]= 'idPartida: '.$value->idPartida." Usuario: ".$value->id_usuario." Resultado: ".$value->resultado;
        }
       
        echo json_encode($respuesta);
    }

   
}