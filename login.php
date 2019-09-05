<!DOCTYPE html>

<?php

if (isset($_GET['erro'])) $erro = $_GET['erro'];
else if (isset($_POST['erro'])) $erro = $_POST['erro'];
else $erro = '';

?>

<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
</head>
<body>
	<div class="container">
		<form action="usuario_acao.php" method="post"> <fieldset>
			<label for="matricula">Matrícula: </label> <br/>
			<input type="text" name="matricula"> <br/><br/>

			<label for="senha">Senha: </label> <br/>
			<input type="password" name="senha"> <br/><br/>

			<button type="submit" name="acao" value="Login">Login</button>
		</fieldset> </form>
		<br/>
		<?php
			if($erro == 'infos_incorretas') {
				echo "<b>Erro: </b>As informações informadas estão incorretas.";
			}
		?>
</body>
</html>
