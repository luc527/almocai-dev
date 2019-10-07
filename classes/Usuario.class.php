<?php
require_once("AbsCodigo.class.php");
require_once("Alimentacao.class.php");
require_once("Carne.class.php");
require_once("Frequencia.class.php");

class Usuario extends AbsCodigo
{
    private $nome;
    private $senha;
    private $tipo;
    private $alimentacao; // vegetariano, vegano ou nenhum dos dois
    private $carnes_come = array(); // quais carnes come
    private $frequencia; // se almoça sempre no if, nunca, as vezes etc.
    private $intolerancia_usuario = array();

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setTipo($t)
    {
        $this->tipo = $t;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setAlimentacao($a)
    {
        if ($a instanceof Alimentacao) {
            $this->alimentacao = $a;
        }
    }

    public function getAlimentacao()
    {
        return $this->alimentacao;
    }

    public function setCarne($c)
    {
        if ($c instanceof Carne) {
            array_push($this->carnes_come, $c);
        }
    }

    public function getCarnes()
    {
        return $this->carnes_come;
    }

    public function setFrequencia($f)
    {
        if ($f instanceof Frequencia) {
            $this->frequencia = $f;
        }
    }

    public function getFrequencia()
    {
        return $this->frequencia;
    }
}
