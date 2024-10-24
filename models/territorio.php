<?php

class Territorio{
    public $id;
    public $posicion;
    public $tropa;
    public $cantidad;

    public function __construct($posicion,$tropa, $cantidad=0,$id=0){
       $this->id=$id;
        $this->posicion=$posicion;
        $this->tropa = $tropa;
        $this->cantidad = $cantidad;
    }

    function atacar( &$defensor,$dadosAtacante,$dadosDefensor){
       $resultado=0;
       
        while(count($dadosDefensor)>0 && $defensor->cantidad>0 && $this->cantidad>1){
            if(end($dadosAtacante)>end($dadosDefensor)){
                $defensor->cantidad--;
               
            }else{
                $this->cantidad--;
            }
            array_pop($dadosAtacante);
            array_pop($dadosDefensor);
        }
       return count($dadosAtacante);
          
    }

   
   
}