<?php

class Territorio{
    public $id;
    public $posicion;
    public $tropa;
    public $cantidad;

    public function __construct($id=1, $posicion,$tropa, $cantidad=0){
        $this->id=$id;
        $this->posicion=$posicion;
        $this->tropa = $tropa;
        $this->cantidad = $cantidad;
    }

   
}