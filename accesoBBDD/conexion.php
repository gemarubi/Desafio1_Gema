<?php 
require_once 'parametros.php';
require_once './models/partida.php';
require_once './models/usuario.php';

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
    public static function desconectar($conexion){
        //$stmt->close();
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
                
               
                 return  $datos;
            }catch(Exception $e){
  
                    return 0;
                }
  
        }catch(Exception $e){
            return -1;
        }finally{
            self::desconectar($conexion);
        }

    }
    public static function cambiarResultado($partida){
        try{
            $conexion=self::conectar();
            try {
                
                $consulta = "UPDATE PARTIDA SET RESULTADO = ? WHERE ID_PARTIDA = ?";
                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param("ii",$partida->resultado, $partida->idPartida);
                $stmt->execute();
                $stmt->close();
             
               
                return 1;
            } catch(Exception $e){
  
                return 0;
            }

        }catch(Exception $e){
        return -1;
        }
        finally{
            self::desconectar($conexion);
        }
    }

    public static function guardarMovimiento($territorio, $idPartida){
        
        try{
            $conexion=self::conectar();
            try {
                
                $consulta = "UPDATE TERRITORIO SET CANTIDAD = ? WHERE ID_PARTIDA = ? AND POSICION=?";
                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param("iii",$territorio->cantidad, $idPartida, $territorio->posicion);
                $stmt->execute();
                $stmt->close();
             
               
                return 1;
            } catch(Exception $e){
  
                return 0;
            }

        }catch(Exception $e){
        return -1;
        }
        finally{
            self::desconectar($conexion);
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
              
           
               return  $partidas;
          }catch(Exception $e){

                  return 0;
              }

      }catch(Exception $e){
          return -1;
      }finally{
        self::desconectar($conexion);
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
                
                return 1;
            }catch(Exception $e){

                return 0;
            }
            
        }catch(Exception $e){
        return -1;
        }finally{
            self::desconectar($conexion);
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
        }finally{
            self::desconectar($conexion);
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
                
                
                 return  $tablero;
            }catch(Exception $e){
  
                    return 0;
                }
  
        }catch(Exception $e){
            return -1;
        }finally{
            self::desconectar($conexion);
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
                  
                    $datos=new Usuario($fila[1]);
                    $datos->id_usuario=$fila[0];
                     
                    }
          
                $resultados -> free_result();
            
        
             return  $datos;
        }catch(Exception $e){

                return 0;
            }

    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function comprobarPass($id){
    try{
        $conexion=self::conectar();
        try{
            $datos=0;
    
            $consulta = "SELECT PASS FROM USUARIO WHERE ID_USUARIO = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("s", $id); 
            $stmt->execute();
            $resultados = $stmt->get_result();
         
                while( $fila = $resultados->fetch_array())
                    {
                  
                 
                    $datos=$fila[0];
                     
                    }
          
                $resultados -> free_result();
            
        
             return  $datos;
        }catch(Exception $e){

                return 0;
            }

    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function buscarRol($id){
    try{
        $conexion=self::conectar();
        try{
            $datos=[];
    
            $consulta = "SELECT ROL.DESCRIPCION FROM ROL 
                        JOIN ROL_USUARIO ON ROL.ID_ROL= ROL_USUARIO.ID_ROL 
                        JOIN USUARIO ON ROL_USUARIO.ID_USUARIO=USUARIO.ID_USUARIO
                        WHERE USUARIO.ID_USUARIO = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id); 
            $stmt->execute();
            $resultados = $stmt->get_result();
           
                while( $fila = $resultados->fetch_array())
                    {
                  
                        
                    $datos[]=$fila[0];
                     
                    }
          
                $resultados -> free_result();
            
        
             return  $datos;
        }catch(Exception $e){

                return 0;
            }

    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function buscarTodosUsuarios(){

    try{
        $conexion=self::conectar();
        try{
            $datos=0;
    
            $consulta = "SELECT ID_USUARIO, CORREO FROM USUARIO ";
            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
            $resultados = $stmt->get_result();
            $listaUser=[];
                while( $fila = $resultados->fetch_array())
                    {
                  
                    $datos=new Usuario($fila[1]);
                     $datos->id_usuario=$fila[0];
                     $listaUser[]=$datos;
                    }
          
                $resultados -> free_result();
            
             return  $listaUser;
        }catch(Exception $e){

                return 0;
            }

    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function registrarUsuarios($usuarios){

    try{
        $conexion=self::conectar();
        try{
           
          foreach ($usuarios as $key => $value) {
            $consulta = "INSERT INTO USUARIO VALUES (?,?,?)";
            $stmt = $conexion->prepare($consulta);
                if($value!=null){
                    $pass=$value->getPass();
                $stmt->bind_param("iss", $value->id_usuario,$value->email,$pass);
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
    } finally{
        self::desconectar($conexion);
    } 
  }

  public static function cambiarPass($id,$pass){
    try{
        $conexion=self::conectar();
        try {
            
            $consulta = "UPDATE USUARIO SET PASS= ? WHERE ID_USUARIO= ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("is",$id, $pass);
            $stmt->execute();
            $stmt->close();
            
           
            return 1;
        } catch(Exception $e){

            return 0;
        }
      
    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function asignarRol($id, $rol){
    try{
       
        $conexion=self::conectar();
        try{
           
            $consulta = "INSERT INTO ROL_USUARIO VALUES (?,?)";
            $stmt = $conexion->prepare($consulta);
                
            $stmt->bind_param("ii",$id,$rol);
                
            $stmt->execute();
            $stmt->close();
           
          
           
            return 1;
           
        }catch(Exception $e){

            return 0;
        }
       
    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function updateRol($id_user, $rol){
    try{
        $conexion=self::conectar();
        try {
            
            $consulta = "UPDATE ROL_USUARIO SET ID_ROL= ? WHERE ID_USUARIO= ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("ii",$rol, $id_user);
            $stmt->execute();
            $stmt->close();
            
           
            return 1;
        } catch(Exception $e){

            return 0;
        }
      
    }catch(Exception $e){
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function borrar($id_user){
    try {
        $conexion=self::conectar();
        try{
            $consulta = "DELETE FROM USUARIO  WHERE ID_USUARIO = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $stmt->close();
           
            return 1;
        }catch (Exception $e){
            return 0;
        }
      
    } catch (Exception $e) {
        return -1;
    }finally{
        self::desconectar($conexion);
    }

  }
  public static function borrarPartidas($id_user){
    try {
        $conexion=self::conectar();
        try{
            $consulta = "DELETE FROM PARTIDA  WHERE ID_USUARIO = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $stmt->close();
           
            return 1;
        }catch (Exception $e){
            return 0;
        }
      
    } catch (Exception $e) {
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }
  public static function borrarTerritorios($id_partida){
    try {
        $conexion=self::conectar();
        try{
            $consulta = "DELETE FROM TERRITORIO  WHERE ID_PARTIDA = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_partida);
            $stmt->execute();
            $stmt->close();
           
            return 1;
        }catch (Exception $e){
            return 0;
        }
      
    } catch (Exception $e) {
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

  public static function borrarRol($id_user){
    try {
        $conexion=self::conectar();
        try{
            $consulta = "DELETE FROM ROL_USUARIO  WHERE ID_USUARIO= ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $stmt->close();
           
            return 1;
        }catch (Exception $e){
            return 0;
        }
      
    } catch (Exception $e) {
        return -1;
    }finally{
        self::desconectar($conexion);
    }
  }

}

