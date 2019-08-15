<?php
require_once "autoload.php";
class AbsUsuario extends AbsCodigo{
    private $nome, $senha;

    public function setSenha($senha){
        $this->senha = $senha;
    }
    public function getSenha(){
        return $this->senha;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getNome(){
        return $this->nome;  
    }
}
?>