<?php

// Dao = Data Acess Object = Objeto de acesso à dados

require_once "autoload.php";

class AlimentoDao {


	////////////////////////
	// FUNÇÕES DE INSERIR //

	public static function Inserir (Alimento $alimento, $diaAlmoco_codigo)	{
		$sql = "INSERT INTO Alimento (descricao, diaAlmoco_codigo, tipo_cod)
		VALUES (:descricao, :diaAlmoco_codigo, :tipo_id)";

		$pdo = Conexao::conexao();

		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(":descricao", $descricao);
		$stmt->bindParam(":diaAlmoco_codigo", $diaAlmoco_codigo);
		$stmt->bindParam(":tipo_cod", $tipo);

		$descricao = $alimento->getDescricao();
		$tipo = $alimento->getTipo()->getCodigo();

		return $stmt->execute();
	}

	///////////////////////
	// FUNÇÕES DE SELECT //

	public static function Popula ($row) {
		$alimento = new Alimento;
		$alimento->setCodigo($row['codigo']);
		$alimento->setDescricao($row['descricao']);

		$tipo = new TipoAlimento;
		$tipo->setCodigo($row['tipo_cod']);
		$alimento->setTipo($tipo);

		return $alimento;
	}

	public static function SelectPorDia ($dia_codigo) {
		$sql = "SELECT * FROM Alimento WHERE diaAlmoco_codigo = ".$dia_codigo;
		//echo $sql;

		$query = Conexao::conexao()->query($sql);

		$alimentos = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($alimentos, self::Popula($row));
		}

		return $alimentos;
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public static function Deletar (Alimento $alimento) {
		$sql = "DELETE FROM Alimento WHERE codigo = :codigo";
		$p_sql = Conexao::conexao()->prepare($sql);
		$p_sql->bindParam(":codigo", $codigo);
		$codigo = $alimento->getCodigo();

		return $p_sql->execute();
	}
}


?>
