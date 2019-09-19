<?php

require_once "autoload.php";

class Funcoes
{
	private $instance;

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

	public static function DataUserParaBD ($data) {
		// Recebe uma data no formato que o usuário digita ("DD/MM/AAAA")
		// e retorna uma data que o BD entende ("AAAA-MM-DD")

		$data = str_replace('/', '-', $data);
		return date('Y-m-d', strtotime($data));
	}

	public static function DataBDParaUser ($data) {
		return date('d/m/Y', strtotime($data));
	}
}

?>
