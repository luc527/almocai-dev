<?php
require_once("Conexao.class.php");
require_once("StatementBuilder.class.php");
require_once("Usuario.class.php");
require_once("CarneDao.class.php");

class UsuarioDao
{


	/**
	 * INSERT
	 */

	public static function Insert(Usuario $usuario)
	{
		$sql = "INSERT INTO Usuario (codigo, username, senha, nome, tipo)
		VALUES (:codigo, :username, :senha, :nome, :tipo)";

		$params = [
			'codigo' => $usuario->getCodigo(),
			'username' => $usuario->getUsername(),
			'nome' => $usuario->getNome(),
			'senha' => $usuario->getSenha(),
			'tipo' => $usuario->getTipo()
		];

		return StatementBuilder::insert($sql, $params);
	}

	/**
	 * SELECT
	 */

	public static function Popula($row)
	{		
		$usuario = new Usuario;
		$usuario->setCodigo($row['codigo']);
		$usuario->setUsername($row['username']);
		$usuario->setSenha($row['senha']);
		$usuario->setNome($row['nome']);
		$usuario->setTipo($row['tipo']);
		$usuario->setEmail($row['email']);

		return $usuario;
	}

	public static function PopulaVarios($usuarios)
	{
		$users = [];
		foreach ($usuarios as $usuario) {
			$users[] = self::Popula($usuario);
		}
		return $users;
	}

