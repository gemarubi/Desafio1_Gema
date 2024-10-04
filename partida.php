<?php
require_once 'territorio.php';
class Partida{
    public $idPartida;
    public $vector;
    public $resultado;
    public $id_usuario;

    public function __construct( $resultado, $id_usuario,$idPartida=0, $longitud=20){
        $this->idPartida = $idPartida;
        $this->vector = array_fill(0,$longitud,0);
        $this->resultado = $resultado;
        $this->id_usuario = $id_usuario;
    }

    public function distribuirTropas($canTropas=30){
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
        
        $this->aniadirSobrantes($canTropas);
            
    }

    function aniadirSobrantes($canTropas){
        $troJ=($canTropas-count($this->vector))/2;
        $troM=($canTropas-count($this->vector))/2;
        $tropasJ=0;
        //echo 'J INicio '.$tropasJ.'<br>';
        $tropasM=0;
        //echo 'M Inicio '.$tropasM.'<br>';
        $cuantas=0;
        while($tropasJ<$troJ||$tropasM<$troJ){
            
            $aleatorio=array_rand($this->vector,1);
            if($this->vector[$aleatorio]->tropa=='J' && $tropasJ<$troJ){
               
                $cuantas=rand(1,$troJ-$tropasJ);
                $this->vector[$aleatorio]->cantidad+=$cuantas;
                $tropasJ+=$cuantas;
                //echo 'J: '.$tropasJ.' + '.$cuantas.'<br>';
            }else if($tropasM<$troM){
                $cuantas=rand(1,$troM-$tropasM);
                $this->vector[$aleatorio]->cantidad+=$cuantas;
                $tropasM+=$cuantas;
                //echo 'M: '.$tropasM.' + '.$cuantas.'<br>';
            }
             
        }
    }
}