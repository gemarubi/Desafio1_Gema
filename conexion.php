<?php 
require_once 'parametros.php';
require_once 'partida.php';
require_once 'usuario.php';

class Conexion{

    public static function conectar(){
        try {
            $conexion = new mysqli(Parametros::$localhost, Parametros::$usuario, Parametros::$pass, Parametros::$bbdd);
   
          
            return $conexion;
        }
        catch (Exception $e){
            return -1;
        }
        
    }
    public static function desconectar($conexion, $stmt){
        $stmt->close();
        $conexion->close();
    }
    public static function buscarUltimaPartida(){
        try{
            $conexion=self::conectar();
            try{
                $datos=0;
        
                $consulta = "SELECT * FROM PARTIDA WHERE ID_PARTIDA =  (SELECT MAX(ID_PARTIDA) FROM PARTIDA)";
                $stmt = $conexion->prepare($consulta);
                
                $stmt->execute();
                $resultados = $stmt->get_result();
                
                    while( $fila = $resultados->fetch_array())
                        {
                      
                        $datos=new Partida($fila[2],$fila[1],$fila[0]);
                         
                        }
                        //echo json_encode($datos);
                    $resultados -> free_result();
                
                self::desconectar($conexion,$stmt);
                 return  $datos;
            }catch(Exception $e){
  
                    return 0;
                }
  
        }catch(Exception $e){
            return -1;
        }

    }
 
    public static function guardarPartida($partida){
        //-1 error de conexion
        //0 error de consulta (clave duplicada o algo asi)
       
        try{
            $conexion=self::conectar();
            try{
                $consulta = "INSERT INTO PARTIDA VALUES (?,?,?)";
                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param("iii", $partida->idPartida,$partida->resultado,$partida->id_usuario);
                $stmt->execute();
                $stmt->close();
                return $stmt;
            }catch(Exception $e){

                return 0;
            }

        }catch(Exception $e){
        return -1;
        }  
    }
    public static function guardarTablero($tablero, $id_Partida){
       //-1 error de conexion
        //0 error de consulta (clave duplicada o algo asi)
        //1 todo ha ido bien
        try{
            $conexion=self::conectar();
            try{
               //var_dump($territorio);
              foreach ($tablero as $key => $value) {
                $consulta = "INSERT INTO TERRITORIO VALUES (?,?,?,?,?)";
                $stmt = $conexion->prepare($consulta);
                    if($value!=null){
                    $stmt->bind_param("iisii", $value->id,$value->posicion, $value->tropa, $value->cantidad, $id_Partida);
                    }
                $stmt->execute();
                $stmt->close();
              }
               
                return 1;
              
                   
                    
                
               
            }catch(Exception $e){

                return 0;
            }

        }catch(Exception $e){
        return -1;
        }  
    }

    public static function buscarTablero($idPartida){

        try{
            $conexion=self::conectar();
            try{
                $datos=0;
        
                $consulta = "SELECT * FROM TERRITORIO WHERE ID_PARTIDA = ?";
                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param("i", $idPartida); 
                $stmt->execute();
                $resultados = $stmt->get_result();
                $tablero=[];
                    while( $fila = $resultados->fetch_array())
                        {
                      
                        $datos=new Territorio($fila[1],$fila[2],$fila[3],$fila[4]);
                          $tablero[]=$datos;
                        }
              
                    $resultados -> free_result();
                
                self::desconectar($conexion,$stmt);
                 return  $tablero;
            }catch(Exception $e){
  
                    return 0;
                }
  
        }catch(Exception $e){
            return -1;
        }

    }
  

  public static function buscarUsuario($correo){

    try{
        $conexion=self::conectar();
        try{
            $datos=0;
    
            $consulta = "SELECT ID_USUARIO, CORREO FROM USUARIO WHERE CORREO = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("s", $correo); 
            $stmt->execute();
            $resultados = $stmt->get_result();
         
                while( $fila = $resultados->fetch_array())
                    {
                  
                    $datos=new Usuario($fila[0],$fila[1]);
                     
                    }
          
                $resultados -> free_result();
            
            self::desconectar($conexion,$stmt);
             return  $datos;
        }catch(Exception $e){

                return 0;
            }

    }catch(Exception $e){
        return -1;
    }
  }

  public static function buscarPartidaUser($id_usuario){
    //-1 error de conexion
  //0 error de consulta (clave duplicada o algo asi)

  try{
      $conexion=self::conectar();
      try{
          $datos=0;
  
          $consulta = "SELECT * FROM PARTIDA WHERE ID_USUARIO = ?";
          $stmt = $conexion->prepare($consulta);
          $stmt->bind_param("i", $id_usuario); 
          $stmt->execute();
          $resultados = $stmt->get_result();
          $partidas=[];
              while( $fila = $resultados->fetch_array())
                  {
                
                  $datos=new Partida($fila[2],$fila[1],$fila[0]);
                    $partidas[]=$datos;
                  }
        
              $resultados -> free_result();
          
          self::desconectar($conexion,$stmt);
           return  $partidas;
      }catch(Exception $e){

              return 0;
          }

  }catch(Exception $e){
      return -1;
  }

}
}