<?php
require_once 'territorio.php';
class Partida{
    public $idPartida;
    public $vector;
    public $resultado;
    public $id_usuario;

    public function __construct(  $id_usuario, $resultado=0 ,$idPartida=0, $longitud=20){
        $this->idPartida = $idPartida;
        $this->vector = array_fill(0,$longitud,0);
        $this->resultado = $resultado;
        $this->id_usuario = $id_usuario;
    }

    public function ataqueJugador($atacante, $defensor,$dadosJugador){
        $dadosMaquina=1;
        if($defensor->cantidad>1){
            $dadosMaquina=2;
        }
        $dadosJugador=$this->generarDados($dadosJugador);
        $dadosMaquina=$this->generarDados($dadosMaquina);
        $tropasAMover=$atacante->atacar($defensor,$dadosJugador, $dadosMaquina);
        if($tropasAMover>0){
           $this->vector[$defensor->posicion]->tropa='J';
           $this->vector[$defensor->posicion]->cantidad=$tropasAMover;
        }
        return $dados=["dadosJugador"=>$dadosJugador,
                        "dadosMaquina"=>$dadosMaquina];
    }
    public function movimiento($origen, $destino, $cantidad){
        $this->vector[$origen]->cantidad-=$cantidad;
        $this->vector[$destino]->cantidad+=$cantidad;
    
         
    }
    public function reclutamiento(){
    
        $tropasM=array_filter($this->vector,function($territorio){
            return $territorio->tropa=='M';
            });
           
           
        $tropasJ=array_filter($this->vector,function($territorio){
            return $territorio->tropa=='J';
            });
           return ["tropasM"=>$tropasM, "tropasJ"=>$tropasJ];
       }
       
    public function haGanado(){
        //1 gana la maquina
        //2 gana el jugador

        $resultado=false;
        $tropasM=array_filter($this->vector,function($territorio){
            return $territorio->tropa=='M';
            });

        $tropasJ=array_filter($this->vector,function($territorio){
            return $territorio->tropa=='J';
            });
        if(count($tropasM)==0){
            $this->resultado=2;
            $resultado=true;
        }else if(count($tropasJ)==0){
            $this->resultado=1;
            $resultado=true;
        }
        return $resultado;
    }
    public function reparto($tropasM){
        $ejercitosM=floor(count($tropasM)/3);
        if($ejercitosM<3) $ejercitosM=3;
        $territorios=array_rand($tropasM,rand(1,$ejercitosM));
       
        foreach ($territorios as $value) {
            if($ejercitosM>0){
                
                $tropasAniadidas=rand(1,$ejercitosM);
                $value->cantidad+=$tropasAniadidas;
                $ejercitosM-=$tropasAniadidas;
            }
            
        }
    }

