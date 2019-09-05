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
		<tr> <td><b>".ADM."</b></td> <td>ADM</td>  <td>Administrador(a)</td> </tr>
		<tr> <td><b>".FUNC."</b></td> <td>FUNC</td> <td>Funcionário(a)</td> </tr>
		<tr> <td><b>".ALUNO."</b></td> <td>ALUNO</td> <td>Aluno(a)</td> </tr>
		</table>";

		if($_SESSION['tipo'] == ADM) {
			echo "<a href='adm_painel.php'>Painel do administrador</a>";
		}
	}


	?>
</body>
</html>
