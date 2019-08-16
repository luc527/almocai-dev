<?php

class DiaAlmocoDao {
	private $instance;

	public static function getInstance () {
		if (!isset(self::$instance)) {
			self::$instance = new DiaAlmocoDao;
		}
		return $instance;
	}


	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public function Inserir (DiaAlmoco $diaAlmoco, $semanaCardapio_codigo) {
		$sql = "INSERT INTO DiaAlmoco (`data`, diaSemana_codigo, semanaCardapio_codigo) VALUES (:data, :diaSemana_codigo, :semanaCardapio_codigo)";
		var_dump($sql);
		
		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":data", $data);
		$p_sql->bindParam(":diaSemana_codigo", $diaSemana_codigo);
		$p_sql->bindParam(":semanaCardapio_codigo", $semanaCardapio_codigo);

		$diaSemana_codigo = $diaAlmoco->getDiaSemana()->getCodigo();
		$data = $diaAlmoco->getData();

		return $p_sql->execute();
	}

	public function InserirAlimentos (DiaAlmoco $diaAlmoco) {
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) { 
			$alimentoDao = new AlimentoDao;
			$alimentoDao->Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public function Popula ($row) {
		// Função que recebe uma linha de um SELECT FROM DiaAlmoco e popula um objeto DiaAlmoco com as informações recebidas

		$diaSemDao = new DiaSemanaDao;

		if (isset($row['diaSemana_codigo'])) {
			$diaSemana = $diaSemDao->SelectPorCodigo($row['diaSemana_codigo']);
		}
		else {
			$diaSemana = new DiaSemana;
		}
		
		$dia = new DiaAlmoco;
		$dia->setCodigo($row['codigo']);
		$dia->setData($row['data']);
		$dia->setDiaSemana($diaSemana);

		return $dia;
	}

	public function SelectTodos () {
		$sql = "SELECT * FROM DiaAlmoco ORDER BY codigo";
		$query = Conexao::conexao()->query($sql);

		$dias = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($dias, $this->Popula($row));
		}

		return $dias;
	}

	public function SelectPorSemana ($semana_codigo) {
		$sql = "SELECT * FROM DiaAlmoco WHERE semanaCardapio_codigo = ".$semana_codigo." ORDER BY `data`";
		$query = Conexao::conexao()->query($sql);

		$dias = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($dias, $this->Popula($row));
		}

		return $dias;
	}

	public function SelectUltimoCod () {
		$sql = "SELECT codigo FROM DiaAlmoco ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row['codigo'];
	}
}

?>