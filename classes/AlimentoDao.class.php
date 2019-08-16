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
		$p_sql->bindParam(":descricao", $diaAlmoco_codigo);

		$descricao = $alimento->getDescricao();

		return $p_sql->execute();
	}
}


?>
