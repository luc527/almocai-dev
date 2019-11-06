<?php
require_once("AbsCodigo.class.php");
require_once("Alimentacao.class.php");
require_once("Carne.class.php");
require_once("Frequencia.class.php");

class Usuario extends AbsCodigo
{
    private $nome;
    private $username;
    private $senha;
    private $tipo;
    private $email;
    private $alimentacao; // vegetariano, vegano ou nenhum dos dois
    private $carnes_come = array(); // quais carnes come
    private $frequencia; // se almoça sempre no if, nunca, as vezes etc.
    private $intolerancia_usuario = array();

    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getUsername()
    {
        return $this->username;
    }
    
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

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
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

    public function setIntolerancia_usuario($intol)
    {
        if ($intol instanceof IntoleranciaUsuario) {
            $this->intolerancia_usuario = $intol;
        }
    }

    public function getIntolerancia_usuario()
    {
        return $this->intolerancia_usuario;
    }


    public function hash()
    {
        return sha1("{$this->getUsername()}{$this->getSenha()}Almoçaí__EngSoftProg2019-Texto_Extra_Para_Segurança");
    }
}
