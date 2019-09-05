<?php
	require_once "autoload.php";

	class UsuarioDao {
		public static function Inserir(Usuario $usuario) {
			try {
				$sql = "INSERT INTO Usuario (matricula, senha, nome, tipo_cod)
					VALUES(:matricula, :senha, :nome, :tipo_cod)";

				$stmt = Conexao::conexao()->prepare($sql);

				$stmt->bindValue(":matricula", $codigo);
				$stmt->bindValue(":senha", $senha);
				$stmt->bindValue(":nome", $nome);
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

			$login_info = array(); // Array de informações que essa função retornará
			$login_info[0] = ''; // Mantem o erro que ocorreu ou a ação a ser tomada
			$login_info[1] = ''; // Mantem, caso o login será feito, a matrícula do aluno
			$login_info[2] = ''; // Mantem, caso o login será feito, o nome do aluno

			try {
				$matricula = $usuario->getCodigo();
				if ( self::MatriculaCadastrada( $matricula ) ) {

					$senha = $usuario->getSenha();
					if ( self::SenhaCorreta($matricula, $senha) ) {
						$usuario = self::SelectPorMatricula($matricula);

						$login_info[0] = 'fazer_login';
						$login_info[1] = $matricula;
						$login_info[2] = $usuario->getNome();
						$login_info[3] = $usuario->getTipo()->getCodigo();
					} else {
						$login_info[0] = 'senha_incorreta';
					}

				} else {
					$login_info[0] = 'matricula_nao_existe';
				}

				return $login_info;
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

		public static function Login_adm(Usuario $usuario) {
			$matricula = $usuario->getCodigo();
			$senha = $usuario->getSenha();
			$tipo = 1;

			$sql = "SELECT * FROM Usuario
			WHERE `matricula` = '$matricula'
			AND `senha` = '$senha'
			AND `tipo_cod` = '$tipo'";

			try { $query = Conexao::conexao()->query($sql);
			} catch (PDOException $e) { echo $e->getMessage(); }

			$row = $query->fetch(PDO::FETCH_ASSOC);

			$login_info = array();
			/** $login_info
			 * Informações que a função retornará:
			 * [0] -> se o login deverá ser efetuado OU, caso contrário, qual foi o erro
			 * [1] -> matrícula do usuário
			 * [2] -> nome do usuário
			 * [3] -> tipo do usuário (adm)
			 * [1 - 3] só serão preenchidas caso o login será feito
			 * serão armazenadas em $_SESSION
			 */

			if ($row) {
				$login_info[0] = "fazer_login";
				$login_info[1] = $row['matricula'];
				$login_info[2] = $row['nome'];
				$login_info[3] = 1; //tipo (adm)
			} else {
				$login_info[0] = 'infos_incorretas';
			}
			return $login_info;
		}
	}
?>
