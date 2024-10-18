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

    public function distribuirTropas(){
        $aleatorio=array_rand($this->vector,count($this->vector)/2); 
        foreach ($aleatorio as  $value) {
            $this->vector[$value]=new Territorio($value,'J',1);
           
        }

        for ($i=1; $i < count($this->vector); $i++) { 
            if($this->vector[$i] instanceof Territorio){
                $this->vector[$i]->cantidad=1;
            }else{
                $this->vector[$i]= new Territorio($i,'M',1);
            }
        }
            
    }

    function aniadirSobrantes($tropa,$canTropas=5){
        
        $tropasJ=0;
        $cuantas=0;
        while($tropasJ<$canTropas){
            
            $aleatorio=array_rand($this->vector,1);
            if($this->vector[$aleatorio]->tropa==$tropa){
               
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