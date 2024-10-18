<?php
require_once 'partida.php';
require_once 'controladorPartida.php';
require_once 'controladorAdmin.php';
$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametros = explode("/",$paths);
$datosRecibidos = file_get_contents("php://input");
$body=json_decode($datosRecibidos,false);
unset($parametros[0]);
;

if($requestMethod=='POST' && strtoupper($parametros[1])=='GAMER' && empty($parametros[2])){
    
    ControladorPartida::iniciarJuegoEstandar($body);

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='GAMER' && strtoupper($parametros[2])=='CUSTOM'){
   // debe tener correo, longitud del tablero, cantidad de tropas y tantas posiciones como tropas restantes haya :(cantTropas-longitud)/2
    ControladorPartida::iniciarJuegoPersonalizado($body);
}else if($requestMethod=='GET'  && strtoupper($parametros[1])=='ADMIN'&& empty($parametros[2])){
    ControladorAdmin::getAllUsers();
}else if($requestMethod=='POST' && strtoupper($parametros[1])=='ADMIN' && empty($parametros[2])){
    ControladorAdmin::insertUser($body);
}else if($requestMethod=='PUT' && strtoupper($parametros[1])=='ADMIN' && empty($parametros[2])){
    ControladorAdmin::asignarRol($body);
}else if($requestMethod=='DELETE' && strtoupper($parametros[1]) =='ADMIN' && empty($parametros[2])){
    ControladorAdmin::deleteUser($body);
}