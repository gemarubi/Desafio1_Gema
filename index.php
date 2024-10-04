<?php
require_once 'partida.php';
$tablero=new Partida(1,2);
$tablero->distribuirTropas();
echo json_encode($tablero);
