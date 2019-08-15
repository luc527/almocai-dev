<?php

require_once "autoload.php";

class SemanaCardapioDao
{
	private $instance;

	public static function getInstance ()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new SemanaCardapioDao;
		}
		return $instance;
	}

	public function Inserir (SemanaCardapio $semanaCardapio)
	{
		$sql = "INSERT INTO SemanaCardapio (codigo) VALUES (null)";

		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		return $p_sql->execute();
	}

	public function InserirDias (SemanaCardapio $semanaCardapio)
	{
		$dias = $semanaCardapio->getDias();
		for ($i=0; $i < count($dias); $i++) { 
			$diaAlmocoDao = new DiaAlmocoDao;
			$diaAlmocoDao->Inserir($dias[$i], $semanaCardapio->getCodigo());
		}
	}
}

?>