<!DOCTYPE html>

<?php

include 'valida_secao.php';

?>

<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
	<?php
	if(isset($_SESSION['matricula'])) {
		echo "<p>Olá, <b>".$_SESSION['nome']."</b>. Sua matrícula é <b>".$_SESSION['matricula']."</b>
		e você é um usuário do tipo <b>".$_SESSION['tipo']."</b></p>";

		echo "<table>
		<tr> <td><b>ADMINISTRADOR</b></td> <td>".ADMINISTRADOR."</td> </tr>
		<tr> <td><b>FUNCIONARIO</b></td> <td>".FUNCIONARIO."</td> </tr>
		<tr> <td><b>ALUNO</b></td> <td>".ALUNO."</td> </tr>
		</table>";

		if($_SESSION['tipo'] == ADMINISTRADOR) {
			echo "<a href='adm_painel.php'>Painel do administrador</a>";
		}
	}
	echo "<a href='usuario_acao.php?acao=logoff'>Sair</a>";

	?>
</body>
</html>
