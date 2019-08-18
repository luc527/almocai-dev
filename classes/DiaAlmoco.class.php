<?php

require_once "autoload.php";

class DiaAlmoco extends AbsCodigo {
	private $alimentos = array();
	private $data;
	private $diaSemana;

	/*
	public function __construct ($data)
	{
		$this->setData($data);
	}
	*/

	public function getAlimentos ()	{
		return $this->alimentos;
	}

	public function setAlimento ($alimento)	{
		if ($alimento instanceof Alimento) {
			array_push ($this->alimentos, $alimento);
		}
	}

	public function getData () {
		return $this->data;
	}

	public function setData ($data) {
		$this->data = $data;
	}

	public function getDiaSemana () {
		return $this->diaSemana;
	}

	public function setDiaSemana ($diaSemana) {
		if ($diaSemana instanceof DiaSemana) {
			$this->diaSemana = $diaSemana;
		}
	}
}

?>