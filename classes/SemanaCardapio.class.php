<?php

require_once "autoload.php";

class SemanaCardapio extends AbsCodigo
{
	private $dias = array();

	public function getDias () {
		return $this->dias;
	}

	public function setDia ($dia) {
		if ($dia instanceof DiaAlmoco) {
			array_push ($this->dias, $dia);
		}
	}
}

?>