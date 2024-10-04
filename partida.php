<?php
class Partida{
    public $idPartida;
    public $tablero;
    public $resultado;
    public $id_usuario;

    public function __construct($idPartida, $tablero, $resultado, $id_usuario){
        $this->idPartida = $idPartida;
        $this->tablero = $tablero;
        $this->resultado = $resultado;
        $this->id_usuario = $id_usuario;
    }

}