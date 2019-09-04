<?php
	require_once "autoload.php";

	class AlunoDao {
		public static function Inserir(Usuario $usuario) {
			try {
				$sql = "INSERT INTO Usuario (matricula, senha, nome, tipo_cod)
					VALUES(:matricula, :senha, :nome, :tipo_cod)";

				$stmt = Conexao::conexao()->prepare($sql);

				$stmt->bindValue(":matricula", $codigo);
				$stmt->bindValue(":senha", $senha);
				$stmt->bindValue(":nome", $nome;
				$stmt->bindValue(":tipo_cod", $tipo);

				$codigo = $usuario->getCodigo();
				$senha = $usuario->getSenha();
				$nome = $usuario->getNome();
				$tipo = $usuario->getTipo()->getCodigo();

				return $stmt->execute();
			} catch (Exception $e){
				print "Erro ". $e->
				getCode() . " Mensagem: " . $e->getMessage();
			}
		}

		public static function Popula ($row) {
			$usuario = new Usuario;
			$usuario->setCodigo( $row['matricula'] );
			$usuario->setSenha( $row['senha'] );
			$usuario->setNome( $row['nome'] );

			$tipo = new Tipo;
			$tipo->setCodigo( $row['tipo_cod'] );
			$usuario->setTipo($tipo);

			return $usuario;
		}

		public static function Select ($criterio, $pesquisa) {
			try {
				switch ($criterio) {
					case 'nome':
						$sql = "SELECT * FROM Usuario WHERE $criterio like '%$pesquisa%'";
						break;

					case 'matricula':
					case 'tipo_cod':
						$sql = "SELECT * FROM Usuario WHERE $criterio = '$pesquisa'";
						break;

					case 'todos':
						$sql = "SELECT * FROM Usuario";
						break;
				}

				$query = Conexao::conexao()->query($sql);

				$usuarios = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					array_push($usuarios, self::Popula($row));
				}

				return $usuarios;
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public static function SelectPorMatricula ($matricula) {
			// Retorna o objeto luno em vez de um array com um só objeto, que seria o resultado do Select ()
			try {
				$usuarios = self::Select('matricula', $matricula);
				return $usuarios[0];
			} catch (Exception $e) {

			}
		}

		public static function Login (Usuario $usuario) {

			$login = array(); // Array de informações que essa função retornará
			$login[0] = ''; // Mantem o erro que ocorreu ou a ação a ser tomada
			$login[1] = ''; // Mantem, caso o login será feito, a matrícula do aluno
			$login[2] = ''; // Mantem, caso o login será feito, o nome do aluno

			try {
				$matricula = $aluno->getCodigo();
				if ( self::MatriculaCadastrada( $matricula ) ) {

					$senha = $aluno->getSenha();
					if ( self::SenhaCorreta($matricula, $senha) ) {
						$usuario = self::SelectPorMatricula($matricula);

						$login[0] = 'fazer_login';
						$login[1] = $matricula;
						$login[2] = $usuario->getNome();
						$login[3] = $usuario->getTipo()->getCodigo(); 
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
				$sql = "SELECT * FROM Usuario WHERE matricula = $matricula";
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
				$sql = "SELECT * FROM Usuario WHERE matricula = $matricula";
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
