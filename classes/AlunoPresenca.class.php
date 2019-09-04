<?php

require_once("autoload.php");

class AlunoPresenca {
	private $aluno;
	private $presenca; // campo binário: presente (1) ou ausente (0)

	public function getPresenca() {
		return $this->presenca;
	}
	public function setPresenca($presenca) {
		$this->presenca = $presenca;
	}

	public function getAluno() {
		return $this->aluno;
	}
	public function setAluno($aluno) {
		if ($aluno instanceof Aluno) {
			$this->aluno = $aluno;
		}
	}
}
?>