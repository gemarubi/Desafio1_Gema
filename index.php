<?php
require_once 'partida.php';
require_once 'controladorPartida.php';
$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametros = explode("/",$paths);
$datosRecibidos = file_get_contents("php://input");
$body=json_decode($datosRecibidos,false);
unset($parametros[0]);

if($requestMethod=='GET' && strtoupper($parametros[1])=='GAMER' && empty($parametros[2])){
    ControladorPartida::iniciarJuegoEstandar($body);
}