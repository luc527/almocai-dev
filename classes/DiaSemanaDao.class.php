<?php

require_once "autoload.php";

class DiaSemanaDao {
	private $instance;

	public static function getInstance () {
		if (!isset(self::$instance)) {
			self::$instance = new SemanaCardapioDao;
		}
		return $instance;
	}

	
	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public function Popula ($row) {
		$diaSemana = new DiaSemana;
		$diaSemana->setCodigo($row['codigo']);
		$diaSemana->setDescricao($row['descricao']);

		return $diaSemana;
	}

	public function SelectTodos () {
		$sql = "SELECT * FROM DiaSemana ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);

		$diasSemana = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($diasSemana, $this->Popula($row));
		}

		return $diasSemana;
	}

	public function SelectPorCodigo ($codigo) {
		$sql = "SELECT * FROM DiaSemana WHERE codigo = ".$codigo." ORDER BY codigo";
		$query = Conexao::conexao()->query($sql);
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $this->Popula($row);
	}

	public static function GerarSelectHTML () {
		return Funcoes::GerarSelectHTML("DiaSemana", "diaSemana_codigo", 0, "codigo", "descricao");
	}
}

?>