	public static function Select($criterio, $pesquisa)
	{
		try {
			switch ($criterio) {
				case 'nome':
				case 'username':
					$sql = "SELECT * FROM Usuario WHERE $criterio like '%$pesquisa%'";
					break;

				case 'username-exato':
					$sql = "SELECT * FROM Usuario WHERE username = '$pesquisa'";
					break;

				case 'codigo':
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

	/**
	 * Seleciona usuários conforme tipo e, se essas opções foram selecionadas, conforme nome e matrícula
	 */
	public static function Select2($tipo, $pesquisa)
	{
		// Por padrão pesquisará todos os usuários
		$sql = "SELECT * FROM Usuario WHERE tipo = :tipo";
		$params['tipo'] = "$tipo";

		// Se a pesquisa não foi por todos os usuários do tipo, no entanto, pesquisa por nome e matrícula
		if ($pesquisa != 'TODOS') {
			$sql .= " AND (nome like :nome OR codigo like :codigo OR username like :username)";
			$params['nome'] = "%{$pesquisa}%";			
			$params['codigo'] = "%{$pesquisa}%";
			$params['username'] = "%{$pesquisa}%";
		}

		return self::PopulaVarios(
			StatementBuilder::select($sql, $params)
		);
	}

	public static function SelectPorCodigo($codigo)
	{
		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM Usuario WHERE codigo = :codigo",
				['codigo' => $codigo]
			)[0]
		);
	}

	public static function SelectPorEmail($email)
	{
		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM Usuario WHERE email = :email",
				['email' => $email]
			)[0]
		);
	}

	/**
	 * Recebe o código de um dia e de um usuário e retorna 0 ou 1 (coluna 'presenca' da tabela Presenca), não o objeto AlunoPresenca
	 */
	public static function SelectPresenca($dia_cod, $user_cod)
	{
		$result = StatementBuilder::select(
			"SELECT * FROM Presenca WHERE diaAlmoco_codigo = :dia_cod AND usuario_cod = :user_cod",
			['dia_cod' => $dia_cod, 'user_cod' => $user_cod]
		);

		if ($result == []) {
			$presenca = "nao-selecionada";
		} else {
			$presenca = $result[0]['presenca'];			
		}

		if ($presenca == 'nao-selecionada') {
			return 'nao-selecionada';
		} else {
			return $presenca;
		}
	}

	/**
	 * Recebe um objeto Usuario e coloca a frequencia do BD nele
	 */
	public static function SelectFrequencia(Usuario $usuario)
	{
		$frequencia = new Frequencia;
		$frequencia->setCodigo(
			StatementBuilder::select(
				"SELECT frequencia FROM Usuario WHERE codigo = :codigo",
				['codigo' => $usuario->getCodigo()]
			)[0]['frequencia']
		);

		$usuario->setFrequencia($frequencia);

		return $usuario;
	}
	/**
	 * Recebe um objeto Usuario e coloca a alimentação do BD nele
	 */
	public static function SelectAlimentacao(Usuario $usuario)
	{
		$al = new Alimentacao;
		$al->setCodigo(
			StatementBuilder::select(
				"SELECT alimentacao FROM Usuario WHERE codigo = :codigo",
				['codigo' => $usuario->getCodigo()]
			)[0]['alimentacao']
		);

		$usuario->setAlimentacao($al);

		return $usuario;
	}

	/**
	 * Recebe um objeto Usuario e coloca as carnes do BD dele
	 */
	public static function SelectCarnes(Usuario $usuario)
	{
		$carne_cods = StatementBuilder::select(
			"SELECT carne_cod FROM Carne_usuario WHERE usuario_cod = :usuario_cod",
			['usuario_cod' => $usuario->getCodigo()]
		);

		foreach ($carne_cods as $cod) {
			$carne = new Carne;
			$carne->setCodigo($cod['carne_cod']);
			$usuario->setCarne($carne);
		}

		return $usuario;
	}


	public static function SelectPorIntolerancia($intol_cod)
	{
		$usuario_cod = StatementBuilder::select(
			"SELECT usuario_cod FROM Usuario_intolerancia WHERE codigo = :codigo",
			['codigo' => $intol_cod]
		)[0]['usuario_cod'];

		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM Usuario WHERE codigo = :codigo",
				['codigo' => $usuario_cod]
			)[0]
		);
	}



	/**
	 * UPDATE
	 */

	public static function Update(Usuario $usuario)
	{
		return StatementBuilder::update(
			"UPDATE Usuario SET nome = :nome, username = :username, tipo = :tipo, senha = :senha, alimentacao = :alimentacao WHERE codigo = :codigo",
			[
				'nome' => $usuario->getNome(),
				'username' => $usuario->getUsername(),
				'tipo' => $usuario->getTipo(),
				'senha' => $usuario->getSenha(),
				'alimentacao' => $usuario->getAlimentacao(),
				'codigo' => $usuario->getCodigo()
			]
		);
	}

	public static function UpdateAlimentacao(Usuario $usuario)
	{
		return StatementBuilder::update(
			"UPDATE Usuario SET alimentacao = :alimentacao WHERE codigo = :codigo",
			[
				'codigo' => $usuario->getCodigo(),
				'alimentacao' => $usuario->getAlimentacao()->getCodigo()
			]
		);
	}

	public static function UpdateFrequencia(Usuario $usuario)
	{
		return StatementBuilder::update(
			"UPDATE Usuario SET frequencia = :frequencia WHERE codigo = :codigo",
			[
				'codigo' => $usuario->getCodigo(),
				'frequencia' => $usuario->getFrequencia()->getCodigo()
			]
		);
	}

	public static function SalvarCarnes(Usuario $usuario)
	{
		// Deleta todos os registros da tabela Carne_usuario de um usuário para evitar erros no INSERT (registro duplicado)
		self::CarnesReset($usuario->getCodigo());

		// Consulta todas as carnes do BD, transforma objetos em código
		$todas = CarneDao::SelectTodas();

		// Transforma array de objetos carne em array de códigos de cada carne para verificação in_array() -- não funcionou com objetos
		$carnes = $usuario->getCarnes();
		for ($i = 0; $i < count($carnes); $i++) {
			$carnes[$i] = $carnes[$i]->getCodigo();
		}

		// Verifica se cada uma das carnes está no array de carnes selecionadas pelo usuário
		// Se está, faz um insert na tabela Carne_usuario (pode ocorrer um erro se o valor já estiver registrado, mas não tem problema?)
		// Se não está, não insere nada
		for ($i = 0; $i < count($todas); $i++) {
			if (in_array($todas[$i]->getCodigo(), $carnes)) {
				StatementBuilder::insert(
					"INSERT INTO Carne_usuario (usuario_cod, carne_cod) VALUES (:usuario_cod, :carne)",
					[
						'usuario_cod' => $usuario->getCodigo(),
						'carne' => $todas[$i]->getCodigo()
					]
				);
			}
		}
	}

	public static function UpdateEmail(Usuario $usuario)
	{
		$sql = "UPDATE Usuario SET email = :email WHERE codigo = :codigo";
		$params = [
			"email" => $usuario->getEmail(),
			"codigo" => $usuario->getCodigo()
		];

		return StatementBuilder::update($sql, $params);
	}

	/**
	 * Update especial para o ADM que altera apenas nome e username
	 */
	public static function Update2(Usuario $usuario)
	{
		$sql = "UPDATE Usuario SET nome = :nome, username = :username WHERE codigo = :codigo";
		$params = [
			'nome' => $usuario->getNome(),
			'username' => $usuario->getUsername(),
			'codigo' => $usuario->getCodigo()
		];

		return StatementBuilder::update($sql, $params);
	}

	/**
	 * Altera apenas a senha de um usuário
	 */
	public static function UpdateSenha(Usuario $usuario)
	{
		$sql = "UPDATE Usuario SET senha = :senha WHERE codigo = :codigo AND email = :email";
		$params = [
			'senha' => $usuario->getSenha(), // acao.php já coloca em sha1
			'codigo' => $usuario->getCodigo(),
			'email' => $usuario->getEmail()
		];

		return StatementBuilder::update($sql, $params);
	}


	/**
	 * DELETE
	 */

	public static function Delete($codigo)
	{
		$sql = "DELETE FROM Usuario WHERE codigo = :codigo";
		$params = ['codigo' => $codigo];

		return StatementBuilder::delete($sql, $params);
	}

	/**
	 * Deleta todos os registros da tabela 'Carne_usuario' de um determinado usuário
	 */
	public static function CarnesReset($codigo)
	{
		$sql = "DELETE FROM Carne_usuario WHERE usuario_cod = :codigo";
		try {
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":codigo", $codigo);
		} catch (PDOException $e) {
			echo "<b>Erro (UsuarioDao::CarnesReset): </b>" . $e->getMessage();
		}
		return $stmt->execute();
	}


	/**
	 * LOGIN
	 */

	public static function Login(Usuario $usuario)
	{
		$username = $usuario->getUsername();
		$senha = $usuario->getSenha();

		$sql = "SELECT * FROM Usuario
			WHERE `username` = '$username'
			AND `senha` = '$senha'";

		try {
			$query = Conexao::conexao()->query($sql);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}

		$row = $query->fetch(PDO::FETCH_ASSOC);

		$login_info = array();
		/** $login_info
		 * Informações que a função retornará:
		 * ['acao'] -> se o login deverá ser efetuado OU, caso contrário, qual foi o erro
		 * ['codigo'] -> código do usuário
		 * ['username'] -> nome do usuário único para o login
		 * ['nome'] -> nome do usuário
		 * ['tipo'] -> tipo do usuário (adm)
		 * ['codigo', 'nome' e 'tipo'] só serão preenchidas caso o login será feito
		 * serão armazenadas em $_SESSION
		 */
		
		if ($row) {
			$login_info['acao'] = "fazer_login";
			$login_info['codigo'] = $row['codigo'];
			$login_info['username'] = $row['username'];
			$login_info['nome'] = $row['nome'];
			$login_info['tipo'] = $row['tipo']; //tipo (adm)
		} else {
			$login_info['acao'] = 'infos_incorretas';
		}
		return $login_info;
	}

	// //////////////////// //

	/**
	 * Gera uma instância do usuário com todas as suas configurações
	 */
	public static function perfilCompleto(Usuario $usuario)
	{
		$usuario = self::SelectPorCodigo($usuario->getCodigo());
		$usuario = self::SelectFrequencia($usuario);
		$usuario = self::SelectCarnes($usuario);
		$usuario = self::SelectAlimentacao($usuario);

		return $usuario;
	}
}
