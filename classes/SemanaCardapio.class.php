<?php

require_once "autoload.php";

class SemanaCardapio extends AbsCodigo
{
	private $dias = array();

	public function getDias ()
	{
		return $this->dias;
	}

	public function setDias ($dias)
	{
		for ($i=0; $i < count($dias); $i++)
		{ 
			if ($dias[$i] instanceof DiaAlmoco)
			{
				array_push ($this->dias, $dias[$i]);
			}
		}
	}
}

?>