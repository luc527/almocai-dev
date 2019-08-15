<?php

class DiaAlmocoDao
{
	private $instance;

	public static function getInstance ()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new DiaAlmocoDao;
		}
		return $instance;
	}

	public function Inserir (DiaAlmoco $diaAlmoco, $semanaCardapio_codigo)
	{
		$sql = "INSERT INTO DiaAlmoco (data, semanaCardapio_codigo) VALUES (:data, :semanaCardapio_codigo)";
		
		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":data", $data);
		$p_sql->bindParam(":semanaCardapio_codigo", $semanaCardapio_codigo);

		$data = $diaAlmoco->getData();

		return $p_sql->execute();
	}

	public function InserirAlimentos (DiaAlmoco $diaAlmoco)
	{
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++)
		{ 
			$alimentoDao = new AlimentoDao;
			$alimentoDao->Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}
}

?>