<?php

require_once "autoload.php";

class Alimento extends AbsCodigo {
	private $descricao;
	private $tipo;

	/*
	public function __construct ($descricao) {
		$this->descricao = $descricao;
	}
	*/

	public function getDescricao ()	{
		return $this->descricao;
	}

	public function setDescricao ($descricao) {
		$this->descricao = $descricao;
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($t) {
		if ($t instanceof TipoAlimento) {
			$this->tipo = $t;
		}
	}
}

?>
