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
		$sql = "INSERT INTO SemanaCardapio (data_inicio) VALUES (:data_inicio)";

		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":data_inicio", $semanaCardapio->getData_inicio());

		return $p_sql->execute();
	}

	/* O usuário nunca irá inserir dias no banco de dados. O banco de dados tem um gatilho 'cria_dias_semana' que, após uma semana ser criada (INSERT INTO SemanaCardapio), são criados automaticamente 5 dias (Segunda, Terça, ...) para a semana. Ver no almocai.sql para entender como funciona

	public function InserirDias (SemanaCardapio $semanaCardapio) {
		$dias = $semanaCardapio->getDias();
		for ($i=0; $i < count($dias); $i++)	{ 
			$diaAlmocoDao = new DiaAlmocoDao;
			$diaAlmocoDao->Inserir($dias[$i], $semanaCardapio->getCodigo());
		}
	}
	*/


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public function Popula ($row) {
		$semana = new SemanaCardapio;
		$semana->setCodigo($row['codigo']);
		$semana->setData_inicio($row['data_inicio']);

		return $semana;
	}

	public function SelectPorCriterio ($pesquisa, $criterio) {
		if ($criterio == 'data_inicio') {
			$pesquisa = Funcoes::DataUserParaBD($pesquisa);
		}

		$sql = "SELECT * FROM SemanaCardapio WHERE ".$criterio." = '".$pesquisa."'";
		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($semanas, $this->Popula($row));
		}

		return $semanas;
	}

	public function SelectPorCodigo ($codigo) {
		return SelectPorCriterio ($codigo, 'codigo');
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

	public function SelectDias ($semanas) {
		// Função que incrementa qualquer outro select de semanas
		// O parâmetro precisa ser um array
		// Recebe array de semanas que só têm código e data_inicio
		// E retorna array de semanas com esses + os dias

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