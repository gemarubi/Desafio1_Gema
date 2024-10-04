<?php
require_once 'partida.php';
require_once 'tablero.php';
$tablero=new Tablero();
$tablero->distribuirTropas();
echo json_encode($tablero);