    public function turnoMaquina(){

        $respuesta=0;
                $tJugador=array_filter($this->vector,function($territorio){
                    return $territorio->tropa=='J';
                });
                $tropTotalesJu=0;
                foreach ($tJugador as $value) {
                    $tropTotalesJu+=$value->cantidad;
                }
                $tMaquina=array_filter($this->vector,function($territorio){
                return $territorio->tropa=='M';
                });
                $tropTotalesMa=0;
                foreach ($tMaquina as $value) {
                    $tropTotalesMa+=$value->cantidad;
                }
                $tFuertes= array_filter($tMaquina, function($territorio){
                    return $territorio->cantidad>2;
                });
                
                $aleatorio=rand(1,10);
            if(!empty($tFuertes) && $tropTotalesMa>=$tropTotalesJu){
               
                foreach ($tFuertes as $value) {
                  
                    if($value->posicion>0 && $this->vector[$value->posicion-1]->tropa=='J' 
                    ){
                      
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion-1]);
                        $value->atacar($this->vector[$value->posicion-1],$dados['atacante'],$dados['defensor']);
                        $this->moverRestantes($value->posicion,$this->vector[$value->posicion-1]->posicion,$this->vector[$value->posicion+1]->posicion);
                       
                    }else if($value->posicion<count($this->vector) && $this->vector[$value->posicion+1]->tropa=='J' 
                    ){
                       
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion+1]);
                        $value->atacar($this->vector[$value->posicion+1],$dados['atacante'],$dados['defensor']);
                        $this->moverRestantes($value->posicion,$this->vector[$value->posicion+1]->posicion,$this->vector[$value->posicion+1]->posicion);
                    }
               
                

                }

            }else if($aleatorio<=2){
                foreach ($tMaquina as $value) {
                    if($value->posicion>0 && $this->vector[$value->posicion-1]->tropa=='J' 
                    && $this->vector[$value->posicion]->cantidad>1){
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion-1]);
                        $value->atacar($this->vector[$value->posicion-1],$dados['atacante'],$dados['defensor']);
                        $this->moverRestantes($value->posicion,$this->vector[$value->posicion-1]->posicion);
                    }else if($value->posicion>0 && $this->vector[$value->posicion+1]->tropa=='J' 
                    && $this->vector[$value->posicion]->cantidad>1){
                        $dados=$this->cuantosDados($value,$this->vector[$value->posicion+1]);
                        $value->atacar($this->vector[$value->posicion+1],$dados['atacante'],$dados['defensor']);
                        $this->moverRestantes($value->posicion, $this->vector[$value->posicion+1]->posicion);
                    }
                    
                }
            }else{
                $tFuertes= array_filter($tMaquina, function($territorio){
                    return $territorio->cantidad>1;
                });
                $queHacer=$this->deboMover($tFuertes);
                if($queHacer["movimiento"]==1){
                   
                    $this->movimiento($queHacer['origen']->posicion,$queHacer['destino']->posicion,$queHacer['canTropas']);
                    
                    $respuesta=1;
                }else {
                    $respuesta=2;
                }
               
            }
        
        return $respuesta;
    }
    function moverRestantes($maquina, $defensor){
        if($this->vector[$maquina]->cantidad==0){
            $this->vector[$maquina]->tropa='J';
            $this->vector[$maquina]->cantidad=$this->vector[$defensor]->cantidad-1;
            $this->vector[$defensor]->cantidad=1;
        }
        if($this->vector[$defensor]->cantidad==0){
            $this->vector[$defensor]->tropa='M';
            $this->vector[$defensor]->cantidad=$this->vector[$maquina]->cantidad-1;
            $this->vector[$maquina]->cantidad=1;
        }
       
    }

    function deboMover($tFuertes){
        
        $respuesta=['movimiento'=>0];
        //1-> si mueve
        //2-> no mueve
       
        foreach ($tFuertes as $value) {
            if($value->posicion>1 && $value->posicion<count($tFuertes)-1
            &&$this->vector[$value->posicion-1]->tropa=='M'
            &&$this->vector[$value->posicion-1]->cantidad==1 
            && $this->vector[$value->posicion-2]->tropa=='J'){
            
                $respuesta=['movimiento'=>1,
                            'origen'=>$this->vector[$value->posicion],
                            'destino'=>$this->vector[$value->posicion-1],
                            'canTropas'=>$this->vector[$value->posicion]->cantidad-1];
            }else if($value->posicion>1 && $value->posicion<count($tFuertes)-1 
            &&$this->vector[$value->posicion+1]->tropa=='M'
            &&$this->vector[$value->posicion+1]->cantidad==1 
            && $this->vector[$value->posicion+2]->tropa=='J'){
                $respuesta=['movimiento'=>1,
                            'origen'=>$this->vector[$value->posicion],
                            'destino'=>$this->vector[$value->posicion+1],
                            'canTropas'=>$this->vector[$value->posicion]->cantidad-1];
            }
        }
        return $respuesta;
    }

    public function cuantosDados($atacante, $defensor){
        $dadosAtacante=0;
        $dadosDefensor=0;
       
        if($atacante->cantidad>3){
            
            $dadosAtacante=$this->generarDados(3);
          
        }else{
            $dadosAtacante=$this->generarDados(2);
            
        }

        if($defensor->cantidad>1){
            $dadosDefensor=$this->generarDados(2);
        }else{
            $dadosDefensor=$this->generarDados(1);
        }
        $dados=['atacante'=>$dadosAtacante,
                'defensor'=>$dadosDefensor];
        return $dados;

    }
    public function generarDados($cantidadDados){
        $dados=[];
        for ($i=0; $i < $cantidadDados; $i++) { 
            $dados[]=rand(1,6);
        }
        
        sort($dados);
        
        return $dados;

    }
   

        

    public function distribuirTropas(){
        $aleatorio=array_rand($this->vector,count($this->vector)/2); 
        foreach ($aleatorio as  $value) {
            $this->vector[$value]=new Territorio($value,'J',1);
        }

        for ($i=0; $i < count($this->vector); $i++) { 
            if($this->vector[$i] instanceof Territorio){
                $this->vector[$i]->cantidad=1;
            }else{
                $this->vector[$i]= new Territorio($i,'M',1);
            }

        }
        
            
    }

    function aniadirSobrantes($trop,$canTropas){
        
        $tropasJ=0;
        $cuantas=0;
      
        while($tropasJ<$canTropas){
            
            $aleatorio=array_rand($this->vector,1);
            if($this->vector[$aleatorio]->tropa===$trop){
               
                $cuantas=rand(1,$canTropas-$tropasJ);
                $this->vector[$aleatorio]->cantidad+=$cuantas;
                $tropasJ+=$cuantas;
                
            }
             
        }
    }
    function distribuirTropasCustom($tropaSituadas){
        foreach ($tropaSituadas as $key => $value) {
            $this->vector[$value->pos]=new Territorio($value->pos, 'J',$value->cantidad);
        }
        
        foreach($this->vector as $key => $value){
            if(!$value instanceof Territorio){
                $this->vector[$key]=new Territorio($key,'M',1);
                //echo json_encode($value);
            }
        }
 
    }
}