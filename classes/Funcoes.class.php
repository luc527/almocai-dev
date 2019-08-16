<?php

require_once "autoload.php";

class Funcoes
{
	private $instance;

	public static function getInstance () {
		if (!isset(self::$instance)) {
			self::$instance = new SemanaCardapioDao;
		}
		return $instance;
	}

	public static function GerarSelectHTML ($tabela, $selectName, $selecionado, $value, $texto) {
		$txt = '';

		$sql = "SELECT * FROM $tabela";

		$query = Conexao::conexao()->query($sql);

		$txt .= "<select name='".$selectName."'>";
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($selecionado = $row["$value"]) {
				$selected = "selected";
			}
			else {
				$selected = "";
			}

			$txt .= "<option value='".$row["$value"]."' ".$selected.">".$row["$texto"]."</option>";
		}
		$txt .= "</select>";

		return $txt;
	}
}

?>