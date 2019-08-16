<?php

require_once "autoload.php";

class SemanaCardapioDao {
	private $instance;

	public static function getInstance () {
		if (!isset(self::$instance)) {
			self::$instance = new SemanaCardapioDao;
		}
		return $instance;
	}


	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public function Inserir (SemanaCardapio $semanaCardapio) {
		$sql = "INSERT INTO SemanaCardapio (codigo) VALUES (null)";

		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		return $p_sql->execute();
	}

	public function InserirDias (SemanaCardapio $semanaCardapio) {
		$dias = $semanaCardapio->getDias();
		for ($i=0; $i < count($dias); $i++)	{ 
			$diaAlmocoDao = new DiaAlmocoDao;
			$diaAlmocoDao->Inserir($dias[$i], $semanaCardapio->getCodigo());
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public function Popula ($row) {
		$semana = new SemanaCardapio;
		$semana->setCodigo($row['codigo']);

		return $semana;
	}

	public function SelectPorCodigo ($codigo) {
		$sql = "SELECT * FROM SemanaCardapio WHERE codigo = ".$codigo." ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);
		$row = $query->fetch(PDO::FETCH_ASSOC);

		return $this->Popula($row);
	}

	public function SelectTodos () {
		$sql = "SELECT * FROM SemanaCardapio ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($semanas, $this->Popula($row));
		}

		return $semanas;
	}

	public function SelectTodosComDias () {
		$semanas = $this->SelectTodos();

		$diaDao = new DiaAlmocoDao;
		for ($i=0; $i < count($semanas); $i++) { 
			$dias = $diaDao->SelectPorSemana($semanas[$i]->getCodigo());

			if (isset($dias)) {
				for ($j=0; $j < count($dias); $j++)	{ 
					$semanas[$i]->setDia($dias[$j]);
				}					
			}
		}

		return $semanas;
	}

	public function SelectUltimoCod () {
		$sql = "SELECT codigo FROM SemanaCardapio ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row['codigo'];
	}


	public static function GerarSelectHTML () {
		return Funcoes::GerarSelectHTML("SemanaCardapio", "semanaCardapio_codigo", 0, "codigo", "codigo");
	}

}

?>