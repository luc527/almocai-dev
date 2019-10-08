<?php

require_once 'autoload.php';

/**
 * Classe com método estáticos de base para os métodos de acesso a dados dos DAOs
 */
class QueryBuilder {

	/**
	 * Query base para SELECT
	 * 
	 * @param string $sql o código sql a ser executado
	 * @param array $paramBinds array com parâmetros a ser colocados em bindParam ['nome' => 'Fulano'] -> bindParam(':nome', 'Fulano')
	 * 
	 * @return array registros num array de arrays associativos (fetchAll(PDO::FETCH_ASSOC))
	 */
	public static function selectBase (string $sql, array $paramBinds = [])
	{
		try {
			$statement = Conexao::conexao()->prepare($sql);

			$statement = self::bindParams($statement, $paramBinds);

			$statement->execute();

		} catch (PDOException $e) {
			$txt = "<b> Não foi possível consultar o BD (QueryBuilder@selectBase) </b>";
			$txt .= "<br> <b>SQL: </b> {$sql}";
			$txt .= "<br> <b>Parâmetros: </b>";
			foreach ($paramBinds as $bind => $param) {
				$txt.= "<br> <b> > {$bind}</b> => {$param}";
			}
			$txt .= "<br> <b><i>Erro: </i></b>".$e->getMessage();
			die($txt);
		}

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	

	/**
	 * Query base para update, delete e insert
	 * 
	 * @param string $sql o código sql a ser executado
	 * @param array $paramBinds array com parâmetros a ser colocados em bindParam ['nome' => 'Fulano'] -> bindParam(':nome', 'Fulano')
	 */
	public static function queryBase (string $sql, array $paramBinds = [])
	{
		try {
			$statement = Conexao::conexao()->prepare($sql);

			$statement = self::bindParams($statement, $paramBinds);

			$statement->execute();

		} catch (PDOException $e) {
			$txt = "<b> Não foi possível executar a query (QueryBuilder@queryBase) </b>";
			$txt .= "<br> <b>SQL: </b> {$sql}";
			$txt .= "<br> <b>Parâmetros: </b>";
			foreach ($paramBinds as $bind => $param) {
				$txt.= "<br> <b> > {$bind}</b> => {$param}";
			}
			$txt .= "<br> <b><i>Erro: </i></b>".$e->getMessage();
			die($txt);
		}
	}

	/**
	 * Executa bindParam para vários parâmetros em um PDOStatement
	 * 
	 * @param PDOStatement $stmt sem parâmetros
	 * @param array $paramBinds array associativo de 'parâmetro' => 'valor' (ex: ['nome' => 'Fulano', 'dataNasc' => '1990-01-01'])
	 * 
	 * @return PDOStatement com parâmetros
	 */
	public static function bindParams(PDOStatement $stmt, array $paramBinds)
	{
		foreach ($paramBinds as $param => &$bind) {
			$stmt->bindParam(":{$param}", $bind);
		}

		return $stmt;
	}
}