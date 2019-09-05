<?php

// Uso: $pdo = Conexao::conexao();
// $pdo->prepare("SELECT * FROM tabela"); etc

require_once "autoload.php";

class Conexao {
	protected static $conexao;

	private function __construct ()	{
		$db_host = "localhost";
		$db_nome = "almocai";
		$db_usuario = "root";
		$db_senha = "";
		$db_driver = "mysql";

		try	{
			self::$conexao = new PDO("$db_driver:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha);
			self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$conexao->exec("SET NAMES utf8");
		}
		catch (PDOException $e) {
			die("Erro de conexão: ".$e->getMessage());
		}
	}

	public static function conexao () {
		if (empty(self::$conexao)) {
			new self;
		}
		return self::$conexao;
	}
}

?>
