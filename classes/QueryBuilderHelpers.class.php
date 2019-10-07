<?php

require_once 'autoload.php';

/**
 * Classe com métodos helpers para a classe QueryBuilder (formatadores, parsers etc.)
 */
class QueryBuilderHelpers {

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
	
	/**
	 * Formata colunas do comando select ['nome', 'sobrenome', 'idade'] -> 'nome, sobrenome, idade';
	 * 
	 * @param array $columns
	 * 
	 * @return string colunas formatadas
	 */
	public static function formatSelectCols ($columns)
	{
		$txt = "";
		for ($i=0; $i < count($columns); $i++) {
			$txt .= $columns[$i];
			if ($i != count($columns)-1) {
				$txt .= ", ";
			}
			return $txt;
		}
	}

	/**
	 * Formata as condições para concatenação num comando SELECT
	 * 
	 * @param array $conds condições -- obs: já passadas pelo parseCondition
	 * 
	 * @return string condições no formato correto
	 */
	public static function formatConditions ($conds) {
		
		$txt = "";
		for ($i=0; $i < count($conds); $i++) {
			$txt .= $i == 0 ? " WHERE " : " AND ";

			$txt .= " {$conds[$i]['column']} ";
			$txt .= " {$conds[$i]['operator']} ";
			$txt .= " :{$conds[$i]['column']} ";
		}
		return $txt;
	}

	/**
	 * Função wrapper, executa parseCondition para várias condições
	 */
	public static function parseConditions ($conditions)
	{
		foreach ($conditions as &$cond) {
			$cond = self::parseCondition($cond);
		}

		return $conditions;
	}

	/**
	 * Usado para select. Recebe uma condição escrita "à mão" ("id = 10"), separa e marca os elementos dela
	 * Essa separação e marcação é feita para bindParam em outras partes do código, e tb para essas outras partes serem legíveis
	 * 
	 * @param string $condition condição no formato "id = 22", "nome = %fulano%" (obs: não precisa de aspas em volta de strings)
	 * 
	 * @return array condição com elementos separados e marcados
	 */
	public static function parseCondition ($condition)
	{		
		$condition = trim(str_replace(['"', "'"], "", $condition));
		$condition = explode(" ", $condition);

		return [
			'column' => $condition[0],
			'operator' => $condition[1],
			'value' => $condition[2]
		];
	}

	/**
	 * Usado no comando select. Gera array $paramBinds (um dos argumentos dos métodos queryBase e selectBase) a partir das condições
	 * 
	 * @param array $conds condições (no formato do parseCondition)
	 * 
	 * @return array $paramBinds valores em array associativo para bindParam(":param", $bind)
	 */
	public static function makeParamBindsArray ($conds) 
	{
		$paramBinds = [];
		
		foreach ($conds as $cond) {
			$paramBinds[$cond['column']] = $cond['value'];
		}

		return $paramBinds;
	}

	/**
	 * Formata as colunas do comando INSERT
	 * 
	 * @param array $columnValues array associativo ['nome' = 'Fulano', 'sobrenome' = 'da Silva', etc]
	 * @param string $param colocar : na frente da coluna ou não (para os parâmetros)
	 * 
	 * @return string ex: "nome, sobrenome, idade" ou ":nome, :sobrenome, :idade
	 */
	public static function formatInsertColumns (array $columnValues, string $param = "")
	{
		$txt = "";

		$counter = 0;
		foreach($columnValues as $column => $value) {
			$counter++;
			
			$txt .= " {$param}{$column} ";

			if ($counter != count($columnValues)) {
				$txt .= ", ";
			}
		}

		return $txt;
	}

	/**
	 * Formata as colunas para o comando UPDATE.
	 * 
	 * @param array $columnValues array associativo com colunas e valores para o comando (['nome' => 'Fulano' etc])
	 * 
	 * @return string colunas formatadas para UPDATE (SET nome = :nome, alimentacao = :alimentacao, tipo = :tipo etc)
	 */
	public static function formatUpdateColumns (array $columnValues) 
	{
		$txt = "";

		$counter = 0;
		foreach ($columnValues as $column => $value) {
			$counter++;

			$txt .= $counter == 1 ? " SET " : " , ";

			$txt .= " {$column} = :{$column} ";
		}

		return $txt;
	}

}