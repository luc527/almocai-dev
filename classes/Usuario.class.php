<?php

require_once "autoload.php";

class Usuario extends AbsCodigo {
    private $nome;
    private $senha;
    private $tipo;

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
      if ($t instanceof TipoUsuario) {
        $this->tipo = $t;
      }
    }

    public function getTipo() {
      return $this->tipo;
    }
}
?>
