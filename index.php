<?php
require_once 'models/partida.php';
require_once 'controllers/controladorPartida.php';
require_once 'controllers/controladorAdmin.php';
require_once 'controllers/controladorUsuario.php';

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
    
    ControladorAdmin::getAllUsers($body);

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='ADMIN' && empty($parametros[2])){
    
    ControladorAdmin::insertUser($body);

}else if($requestMethod=='PUT' && strtoupper($parametros[1])=='ADMIN' && empty($parametros[2])){
    
    ControladorAdmin::updateRol($body);

}else if($requestMethod=='DELETE' && strtoupper($parametros[1]) =='ADMIN' && empty($parametros[2])){
    
    ControladorAdmin::deleteUser($body);
}else if($requestMethod=='POST' && strtoupper($parametros[1])=='USER'&& empty($parametros[2])){
    
    ControladorUsuario::consultarDatosUser($body->correo, $body->pass);

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='USER'&& strtoupper($parametros[2])== 'ESTADISTICA'){
    
    ControladorUsuario::verEstadisticas($body->correo,$body->pass);

}else if($requestMethod=='PUT' && strtoupper($parametros[1])=='GAMER'&& empty($parametros[2])){
    
    ControladorPartida::moverTropasJugador($body);

}else if($requestMethod=='PUT' && strtoupper($parametros[1])=='GAMER' && strtoupper($parametros[2])=='ATTACK'){
    
    ControladorPartida::atacarJugador($body);

}else if($requestMethod=='PUT' && strtoupper($parametros[1])=='GAMER' && strtoupper($parametros[2])=='PASSTURN'){
    
    ControladorPartida::jugadaMaquina($body);

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='USER' && strtoupper($parametros[2])=='NEWPASS'){
    
    ControladorUsuario::restablecerPass($body);

}else{
    
    header("HTTP/1.1 404 Servicio incorrecto");
    $respuesta=[
        'cod'=> 404,
        'data' => 'Incorrecto'
    ];

    echo json_encode($respuesta);
}