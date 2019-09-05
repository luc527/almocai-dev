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
	<div class="container" style="width:70%; margin: auto;">
		<form action="usuario_acao.php" method="post"> <fieldset>
			<label for="matricula">Matrícula: </label> <br/>
			<input type="text" name="matricula"> <br/><br/>

			<label for="senha">Senha: </label> <br/>
			<input type="password" name="senha"> <br/><br/>

			<button type="submit" name="acao" value="Login">Login</button>
		</fieldset> </form>
		<br/>
		<?php
			if ($erro == 'matricula_nao_existe') {
				echo "<i>Erro: a matrícula informada não existe.</i>";
			} else if ($erro == 'senha_incorreta') {
				echo "<i>Erro: a senha informada está incorreta.</i>";
			}
		?>
</body>
</html>
