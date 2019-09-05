<?php 
	require_once "autoload.php";

	class AlunoDao {
		public static function Inserir(Aluno $aluno) {
			try {
				$sql = "INSERT INTO Aluno (
					matricula,
					senha,
					nome)
					VALUES(
					:matricula,
					:senha,
					:nome)";
				$pdo = Conexao::conexao();
				var_dump($pdo);
				$stmt = Conexao::conexao()->prepare($sql);

				$stmt->bindValue(":matricula", $aluno->getCodigo());
				$stmt->bindValue(":senha", $aluno->getSenha());
				$stmt->bindValue(":nome", $aluno->getNome());

				return $stmt->execute();
			} catch (Exception $e){
				print "Ocorreu um erro ao tentar executar esta ação<br> ". $e->
				getCode() . " Mensagem: " . $e->getMessage();
			}
		}

		public static function Popula ($row) {
			$aluno = new Aluno;
			$aluno->setCodigo( $row['matricula'] );
			$aluno->setSenha( $row['senha'] );
			$aluno->setNome( $row['nome'] );

			return $aluno;
		}

		public static function Select ($criterio, $pesquisa) {
			try {
				switch ($criterio) {
					case 'nome':
						$sql = "SELECT * FROM Aluno WHERE $criterio like '%$pesquisa%'";
						break;
					
					case 'matricula':
						$sql = "SELECT * FROM Aluno WHERE $criterio = '$pesquisa'";
						break;

					case 'todos':
						$sql = "SELECT * FROM Aluno";
						break;
				}

				$query = Conexao::conexao()->query($sql);

				$alunos = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					array_push($alunos, self::Popula($row));
				}

				return $alunos;
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public static function SelectPorMatricula ($matricula) {
			// Retorna o objeto luno em vez de um array com um só objeto, que seria o resultado do Select ()
			try {
				$alunos = self::Select('matricula', $matricula);
				return $alunos[0];
			} catch (Exception $e) {

			}
		}

		public static function Login (Aluno $aluno) {
			
			$login = array(); // Array de informações que essa função retornará
			$login[0] = ''; // Mantem o erro que ocorreu ou a ação a ser tomada
			$login[1] = ''; // Mantem, caso o login será feito, a matrícula do aluno
			$login[2] = ''; // Mantem, caso o login será feito, o nome do aluno

			try {
				$matricula = $aluno->getCodigo();
				var_dump($matricula);
				if ( self::MatriculaCadastrada( $matricula ) ) {

					$senha = $aluno->getSenha();
					if ( self::SenhaCorreta($matricula, $senha) ) {
						$login[0] = 'fazer_login';
						$login[1] = $matricula;
						$login[2] = self::SelectPorMatricula($matricula)->getNome();
					} else {
						$login[0] = 'senha_incorreta';
					}

				} else {
					$login[0] = 'matricula_nao_existe';
				}

				return $login;
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public static function MatriculaCadastrada ($matricula) {
			// Recebe uma matricula e retorna se ela existe ou não no BD (bool)
			try {  
				$sql = "SELECT * FROM Aluno WHERE matricula = $matricula";
				$query = Conexao::conexao()->query($sql);
				$row = $query->fetch(PDO::FETCH_ASSOC);

				if (!$row) {
					return false;
				} else {
					return true;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public static function SenhaCorreta ($matricula, $senha) {
			// Rceebe uma matrícula e uma senha e retorna se a senha recebida condiz com a senha que está no BD
			try {
				$sql = "SELECT * FROM Aluno WHERE matricula = $matricula";
				$query = Conexao::conexao()->query($sql);
				$row = $query->fetch(PDO::FETCH_ASSOC);

				if ( $senha == $row['senha'] ) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}
?>