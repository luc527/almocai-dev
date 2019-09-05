<?php

require_once "autoload.php";

class DiaAlmoco extends AbsCodigo {
	private $alimentos = array();
	private $data;
	private $diaSemana;
	private $presencas = array();

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
		$this->diaSemana = $diaSemana;
	}

	public function getPresencas() {
		return $this->presencas;
	}

	public function setPresenca($aluno_p) {
		if ($aluno_p instanceof AlunoPresenca) {
			array_push($this->presencas, $aluno_p);
		}
	}
}

?>
