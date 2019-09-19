<?php

require_once("autoload.php");

abstract class AbsCodigoDescricao extends AbsCodigo {
	private $descricao;

	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($d) {
		$this->descricao = $d;
	}
}

?>
