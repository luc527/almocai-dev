<?php

class DiaSemana extends AbsCodigo {
	private $descricao;

	/*
	public function __construct ($descricao)
	{
		$this->setDescricao($descricao);
	}
	*/

	public function getDescricao () {
		return $this->descricao;
	}

	public function setDescricao ($desc) {
		$this->descricao = $desc;
	}
}

?>