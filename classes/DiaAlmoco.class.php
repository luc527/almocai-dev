<?php

require_once "autoload.php";

class DiaAlmoco extends AbsCodigo
{
	private $alimentos = array();
	private $data;

	/*
	public function __construct ($data)
	{
		$this->setData($data);
	}
	*/

	public function getAlimentos ()
	{
		return $this->alimentos;
	}

	public function setAlimento ($alimento)
	{
		if ($array_alimentos[$i] instanceof Alimento)
		{
			array_push ($this->alimentos, $array_alimentos[$i]);
		}
	}

	public function getData ()
	{
		return $this->data;
	}

	public function setData ($data)
	{
		$this->data = $data;
	}
}

?>