<?php
require_once("AlimentoDao.class.php");
require_once("Conexao.class.php");
require_once("DiaAlmoco.class.php");

class DiaAlmocoDao {

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function InserirAlimentos (DiaAlmoco $diaAlmoco) {
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) {
			AlimentoDao::Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}

	public static function InserirPresencas (DiaAlmoco $diaAlmoco) {
		$presencas = $diaAlmoco->getPresencas();

		for ($i=0; $i < count($presencas); $i++) {
			self::DeletarPresenca($presencas[$i]->getAluno()->getCodigo(), $diaAlmoco->getCodigo());
			// Se já existe uma presença marcada pra esse dia, o sistema a deleta e insere uma nova
			// Se não existe, ele tenta deletar, mas não deleta nada (porque não existe), e simplesmente insere uma nova

			try {
				$sql = "INSERT INTO Presenca (usuario_matricula, diaAlmoco_codigo, presenca) VALUES (:usuario, :dia, :presenca)";

				$stmt = Conexao::conexao()->prepare($sql);

				$stmt->bindParam(":usuario", $usuario_mat);
				$stmt->bindParam(":dia", $dia_cod);
				$stmt->bindParam(":presenca", $pres);

				$usuario_mat = $presencas[$i]->getAluno()->getCodigo();
				$dia_cod = $diaAlmoco->getCodigo();
				$pres = $presencas[$i]->getPresenca();

				return $stmt->execute();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}

	public static function DeletarPresenca($usuario, $dia) {
		try {
			$sql = "DELETE FROM Presenca WHERE usuario_matricula = :usuario AND diaAlmoco_codigo = :dia";
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":usuario", $usuario);
			$stmt->bindParam(":dia", $dia);
			return $stmt->execute();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula ($row) {
		$dia = new DiaAlmoco;
		$dia->setCodigo($row['codigo']);
		$dia->setData($row['data']);
		$dia->setDiaSemana($row['diaSemana']);

		return $dia;
	}

	public static function SelectTodos () {
		$sql = "SELECT * FROM DiaAlmoco ORDER BY codigo";
		$query = Conexao::conexao()->query($sql);

		$dias = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($dias, self::Popula($row));
		}

		return $dias;
	}

	public static function SelectPorSemana ($semana_codigo) {
		$sql = "SELECT * FROM DiaAlmoco WHERE semanaCardapio_codigo = ".$semana_codigo." ORDER BY `data`";
		$query = Conexao::conexao()->query($sql);

		$dias = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($dias, self::Popula($row));
		}

		return $dias;
	}

	public static function SelectUltimoCod () {
		$sql = "SELECT codigo FROM DiaAlmoco ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);

		return $row['codigo'];
	}

	public static function SelectAlimentos ($dia) {

		$alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());

		for ($i=0; $i < count($alimentos); $i++) {
			$dia->setAlimento($alimentos[$i]);
		}

		return $dia;
	}

	public static function SelectPorData (string $data) {
		$sql = "SELECT * FROM DiaAlmoco WHERE `data` = '$data'";
		try {
			$bd = Conexao::getInstance();
			$query = $bd->query($sql);
			$row = $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo "<b>Erro (DiaAlmocoDao::SelectPorData): </b>".$e->getMessage();
		}
		return self::Popula($row);
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public static function Deletar (DiaAlmoco $dia) {
		$alimentos = $dia->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) {
			AlimentoDao::Deletar($alimentos[$i]);
		}

		$sql = "DELETE FROM DiaAlmoco WHERE codigo = :codigo";
		$stmt = Conexao::conexao()->prepare($sql);
		$stmt->bindParam(":codigo", $codigo);
		$codigo = $dia->getCodigo();

		return $stmt->execute();
	}
}

?>
