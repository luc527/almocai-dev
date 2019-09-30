<?php

// Dao = Data Acess Object = Objeto de acesso à dados

require_once "autoload.php";

class AlimentoDao {


	////////////////////////
	// FUNÇÕES DE INSERIR //

	public static function Inserir (Alimento $alimento, $diaAlmoco_codigo)	{
		$sql = "INSERT INTO Alimento (descricao, diaAlmoco_codigo, tipo)
		VALUES (:descricao, :diaAlmoco_codigo, :tipo)";

		$pdo = Conexao::conexao();

		$stmt = $pdo->prepare($sql);

		$descricao = $alimento->getDescricao();
		$tipo = $alimento->getTipo();
		
		$stmt->bindParam(":descricao", $descricao);
		$stmt->bindParam(":diaAlmoco_codigo", $diaAlmoco_codigo);
		$stmt->bindParam(":tipo", $tipo);

		return $stmt->execute();
	}

	/**
	 * Insere um alimento em todos os dias em uma semana
	 */
	public static function InserirEmSemana (Alimento $alimento, $semana_codigo) {
		$sql = "SELECT codigo FROM DiaAlmoco WHERE semanaCardapio_codigo = $semana_codigo";
		try {
			$query = Conexao::conexao()->query($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				self::Inserir($alimento, $row['codigo']);
			}
		} catch (PDOException $e) { echo "<b>Erro (AlimentoDao::InserirEmSemana): </b>".$e->getMessage(); }
	}

	///////////////////////
	// FUNÇÕES DE SELECT //

	public static function Popula ($row) {
		$alimento = new Alimento;
		$alimento->setCodigo($row['codigo']);
		$alimento->setDescricao($row['descricao']);
		$alimento->setTipo($row['tipo']);

		return $alimento;
	}

	public static function SelectPorDia ($dia_codigo) {
		$sql = "SELECT * FROM Alimento WHERE diaAlmoco_codigo = ".$dia_codigo;

		$query = Conexao::conexao()->query($sql);

		$alimentos = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($alimentos, self::Popula($row));
		}

		return $alimentos;
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public static function Deletar ($alimento_cod) {
		$sql = "DELETE FROM Alimento WHERE codigo = :alimento_cod";
		try {
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":codigo", $alimento_cod);
		} catch (PDOException $e) { echo "<b>Erro (AlimentoDao::Deletar): </b>".$e->getMessages(); }

		return $stmt->execute();
	}

	/**
	 * Deleta todos os alimentos de um dia
	 */
	public static function DeletarPorDia ($dia_cod) {
		$sql = "DELETE FROM Alimento WHERE diaAlmoco_codigo = :dia_cod";
		try {
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":dia_cod", $dia_cod);
		} catch (PDOException $e) { echo "<b>Erro (AlimentoDao::DeletarPorDia): </b>".$e->getMessages(); }
		return $stmt->execute();
	}

	/**
	 * Deleta todos os alimentos de todos os dias de uma semana
	 */
	public static function DeletarPorSemana ($semana_cod) {
		$sql = "SELECT codigo FROM DiaAlmoco WHERE semanaCardapio_codigo = $semana_cod";
		try {
			$query = Conexao::conexao()->query($sql);
			while ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				self::DeletarPorDia($row['codigo']);
			}
		} catch (PDOException $e) { echo "<b>Erro (AlimentoDao::DeletePorSemana): </b>".$e->getMessage(); }
	}
}


?>
