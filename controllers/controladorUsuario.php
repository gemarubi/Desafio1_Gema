<?php

require_once './accesoBBDD/conexion.php';
require_once 'controladorPartida.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once './phpmailer/src/Exception.php';
require_once './phpmailer/src/PHPMailer.php';
require_once './phpmailer/src/SMTP.php';

class ControladorUsuario{

    public static function consultarDatosUser($correo,$pass){
      
        $usuario= self::buscarUsuario($correo,$pass);
        
        if($usuario instanceof Usuario){
            $rol=Conexion::buscarRol($usuario->id_usuario);
            $usuario->rol=$rol;
            echo json_encode($usuario);
        }else{
            ControladorPartida::mostrarError();
        }
        
    }

    public static function restablecerPass($body){
        $usuario=Conexion::buscarUsuario($body->correo);
        if($usuario instanceof Usuario){
         $newPass=rand(10000,99999);
         Conexion::cambiarPass($usuario->id_usuario,md5($newPass));
    
            try {
                $mail = new PHPMailer();
                          //Habilitar los mensajes de depuración
                $mail->isSMTP();                                 
                $mail->Host       = Parametros::$host;          
                $mail->SMTPAuth   = true;                          
                $mail->Username   = Parametros::$email;           
                $mail->Password   = Parametros::$passCorreo;                     
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
                $mail->Port       = 465;                           
            
                //Emisor
                $mail->setFrom(Parametros::$email, 'App Risk');
            
                //Destinatarios
                $mail->addAddress($body->correo);     
              
                $mail->isHTML(true);                         //Establecer el formato de correo electrónico en HTMl
                $mail->Subject = 'Restablecer contraseña';
                $mail->Body    = '<h2>Tu nueva contraseña es:'.$newPass.'</h2>';
                $mail->AltBody = 'Tu nueva contraseña es:'.$newPass;
            
                $mail->send();    //Enviar correo eletrónico
                
            } catch (Exception $e) {
               $res=$e;
              
            }
        }

        echo json_encode(['mensaje'=>'El correo ha sido enviado']);
    }
    public static function buscarUsuario($correo, $pass){
        $res=false;
        $usuario= Conexion::buscarUsuario($correo);
        if($usuario instanceof Usuario){
            $login=Conexion::comprobarPass($usuario->id_usuario);
            if($login==md5($pass) ){
                $res= $usuario; 
            }
        }
        return $res;
       
    }

    public static function verEstadisticas($correo, $pass){
        $user=self::buscarUsuario($correo,$pass);
        
        if($user instanceof Usuario ){
            $partidas=Conexion::buscarPartidaUser($user->id_usuario);
            $respuesta=[];
            foreach ($partidas as $value) {
            
                $respuesta[]= 'idPartida: '.$value->idPartida." Usuario: ".$value->id_usuario." Resultado: ".$value->resultado;
            }
       
            echo json_encode($respuesta);
        }else{
            ControladorPartida::mostrarError();
        }
       
    }



   
}