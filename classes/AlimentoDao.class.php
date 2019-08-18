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

	public function Inserir (Alimento $alimento, $diaAlmoco_codigo)	{
		$sql = "INSERT INTO Alimento (descricao, diaAlmoco_codigo) VALUES (:descricao, :diaAlmoco_codigo)";
		
		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":descricao", $descricao);
		$p_sql->bindParam(":diaAlmoco_codigo", $diaAlmoco_codigo);

		$descricao = $alimento->getDescricao();

		return $p_sql->execute();
	}




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
}


?>
