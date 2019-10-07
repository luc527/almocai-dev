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

			$statement = QueryBuilderHelpers::bindParams($statement, $paramBinds);

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
	 * Método para comando SELECT
	 * 
	 * @param string $columns as colunas a seremcolocadas na consulta (ex. "nome, sobrenome, idade")
	 * @param string $table a tabela a ser consultada (ex. "usuario")
	 * @param array $conditions no formato ["id = 2", "nome = Fulano"] 
	 * @param string $additional código adicional (ex. "ORDER BY nome LIMIT 10")
	 * 
	 * @return array array de registros do BD em array associativo -- resultado de fetchAll(PDO::FETCH_ASSOC) 
	 */
	public static function select (string $columns, string $table, array $conditions, string $additional = "")
	{
		$conditions = QueryBuilderHelpers::parseConditions($conditions);

		$form_conds = QueryBuilderHelpers::formatConditions($conditions);

		$sql = "SELECT {$columns} FROM {$table} {$form_conds} {$additional}";

		$paramBinds = QueryBuilderHelpers::makeParamBindsArray($conditions);

		return self::selectBase($sql, $paramBinds);

	}

	/**
	 * Função wrapper para consultar todos os registros de uma tabela
	 * @param string $additional ORDER BY ... LIMIT ... qualquer código a mais no final do select
	 */
	public static function selectAll (string $table, string $additional = "")
	{
		return self::select("*", $table, [], $additional);
	}

	/**
	 * Função wrapper para consultar por um id
	 * @param $pk chave primária da tabela (matricula, codigo etc.)
	 * @param string $additional ORDER BY ... LIMIT ... qualquer código a mais no final do select
	 */
	public static function selectWhereId (string $table, $pk, $id, string $additional = "")
	{
		return self::select("*", $table, ["{$pk} = {$id}"], $additional)[0];
	}

	/**
	 * Função wrapper para consultar tabela com apenas uma (1) condição (id = 22, nome = Fulano etc.)
	 * @param string $additional ORDER BY ... LIMIT ... qualquer código a mais no final do select
	 */
	public static function selectWhere (string $table, string $condition, string $additional = "")
	{
		return self::select("*", $table, [$condition], $additional);
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

			$statement = QueryBuilderHelpers::bindParams($statement, $paramBinds);

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
	 * Método para comando INSERT. Insere numa tabela os valores informados nas colunas informadas
	 * 
	 * @param string $table 
	 * @param array $columnValues array associativo com coluna e valores (['nome' => 'Fulano', 'tipo' => 'ALUNO'] etc)
	 */
	public static function insert (string $table, array $columnValues)
	{
		$columns = QueryBuilderHelpers::formatInsertColumns($columnValues);

		$param_columns = QueryBuilderHelpers::formatInsertColumns($columnValues, ":");
		
		$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$param_columns})";

		return self::queryBase($sql, $columnValues);
	}


	/**
	 * Método para comando UPDATE. Atualiza numa tabela as colunas informadas com os valores informados nos registros que atendem às condições informadas
	 * 
	 * @param string $table a tabela a ser atualizada
	 * @param array $columnValues associativo com colunas e valores (['nome' => 'fulano' etc])
	 * @param array $conditions condições para quais tabelas atualizar (SET (...) WHERE "id = 2")
	 */
	public static function update (string $table, array $columnValues, array $conditions)
	{
		$columnParams = QueryBuilderHelpers::formatUpdateColumns($columnValues);

		$conditions = QueryBuilderHelpers::parseConditions($conditions);

		$form_conds = QueryBuilderHelpers::formatConditions($conditions);

		$sql = "UPDATE {$table} {$columnParams} {$form_conds}";

		// Adiciona as condições aos parâmetros
		foreach ($conditions as $cond) {
			$columnValues[$cond['column']] = $cond['value'];
		}

		return self::queryBase($sql, $columnValues);		
	}


	/**
	 * Deleta registros numa tabela conforme dadas condições
	 * 
	 * @param string $table tabela a ter registros deletados
	 * @param array $conditions condições a definir quais registros devem ser deletados
	 */
	public static function delete (string $table, array $conditions)
	{
		$conditions = QueryBuilderHelpers::parseConditions($conditions);
		
		$form_conds = QueryBuilderHelpers::formatConditions($conditions);

		$paramBinds = QueryBuilderHelpers::makeParamBindsArray($conditions);

		$sql = "DELETE FROM {$table} {$form_conds}";

		return self::queryBase($sql, $paramBinds);
	}

	/**
	 * Função wrapper para deletar por ID (ou melhor, por chave primária)
	 * 
	 * @param string $table nome da tabela
	 * @param string $pk chave primária da tabela 
	 * @param $id id do registro a ser deletado
	 */
	public static function deleteById (string $table, string $pk, $id)
	{
		$conditions = ["{$pk} = {$id}"];

		return self::delete($table, $conditions);
	}

}