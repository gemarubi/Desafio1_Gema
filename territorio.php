<?php

class Territorio{
    public $id;
    public $posicion;
    public $tropa;
    public $cantidad;

    public function __construct(/*$id=0*/$posicion,$tropa, $cantidad=0){
       // $this->id=$id; no me funciona
        $this->posicion=$posicion;
        $this->tropa = $tropa;
        $this->cantidad = $cantidad;
    }

   
}