<?php
require_once './models/partida.php';
require_once './accesoBBDD/conexion.php';
require_once 'controladorUsuario.php';
require_once './models/usuario.php';

class ControladorPartida{
   
  
    

    public static function jugadaMaquina($body){
       
        $usuario=ControladorUsuario::buscarUsuario($body->correo,$body->pass);
     
            if($usuario instanceof Usuario){
        $partidasUser=Conexion::buscarPartidaUser($usuario->id_usuario);
        
        $partida=self::seleccionarPartida($body->idPartida,$partidasUser);
        $posIncrementadas=$partida->reclutamiento();
        self::repartir($posIncrementadas["tropasM"],$partida);
        self::repartir($posIncrementadas["tropasJ"],$partida);
    
        $respuesta=$partida->turnoMaquina();
        $partidasUser=Conexion::buscarPartidaUser($usuario->id_usuario);
        $partida=self::seleccionarPartida($body->idPartida,$partidasUser);
            if($respuesta==1){
                echo json_encode([
                            "partida"=>$partida->idPartida,
                            "movimiento"=>"atacar",
                             "tablero"=>$partida->vector]);
            }else if($respuesta==2){
                echo json_encode([
                            "partida"=>$partida->idPartida,
                            "movimiento"=>"mover tropas",
                             "tablero"=>$partida->vector]);
            }else{
                echo json_encode([
                            "partida"=>$partida->idPartida,
                            "movimiento"=>"pasar el turno",
                             "tablero"=>$partida->vector]);
            }
        }else{
            self::mostrarError();
        }

        $posIncrementadas=$partida->reclutamiento();
        self::repartir($posIncrementadas["tropasM"],$partida);
        self::repartir($posIncrementadas["tropasJ"],$partida);
    }

   public static function repartir($posiciones, $partida){
    foreach ($posiciones as $value) {
        Conexion::guardarMovimiento($partida->vector[$value->posicion],$partida->idPartida);
    }
} 
    public static function atacarJugador($body){
        $usuario=ControladorUsuario::buscarUsuario($body->correo, $body->pass);
        if($usuario instanceof Usuario){
            $partidasUser=Conexion::buscarPartidaUser($usuario->id_usuario);
            $partida=self::seleccionarPartida($body->idPartida,$partidasUser);
            if($partida && ($body->atacante==$body->defensor+1 || $body->atacante==$body->defensor-1)
                && $partida->vector[$body->atacante]->tropa=='J' && $partida->vector[$body->defensor]->tropa == 'M'
                && $partida->vector[$body->atacante]->cantidad>1 && $body->dados<=3 
                && $body->dados<=$partida->vector[$body->atacante]->cantidad-1){
                    $dados=$partida->ataqueJugador($partida->vector[$body->atacante],$partida->vector[$body->defensor],$body->dados);
                    Conexion::guardarMovimiento($partida->vector[$body->atacante],$body->idPartida);
                    Conexion::guardarMovimiento($partida->vector[$body->defensor],$body->idPartida);
                    
                    
                    echo json_encode(["partida"=>$partida->idPartida,
                                    "dados"=>$dados,
                                    "atacante"=>$partida->vector[$body->atacante],
                                    "defensor"=>$partida->vector[$body->defensor]]);
                }else{
                    self::mostrarError();
                }
        }else{
            echo json_encode(["mensaje"=>"usuario incorrecto"]);
        }
    }
    public static function moverTropasJugador($body){
        $usuario=ControladorUsuario::buscarUsuario($body->correo, $body->pass);
        if($usuario instanceof Usuario){
            $partidasUser=Conexion::buscarPartidaUser($usuario->id_usuario);
            $partida=self::seleccionarPartida($body->idPartida,$partidasUser);

            if($partida && ($body->destino==$body->origen+1 || $body->destino==$body->origen-1) 
            && $partida->vector[$body->origen]->tropa=='J' && $partida->vector[$body->destino]->tropa == 'J'
            && $body->canTropas < $partida->vector[$body->origen]->cantidad){
              
                $partida->movimientoJugador($body);  
                Conexion::guardarMovimiento($partida->vector[$body->origen],$partida->idPartida);
                Conexion::guardarMovimiento($partida->vector[$body->destino],$partida->idPartida);
                echo json_encode($partida->vector[$body->origen]);
                echo json_encode($partida->vector[$body->destino]);
            }else{
                self::mostrarError();
            }
        }else{
            echo json_encode(["mensaje"=>"usuario incorrecto"]);
        }
        

    }

    public static function seleccionarPartida($id, $partidasUser){
        $partida=0;
        foreach ($partidasUser as $key => $value) {
           
            if($value->idPartida==$id) {
                $partida=$value;
            }
        }
     
        $partida->vector=Conexion::buscarTablero($partida->idPartida);
        return $partida;
    }

  
    

    public static function iniciarJuegoEstandar($body){
        $usuario=ControladorUsuario::buscarUsuario($body->correo, $body->pass);
        echo json_encode($usuario);
        if(count(Conexion::buscarPartidaUser($usuario->id_usuario))<2 && $usuario instanceof Usuario){
            $partida=new Partida($usuario->id_usuario);
            $partida->distribuirTropas();
            $partida->aniadirSobrantes('M',5);
            $partida->aniadirSobrantes('J',5);
            $partidaGuardada=self::guardarPartidaBBDD($partida);
            echo json_encode($partidaGuardada);
        }else{
            self::mostrarError();
        }
       
    }
    public static function iniciarJuegoPersonalizado($body){
        
        $usuario=ControladorUsuario::buscarUsuario($body->correo, $body->pass);
       if(count(Conexion::buscarPartidaUser($usuario->id_usuario))<2 && $usuario instanceof Usuario 
            && $body->longitud%2==0 && $body->canTropas>$body->longitud 
            && $body->canTropas%2==0 && count($body->tropaSituadas)>=$body->longitud/2){
                
                $partida=new Partida($usuario->id_usuario,0 ,0 ,$body->longitud);
                $partida->distribuirTropasCustom($body->tropaSituadas);
                $partida->aniadirSobrantes('M',($body->canTropas-$body->longitud)/2);
                echo json_encode($partida);
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