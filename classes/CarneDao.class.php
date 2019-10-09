<?php
require_once("Conexao.class.php");
require_once("StatementBuilder.class.php");
require_once("Carne.class.php");

class CarneDao {
	public static function Popula ($row)
	{
		$carne = new Carne;
		$carne->setCodigo($row['codigo']);
		$carne->setDescricao($row['descricao']);
		return $carne;
	}

	public static function PopulaVarias ($rows)
	{
		foreach ($rows as $row) {
			$carnes[] = self::Popula($row);
		}
		return $carnes;
	}

	public static function SelectTodas ()
	{
		return self::PopulaVarias(
			StatementBuilder::select("SELECT * FROM Carne")
		);
	}
}

?>