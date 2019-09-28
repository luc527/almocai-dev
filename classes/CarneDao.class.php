<?php
require_once("Conexao.class.php");
require_once("Carne.class.php");

class CarneDao {
	public static function Popula ($row) {
		$carne = new Carne;
		$carne->setCodigo($row['codigo']);
		$carne->setDescricao($row['descricao']);
		return $carne;
	}

	public static function SelectTodas () {
		$sql = "SELECT * FROM Carne";
		try {
			$bd = Conexao::conexao();
			$query = $bd->query($sql);
			$carnes = array();
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				array_push($carnes, self::Popula($row));
			}
		} catch (PDOException $e) {
			echo "<b>Erro (CarneDao::SelectTodos): </b>".$e->getMessage();
		}
		return $carnes;
	}
}

?>