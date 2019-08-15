<?php

require_once "autoload.php";

class DiaAlmoco extends AbsCodigo
{
	private $alimentos = array();
	private $data;

	public function __construct ($array_alimentos, $data)
	{
		$this->setAlimentos($array_alimentos);
		$this->setData($data);
	}

	public function getAlimentos ()
	{
		return $this->alimentos;
	}

	public function setAlimentos ($array_alimentos)
	{
		for ($i=0; $i < count($array_alimentos); $i++)
		{ 
			if ($array_alimentos[$i] instanceof Alimento)
			{
				array_push ($this->alimentos, $array_alimentos[$i]);
			}
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