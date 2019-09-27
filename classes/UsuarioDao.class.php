<?php
	require_once("Conexao.class.php");
	require_once("Usuario.class.php");

	class UsuarioDao {


		/**
		 * INSERT
		 */

		public static function Insert(Usuario $usuario) {
			try {
				$sql = "INSERT INTO Usuario (matricula, senha, nome, tipo)
					VALUES(:matricula, :senha, :nome, :tipo)";

				$stmt = Conexao::conexao()->prepare($sql);

				$stmt->bindParam(":matricula", $codigo);
				$stmt->bindParam(":senha", $senha);
				$stmt->bindParam(":nome", $nome);
				$stmt->bindParam(":tipo", $tipo);

				$codigo = $usuario->getCodigo();
				$senha = $usuario->getSenha();
				$nome = $usuario->getNome();
				$tipo = $usuario->getTipo();

				return $stmt->execute();
			} catch (Exception $e){
				print "Erro ". $e->
				getCode() . " Mensagem: " . $e->getMessage();
			}
		}

		public static function SalvarCarnes (Usuario $usuario) {
			// Gera array com código de todas as carnes
			$todas = CarneDao::SelectTodas();
			for ($i=0; $i < count($todas); $i++) { 
				$todas[$i] = $todas[$i]->getCodigo();
			}

			$carnes = $usuario->getCarnes();

			// Verifica se cada uma das carnes está no array de carnes selecionadas pelo usuário
			// Se está, faz um insert na tabela Carne_usuario (pode ocorrer um erro se o valor já estiver registrado, mas não tem problema?)
			// Se não está, faz um delete na tabela Carne_usuario. Nesse caso, não ocorre erro quando deleta um registro que não existe
			for ($i=0; $i < count($todas); $i++) { 
				if (in_array($todas[$i], $carnes)) { 
					$sql = "INSERT INTO Carne_usuario (usuario_matricula, carne_cod) VALUES (:matricula, :carne)";
				} else {
					$sql = "DELETE FROM Carne_usuario WHERE usuario_matricula = :matricula and carne_cod = :carne";
				}
				try {
					$stmt = Conexao::conexao()->prepare($sql);
					$matricula = $usuario->getCodigo();
					$carne_cod = $todas[$i]->getCodigo();
					$stmt->bindParam(":matricula", $matricula);
					$stmt->bindParam(":carne", $carne_cod);
				} catch (PDOException $e) { echo "<b>Erro (UsuarioDao::InsertCarnes): </b>".$e->getMessage(); }
			}
		}




		/**
		 * SELECT
		 */

		public static function Popula ($row) {
			$usuario = new Usuario;
			$usuario->setCodigo( $row['matricula'] );
			$usuario->setSenha( $row['senha'] );
			$usuario->setNome( $row['nome'] );
			$usuario->setTipo( $row['tipo'] );

			return $usuario;
		}

		public static function Select ($criterio, $pesquisa) {
			try {
				switch ($criterio) {
					case 'nome':
						$sql = "SELECT * FROM Usuario WHERE $criterio like '%$pesquisa%'";
						break;

					case 'matricula':
					case 'tipo':
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

		public static function Select2($tipo, $pesquisa)
		{
			// feita especificamente para a página de gerenciamento do administrador
			// seleciona por um tipo específico + uma pesquisa que pode ser tanto o nome qto a matrícula do aluno
			$sql = "SELECT * FROM Usuario WHERE tipo = '$tipo' ";
			if ($pesquisa != 'TODOS') {
				$sql .= " AND (nome like '%$pesquisa%' OR matricula like '%$pesquisa%')";
			}
				
			try {
				$bd = Conexao::conexao();
				$query = $bd->query($sql);
				$registros = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					array_push($registros, self::Popula($row));
				}
			} catch (PDOException $e) {
				echo "Erro (UsuarioDao::Select2): " . $e->getMessage();
			}

			return $registros;
		}

		public static function SelectPorMatricula ($matricula) {
			// Retorna o objeto aluno em vez de um array com um só objeto, que seria o resultado do Select ()
			$usuarios = self::Select('matricula', $matricula);
			return $usuarios[0];
		}

		/**
		 * Recebe o código de um dia e de um usuário e retorna 0 ou 1 (coluna 'presenca' da tabela Presenca), não o objeto AlunoPresenca
		 */
		public static function SelectPresenca($dia_cod, $user_mat) {
			$sql = "SELECT * FROM Presenca WHERE diaAlmoco_codigo = $dia_cod
			AND usuario_matricula = $user_mat";
			try {
				$query = Conexao::conexao()->query($sql);
				$row = $query->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			return $row['presenca'];
		}

		/**
		 * Recebe um objeto Usuario e coloca a frequencia do BD nele
		 */
		public static function SelectFrequencia(Usuario $usuario) {
			$matricula = $usuario->getCodigo();
			$sql = "SELECT frequencia FROM Usuario where matricula = $matricula";
			try {
				$bd = Conexao::conexao();
				$query = $bd->query($sql);
				$row = $query->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo "<b>Erro (UsuarioDao::SelectFrequencia): </b>".$e->getMessage();
			}
			$frequencia = new Frequencia;
			$frequencia->setCodigo($row['frequencia']);
			$usuario->setFrequencia($frequencia);
			return $usuario;
		}
		/**
		 * Recebe um objeto Usuario e coloca a alimentação do BD nele
		 */
		public static function SelectAlimentacao(Usuario $usuario) {
			$matricula = $usuario->getCodigo();
			$sql = "SELECT alimentacao FROM Usuario where matricula = $matricula";
			try {
				$bd = Conexao::conexao();
				$query = $bd->query($sql);
				$row = $query->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo "<b>Erro (UsuarioDao::SelectAlimentacao): </b>" . $e->getMessage();
			}
			$al = new Alimentacao;
			$al->setCodigo($row['alimentacao']);
			$usuario->setAlimentacao($al);
			return $usuario;
		}
		/**
		 * Recebe um objeto Usuario e coloca as carnes do BD dele
		 */
		public static function SelectCarnes (Usuario $usuario) {
			$matricula = $usuario->getCodigo();
			$sql = "SELECT carne_cod FROM Carne_usuario WHERE usuario_matricula = $matricula";
			try {
				$bd = Conexao::conexao();
				$query = $bd->query($sql);
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$carne = new Carne;
					$carne->setCodigo($row['carne_cod']);
					$usuario->setCarne($carne);
				}
			} catch (PDOException $e) {
				echo "<b>Erro (UsuarioDao::SelectCarnes): </b>" . $e->getMessage();
			}
			return $usuario;
		}

		/**
		 * UPDATE
		 */

		public static function Update( Usuario $usuario) {
			$sql = "UPDATE Usuario SET nome = :nome, tipo = :tipo, senha = :senha,
			alimentacao = :alimentacao WHERE matricula = :matricula";
			try {
				$bd = Conexao::getInstance();
				$stmt = $bd->prepare($sql);
				
				$nome = $usuario->getNome();
				$stmt->bindParam(":nome", $nome);
				$tipo = $usuario->getTipo();
				$stmt->bindParam(":tipo", $tipo);
				$senha = $usuario->getSenha();
				$stmt->bindParam(":senha", $senha);
				$alimentacao = $usuario->getAlimentacao();
				$stmt->bindParam(":alimentacao", $alimentacao);
				$matricula = $usuario->getCodigo();
				$stmt->bindParam(":matricula", $matricula);
			} catch (PDOException $e) {
				echo "<b>Erro no preparo (UsuarioDao::Update): </b>".$e->getMessage();
			}

			return $stmt->execute();
		}

		public static function UpdateNome (Usuario $usuario) {
			// Só altera nome
			$sql = "UPDATE Usuario SET nome = :nome WHERE matricula = :matricula";
			try {
				$bd = Conexao::conexao();
				$stmt = $bd->prepare($sql);

				$nome = $usuario->getNome();
				$stmt->bindParam(":nome", $nome);
				$matricula = $usuario->getCodigo();
				$stmt->bindParam(":matricula", $matricula);

				return $stmt->execute();
			} catch (PDOException $e) {
				echo "Erro (UsuarioDao::UpdateNome): ".$e->getMessage();
			}
		}


		/**
		 * DELETE
		 */

		public static function Delete ($matricula) {
			$sql = "DELETE FROM Usuario WHERE matricula = :matricula";
			try {
				$bd = Conexao::conexao();
				$stmt = $bd->prepare($sql);
				$stmt->bindParam(":matricula", $matricula);
				return $stmt->execute();
			} catch (PDOException $e) {
				echo "Erro (UsuarioDao::Delete): ".$e->getMessage();
			}
		}


		/**
		 * LOGIN
		 */

		public static function Login(Usuario $usuario) {
			$matricula = $usuario->getCodigo();
			$senha = $usuario->getSenha();

			$sql = "SELECT * FROM Usuario
			WHERE `matricula` = '$matricula'
			AND `senha` = '$senha'";

			try { $query = Conexao::conexao()->query($sql);
			} catch (PDOException $e) { echo $e->getMessage(); }

			$row = $query->fetch(PDO::FETCH_ASSOC);

			$login_info = array();
			/** $login_info
			 * Informações que a função retornará:
			 * ['acao'] -> se o login deverá ser efetuado OU, caso contrário, qual foi o erro
			 * ['matricula'] -> matrícula do usuário
			 * ['nome'] -> nome do usuário
			 * ['tipo'] -> tipo do usuário (adm)
			 * ['matricula', 'nome' e 'tipo'] só serão preenchidas caso o login será feito
			 * serão armazenadas em $_SESSION
			 */

			if ($row) {
				$login_info['acao'] = "fazer_login";
				$login_info['matricula'] = $row['matricula'];
				$login_info['nome'] = $row['nome'];
				$login_info['tipo'] = $row['tipo']; //tipo (adm)
			} else {
				$login_info['acao'] = 'infos_incorretas';
			}
			return $login_info;
		}
	}
?>
