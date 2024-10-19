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

    //preguntar si dejarlo aqui o ponerlo en el controladorPartida
    public function movimientoJugador($body){
      
            $this->vector[$body->origen]->cantidad-=$body->canTropas;
            $this->vector[$body->destino]->cantidad+=$body->canTropas;
      
    }

    public function turnoMaquina(){

                $tJugador=array_filter($this->vector,function($territorio){
                    return $territorio->tropa=='J';
                });
                $tMaquina=array_filter($this->vector,function($territorio){
                return $territorio->tropa=='M';
                });
                $tropTotales;
                foreach ($tMaquina as $value) {
                    $tropTotales+=$value->cantidad;
                }
                $tFuertes= array_filter($tMaquina, function($territorio){
                    return $territorio->cantidad>2;
                });
        
            $ataques=0;
            if(!empty($tFuertes)){
                foreach ($tFuertes as $value) {
                    if($this->vector[$value->posicion-1]->tropa=='J' && $this->vector[$value->posicion-1]->cantidad==1 && count(tMaquina)>=count(tJugador)){
                        $this->atacarMaquina($value, $this->vector[$value->posicion-1]);
                        $ataques++;
                    }else if($this->vector[$value->posicion+1]->tropa=='J' && $this->vector[$value->posicion+1]->cantidad==1 && count(tMaquina)>=count(tJugador)){
                        $this->atacarMaquina($value, $this->vector[$value->posicion+1]);
                         $ataques++;
                    }
                }
            }
                
            if($ataques==0){
                //moverMaquina()
            }
        
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