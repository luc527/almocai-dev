<?php
require_once("AbsCodigo.class.php");

class Usuario extends AbsCodigo {
    private $nome;
    private $senha;
    private $tipo;
    private $alimentacao; // vegetariano, vegano ou nenhum dos dois
    private $carnes = array(); // quais carnes come

    public function setSenha ($senha) {
        $this->senha = $senha;
    }
    public function getSenha () {
        return $this->senha;
    }

    public function setNome ($nome) {
        $this->nome = $nome;
    }

    public function getNome () {
        return $this->nome;
    }

    public function setTipo($t) {
      $this->tipo = $t;
    }

    public function getTipo() {
      return $this->tipo;
    }

    public function setAlimentacao ($a) {
        $this->alimentacao = $a;
    }

    public function getAlimentacao () {
        return $this->alimentacao;
    }
}
?>
