<?php

class IntoleranciaDao
{

	public static function Popula($row)
	{
		$intol = new Intolerancia;
		$intol->setCodigo($row['codigo']);
		$intol->setDescricao($row['descricao']);
		return $intol;
	}


	public static function PopulaVarias($rows)
	{
		$intols = [];
		foreach($rows as $row) {
			$intols[] = self::Popula($row);
		}
		return $intols;
	}

	public static function SelectPorCodigo($codigo)
	{
		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM Intolerancia WHERE codigo = :codigo",
				['codigo' => $codigo]
			)[0]
		);
	}

	/**
	 * Seleciona todas as intolerâncias do BD em objetos
	 * 
	 * @return array de objetos IntoleranciaUsuario
	 */
	public static function SelectTodas()
	{
		$sql = "SELECT * FROM Intolerancia";
		return self::PopulaVarias(	
			StatementBuilder::select($sql)
		);
	}
}