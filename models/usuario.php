<?php

class Usuario{
    public $id_usuario;
    public $email;
    private $pass;
    public $rol=2;
   

    public function __construct($email, $pass=null,$id_usuario=0) {
        $this->id_usuario = $id_usuario;
        $this->email = $email;
        $this->pass = $pass;
    }



    /**
     * Get the value of pass
     */
    public function getPass() {
        return $this->pass;
    }
}

