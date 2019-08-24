<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="aluno_acao.php" method="post"> <fieldset>
		<label for="matricula">Matrícula: </label> <br/>
		<input type="text" name="matricula"> <br/><br/>

		<label for="senha">Senha: </label> <br/>
		<input type="password" name="senha"> <br/><br/>

		</fieldset> <button type="submit" name="acao" value="Login">Login</button>
	</form>
</body>
</html>