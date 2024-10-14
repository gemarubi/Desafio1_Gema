<?php

class Usuario{
    public $id_usuario;
    public $email;
    private $pass;

    public function __construct($id_usuario=0, $email, $pass=null) {
        $this->id_usuario = $id_usuario;
        $this->email = $email;
        $this->pass;
    }


}

