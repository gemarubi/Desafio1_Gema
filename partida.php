<?php
require_once 'territorio.php';
class Partida{
    public $idPartida;
    public $vector;
    public $resultado;
    public $id_usuario;

    public function __construct(  $id_usuario, $resultado=0 ,$idPartida=0, $longitud=20){
        $this->idPartida = $idPartida;
        $this->vector = array_fill(0,$longitud,0);
        $this->resultado = $resultado;
        $this->id_usuario = $id_usuario;
    }

    public function movimientoJugador($body){
      
            $this->vector[$body->origen]->cantidad-=$body->canTropas;
            $this->vector[$body->destino]->cantidad+=$body->canTropas;
      
    }

    public function ataqueJugador($atacante, $defensor,$dadosJugador){
        $dadosMaquina=1;
        if($defensor->cantidad>1){
            $dadosMaquina=2;
        }

        $atacante->ataca($defensor,$dadosJugador,$dadosmaquina);

        Conexion::guardarMovimiento($this->vector[$atacante->posicion],$this->idPartida);
        Conexion::guardarMovimiento($this->vector[$defensor->posicion],$this->idPartida);
    }
    public function movimientoMaquina($origen, $destino, $cantidad){
        $this->vector[$origen->posicion]->cantidad-=$cantidad;
        $this->vector[$destino->posicion]->cantidad+=$cantidad;
        Conexion::guardarMovimiento($this->vector[$origen->posicion],$this->idPartida);
        Conexion::guardarMovimiento( $this->vector[$destino->posicion],$this->idPartida);
         
    }

    public function turnoMaquina(){

        $respuesta;
        //1 ataque
        //2 movimiento
        //3 pasar turno
        
                $tJugador=array_filter($this->vector,function($territorio){
                    return $territorio->tropa=='J';
                });
                $tropTotalesJu=0;
                foreach ($tJugador as $value) {
                    $tropTotalesJu+=$value->cantidad;
                }
                $tMaquina=array_filter($this->vector,function($territorio){
                return $territorio->tropa=='M';
                });
                $tropTotalesMa=0;
                foreach ($tMaquina as $value) {
                    $tropTotalesMa+=$value->cantidad;
                }
                $tFuertes= array_filter($tMaquina, function($territorio){
                    return $territorio->cantidad>2;
                });
            
            
            if(!empty($tFuertes) && $tropTotalesMa>=$tropTotalesJu){
                foreach ($tFuertes as $value) {
                    if($value->posicion>0 && $this->vector[$value->posicion-1]->tropa=='J' 
                    && $this->vector[$value->posicion-1]->cantidad<$value->cantidad 
                    && $value->cantidad>0 &&  $this->vector[$value->posicion-1]->cantidad>0){
                       
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion-1]);
                       
                        $value->atacar($this->vector[$value->posicion-1],$dados['atacante'],$dados['defensor']);
                        Conexion::guardarMovimiento($this->vector[$value->posicion-1],$this->idPartida);
                        Conexion::guardarMovimiento($value,$this->idPartida);
                    }else if($value->posicion<count($this->vector) && $this->vector[$value->posicion+1]->tropa=='J' 
                    && $this->vector[$value->posicion+1]->cantidad<$value->cantidad 
                    && $value->cantidad>0 &&  $this->vector[$value->posicion-1]->cantidad>0){
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion+1]);
                        
                        $value->atacar($this->vector[$value->posicion+1],$dados['atacante'],$dados['defensor']);
                        Conexion::guardarMovimiento($this->vector[$value->posicion+1],$this->idPartida);
                        Conexion::guardarMovimiento($value,$this->idPartida);
                         
                    }
                }
                $respuesta=1;

            }else{
                
                $queHacer=$this->deboMover($tFuertes);
                if(count($queHacer)>1){
                    $respuesta=2;
                    $this->movimientoMaquina($queHacer['origen'],$queHacer['destino'],$queHacer['canTropas']);
                }else{
                    $respuesta=3;
                }
            }
        return $respuesta;
        
    }

    function deboMover($tFuertes){
        $i=0;
        $movimientoHecho=false;
        $respuesta=['movimiento'=>0];
        //1-> si mueve
        //2-> no mueve
       
        foreach ($tFuertes as $value) {
            if($this->vector[$value->posicion-1]->tropa=='M' && $this->vector[$value->posicion-2]->tropa=='J'){
            
                $respuesta=['movimiento'=>1,
                            'origen'=>$this->vector[$value->posicion],
                            'destino'=>$this->vector[$value->posicion-1],
                            'canTropas'=>$this->vector[$value->posicion]->cantidad-1];
            }
        }
        return $respuesta;
    }

    public function cuantosDados($atacante, $defensor){
        $dadosAtacante;
        $dadosDefensor;
       
        if($atacante->cantidad>3){
            
            $dadosAtacante=$this->generarDados(3);
          
        }else{
            $dadosAtacante=$this->generarDados(2);
            
        }

        if($defensor->cantidad>1){
            $dadosDefensor=$this->generarDados(2);
        }else{
            $dadosDefensor=$this->generarDados(1);
        }
        $dados=['atacante'=>$dadosAtacante,
                'defensor'=>$dadosDefensor];
        return $dados;

    }
    public function generarDados($cantidadDados){
        $dados=[];
        for ($i=0; $i < $cantidadDados; $i++) { 
            $dados[]=rand(1,6);
        }
        
        sort($dados);
        
        return $dados;

    }
   

        

    public function distribuirTropas(){
        $aleatorio=array_rand($this->vector,count($this->vector)/2); 
        foreach ($aleatorio as  $value) {
            $this->vector[$value]=new Territorio($value,'J',1);
        }

        for ($i=0; $i < count($this->vector); $i++) { 
            if($this->vector[$i] instanceof Territorio){
                $this->vector[$i]->cantidad=1;
            }else{
                $this->vector[$i]= new Territorio($i,'M',1);
            }

        }
        
            
    }

    function aniadirSobrantes($trop,$canTropas){
        
        $tropasJ=0;
        $cuantas=0;
      
        while($tropasJ<$canTropas){
            
            $aleatorio=array_rand($this->vector,1);
            if($this->vector[$aleatorio]->tropa===$trop){
               
                $cuantas=rand(1,$canTropas-$tropasJ);
                $this->vector[$aleatorio]->cantidad+=$cuantas;
                $tropasJ+=$cuantas;
                
            }
             
        }
    }
    function distribuirTropasCustom($tropaSituadas){
        foreach ($tropaSituadas as $key => $value) {
            $this->vector[$value->pos]=new Territorio($value->pos, 'J',$value->cantidad);
        }
        
        foreach($this->vector as $key => $value){
            if(!$value instanceof Territorio){
                $this->vector[$key]=new Territorio($key,'M',1);
                //echo json_encode($value);
            }
        }
 
    }
}