<?php

// Uso: $pdo = Conexao::conexao();
// $pdo->prepare("SELECT * FROM tabela"); etc

require_once "autoload.php";

class Conexao 
{
	protected static $instance;

	private function __construct ()
	{
		$db_host = "localhost";
		$db_nome = "almocai";
		$db_usuario = "root";
		$db_senha = "";
		$db_driver = "mysql";

		try
		{
			self::$instance = new PDO("$db_driver:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha);
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$instance->exec("SET NAMES utf8");
		} catch (PDOException $e)
		{
			die("Erro de conexão: ".$e->getMessage());
		}
	}

	public static function conexao ()
	{
		if (!self::$instance)
		{
			new Conexao;
		}

		return self::$instance;
	}
}

?>