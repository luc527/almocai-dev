<?php

require_once('autoload.php');

class DiaAlmocoDao {

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function InserirAlimentos (DiaAlmoco $diaAlmoco) {
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) { 
			$alimentoDao = new AlimentoDao;
			$alimentoDao->Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}

	public static function InserirPresencas (DiaAlmoco $diaAlmoco) {
		$presencas = $diaAlmoco->getPresencas();

		for ($i=0; $i < count($presencas); $i++) { 
			try {
				$sql = "INSERT INTO Presenca (aluno_matricula, dia_id) VALUES (:aluno, :dia)";
				
				$stmt = Conexao::getInstance->prepare($sql);
				
				$stmt->bindParam(":aluno", $aluno_mat);
				$stmt->bindParam(":dia", $dia_cod);

				$aluno_mat = $presencas[$i]->getAluno()->getCodigo();
				$dia_cod = $diaAlmoco->getCodigo();

				return $stmt->execute();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula ($row) {
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

	public static function SelectTodos () {
		$sql = "SELECT * FROM DiaAlmoco ORDER BY codigo";
		$query = Conexao::conexao()->query($sql);

		$dias = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($dias, $this->Popula($row));
		}

		return $dias;
	}

	public static function SelectPorSemana ($semana_codigo) {
		$sql = "SELECT * FROM DiaAlmoco WHERE semanaCardapio_codigo = ".$semana_codigo." ORDER BY `data`";
		$query = Conexao::conexao()->query($sql);

		$dias = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($dias, $this->Popula($row));
		}

		return $dias;
	}

	public static function SelectUltimoCod () {
		$sql = "SELECT codigo FROM DiaAlmoco ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row['codigo'];
	}

	public static function SelectAlimentos ($dias) {
		//print_r ($dias[0]);
		$alimentoDao = new AlimentoDao;

		for ($i=0; $i < count($dias); $i++) {
			$alimentos = $alimentoDao->SelectPorDia($dias[$i]->getCodigo());

			for ($j=0; $j < count($alimentos); $j++) { 
				$dias[$i]->setAlimento($alimentos[$j]);
			}
		}

		return $dias;
	}

	
	////////////////////////
	// FUNÇÕES DE DELETAR //	

	public static function Deletar (DiaAlmoco $dia) {
		$alimentos = $dia->getAlimentos();
		$alimentoDao = new AlimentoDao;
		for ($i=0; $i < count($alimentos); $i++) { 
			$alimentoDao->Deletar($alimentos[$i]);
		}

		$sql = "DELETE FROM DiaAlmoco WHERE codigo = :codigo";
		$p_sql = Conexao::conexao()->prepare($sql);
		$p_sql->bindParam(":codigo", $codigo);
		$codigo = $dia->getCodigo();

		return $p_sql->execute();
	}
}

?>