<?php
require_once 'partida.php';
require_once 'controladorPartida.php';
require_once 'controladorUsuario.php';
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

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='USER'&& empty($parametros[2])){
    
    ControladorUsuario::consultarDatosUser($body->correo);

}else if($requestMethod=='POST' && strtoupper($parametros[1])=='USER'&& strtoupper($parametros[2])== 'ESTADISTICA'){
    
    ControladorUsuario::verEstadisticas($body->correo);
}

//Intento poner correo en la ruta pero no funciona
/*else if($requestMethod=='GET' && strtoupper($parametros[1]=='USER') && strtoupper($parametros[2])){
    $v=split($parametros[2],':');
    $correo=$v[0].'@'.$v[1];
    ControladorUsuario::consultarDatosUser($correo);
}*/