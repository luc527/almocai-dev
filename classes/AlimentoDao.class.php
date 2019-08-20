<?php

// Dao = Data Acess Object = Objeto de acesso à dados

require_once "autoload.php";

class AlimentoDao {
	public static $instance;

	public static function getInstance () {
		if (!isset(self::$instance)) {
			self::$instance = new AlimentoDao;
		}
		return $instance;
	}

	
	////////////////////////
	// FUNÇÕES DE INSERIR //

	public function Inserir (Alimento $alimento, $diaAlmoco_codigo)	{
		$sql = "INSERT INTO Alimento (descricao, diaAlmoco_codigo) VALUES (:descricao, :diaAlmoco_codigo)";
		
		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":descricao", $descricao);
		$p_sql->bindParam(":diaAlmoco_codigo", $diaAlmoco_codigo);

		$descricao = $alimento->getDescricao();

		return $p_sql->execute();
	}

	///////////////////////
	// FUNÇÕES DE SELECT //

	public function Popula ($row) {
		$alimento = new Alimento;
		$alimento->setCodigo($row['codigo']);
		$alimento->setDescricao($row['descricao']);

		return $alimento;
	}

	public function SelectPorDia ($dia_codigo) {
		$sql = "SELECT * FROM alimento WHERE diaAlmoco_codigo = ".$dia_codigo;
		//echo $sql;

		$query = Conexao::conexao()->query($sql);

		$alimentos = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($alimentos, $this->Popula($row));
		}

		return $alimentos;
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public function Deletar (Alimento $alimento) {
		$sql = "DELETE FROM Alimento WHERE codigo = :codigo";
		$p_sql = Conexao::conexao()->prepare($sql);
		$p_sql->bindParam(":codigo", $codigo);
		$codigo = $alimento->getCodigo();

		return $p_sql->execute();
	}
}


?>
