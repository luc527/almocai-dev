<?php
require_once("autoload.php");

class SemanaCardapioDao {

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function Inserir (SemanaCardapio $semanaCardapio) {
		$sql = "INSERT INTO SemanaCardapio (data_inicio) VALUES (:data_inicio)";

		$pdo = Conexao::conexao();

		$p_sql = $pdo->prepare($sql);

		$p_sql->bindParam(":data_inicio", $data_inicio);
		$data_inicio = $semanaCardapio->getData_inicio();

		return $p_sql->execute();
	}

	/* O usuário nunca irá inserir dias no banco de dados. O banco de dados tem um gatilho 'cria_dias_semana' que, após uma semana ser criada (INSERT INTO SemanaCardapio), são criados automaticamente 5 dias (Segunda, Terça, ...) para a semana. Ver no almocai.sql para entender como funciona

	public function InserirDias (SemanaCardapio $semanaCardapio) {
		$dias = $semanaCardapio->getDias();
		for ($i=0; $i < count($dias); $i++)	{
			$diaAlmocoDao = new DiaAlmocoDao;
			$diaAlmocoDao->Inserir($dias[$i], $semanaCardapio->getCodigo());
		}
	}
	*/


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula ($row) {
		$semana = new SemanaCardapio;
		$semana->setCodigo($row['codigo']);
		$semana->setData_inicio($row['data_inicio']);

		return $semana;
	}

	public static function SelectPorCriterio ($pesquisa, $criterio) {
		if ($criterio == 'data_inicio') {
			$pesquisa = Funcoes::DataUserParaBD($pesquisa);
		}

		$sql = "SELECT * FROM SemanaCardapio WHERE ".$criterio." = '".$pesquisa."'";
		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($semanas, self::Popula($row));
		}

		return $semanas;
	}

	public static function SelectPorCodigo ($codigo) {
		$semanas = self::SelectPorCriterio ($codigo, 'codigo');
		return $semanas[0];
	}

	public static function SelectTodos () {
		$sql = "SELECT * FROM SemanaCardapio ORDER BY codigo";

		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($semanas, self::Popula($row));
		}

		return $semanas;
	}

	public static function SelectDias ($semana) {
		// Recebe um objeto semana sem dias
		// Retorna um objeto semana com os dias devidos, conforme seu código

		$dias = DiaAlmocoDao::SelectPorSemana($semana->getCodigo());

		if (isset($dias)) {
			for ($i=0; $i < count($dias); $i++)	{
				$semana->setDia($dias[$i]);
			}
		}

		return $semana;
	}

	public static function SelectUltimoCod () {
		$sql = "SELECT codigo FROM SemanaCardapio ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);

		return $row['codigo'];
	}

	public static function SelectPorDia ($data) {
		$sql = "SELECT semanaCardapio_codigo FROM diaAlmoco WHERE `data` = '$data'";
		try {
			$bd = Conexao::conexao();
			$query = $bd->query($sql);
			$row = $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo "Erro (SemanaCardapioDao::SelectPorDia): ".$e->getMessage();
		}
		return self::SelectPorCodigo( $row['semanaCardapio_codigo'] );
	}


	public static function GerarSelectHTML () {
		return Funcoes::GerarSelectHTML("SemanaCardapio", "semanaCardapio_codigo", 0, "codigo", "codigo");
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public static function Deletar (SemanaCardapio $semana) {
		$dias = $semana->getDias();
		for ($i=0; $i < count($dias); $i++) {
			DiaAlmocoDao::Deletar($dias[$i]);
		}

		$sql = "DELETE FROM SemanaCardapio WHERE codigo = :codigo";
		$p_sql = Conexao::conexao()->prepare($sql);
		$p_sql->bindParam(":codigo", $codigo);
		$codigo = $semana->getCodigo();

		return $p_sql->execute();
	}


	// //////////////////// //
	// OUTROS MÉTODOS ÚTEIS //
	// //////////////////// //

	/**
	 * Verifica se uma semana está cadastrada no BD a partir da data informada
	 * @param string $data data
	 * @return bool true se existe, false se não
	 */
	public static function SemanaExiste($data) {
		$data = Funcoes::CorrigeData($data);
		$sql = "SELECT * FROM DiaAlmoco WHERE `data` = '$data'";
		try {
			$bd = Conexao::conexao();
			$query = $bd->query($sql);
			$row = $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo "<b>Erro (Funcoes::SemanaExiste): </b>" . $e->getMessage();
		}
		if ($row != false) { // qdo a consulta não tem resultados $row = false
			return true;
		} else {
			return false;
		}
	}
}

?>
