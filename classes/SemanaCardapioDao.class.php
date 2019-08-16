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
		for ($i=0; $i < count($dias); $i++)
		{ 
			$diaAlmocoDao = new DiaAlmocoDao;
			$diaAlmocoDao->Inserir($dias[$i], $semanaCardapio->getCodigo());
		}
	}

	public function SelectPorCodigo ($codigo)
	{
		$sql = "SELECT * FROM SemanaCardapio WHERE codigo = ".$codigo." ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);

		return $this->PopulaSemanaCardapio($query->fetch(PDO::FETCH_ASSOC));
	}

	public function PopulaSemanaCardapio ($row)
	{
		$semana = new SemanaCardapio;
		$semana->setCodigo($row['codigo']);

		return $semana;
	}

	public function SelectTodos ()
	{
		$sql = "SELECT * FROM SemanaCardapio ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			array_push($semanas, $this->PopulaSemanaCardapio($row));
		}

		return $semanas;
	}

	public function SelectUltimoCod ()
	{
		$sql = "SELECT codigo FROM SemanaCardapio ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row['codigo'];
	}
}

?>