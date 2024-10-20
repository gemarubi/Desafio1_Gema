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
       
       
        while(count($dadosDefensor)>0){
            if(end($dadosAtacante)>end($dadosDefensor)){
                $defensor->cantidad--;
               
            }else{
                $this->cantidad--;
            }
            array_pop($dadosAtacante);
            array_pop($dadosDefensor);
        }
          
    }

   
   
}