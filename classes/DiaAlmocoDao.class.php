<?php

require_once('autoload.php');

class DiaAlmocoDao {

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function InserirAlimentos (DiaAlmoco $diaAlmoco) {
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) { 
			AlimentoDao::Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}

	public static function InserirPresencas (DiaAlmoco $diaAlmoco) {
		$presencas = $diaAlmoco->getPresencas();

		for ($i=0; $i < count($presencas); $i++) { 
			self::DeletarPresenca($presencas[$i]->getAluno()->getCodigo(), $diaAlmoco->getCodigo());
			// Se já existe uma presença marcada pra esse dia, o sistema a deleta e insere uma nova
			// Se não existe, ele tenta deletar, mas não deleta nada (porque não existe), e simplesmente insere uma nova

			try {
				$sql = "INSERT INTO Presenca (aluno_matricula, diaAlmoco_codigo, presenca) VALUES (:aluno, :dia, :presenca)";
				
				$stmt = Conexao::conexao()->prepare($sql);
				
				$stmt->bindParam(":aluno", $aluno_mat);
				$stmt->bindParam(":dia", $dia_cod);
				$stmt->bindParam(":presenca", $pres);

				$aluno_mat = $presencas[$i]->getAluno()->getCodigo();
				$dia_cod = $diaAlmoco->getCodigo();
				$pres = $presencas[$i]->getPresenca();

				return $stmt->execute();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}

	public static function DeletarPresenca($aluno, $dia) {
		try {	
			$sql = "DELETE FROM Presenca WHERE aluno_matricula = :aluno AND diaAlmoco_codigo = :dia";
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":aluno", $aluno);
			$stmt->bindParam(":dia", $dia);
			return $stmt->execute();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula ($row) {
		// Função que recebe uma linha de um SELECT FROM DiaAlmoco e popula um objeto DiaAlmoco com as informações recebidas

		if (isset($row['diaSemana_codigo'])) {
			$diaSemana = DiaSemanaDao::SelectPorCodigo($row['diaSemana_codigo']);
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
			array_push ($dias, self::Popula($row));
		}

		return $dias;
	}

	public static function SelectPorSemana ($semana_codigo) {
		$sql = "SELECT * FROM DiaAlmoco WHERE semanaCardapio_codigo = ".$semana_codigo." ORDER BY `data`";
		$query = Conexao::conexao()->query($sql);

		$dias = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($dias, self::Popula($row));
		}

		return $dias;
	}

	public static function SelectUltimoCod () {
		$sql = "SELECT codigo FROM DiaAlmoco ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row['codigo'];
	}

	public static function SelectAlimentos ($dia) {
		
		$alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());

		for ($i=0; $i < count($alimentos); $i++) { 
			$dia->setAlimento($alimentos[$i]);
		}
		
		return $dia;
	}

	
	////////////////////////
	// FUNÇÕES DE DELETAR //	

	public static function Deletar (DiaAlmoco $dia) {
		$alimentos = $dia->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) { 
			AlimentoDao::Deletar($alimentos[$i]);
		}

		$sql = "DELETE FROM DiaAlmoco WHERE codigo = :codigo";
		$p_sql = Conexao::conexao()->prepare($sql);
		$p_sql->bindParam(":codigo", $codigo);
		$codigo = $dia->getCodigo();

		return $p_sql->execute();
	}
}

?>