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
	
		if($_SESSION['tipo'] == ADMINISTRADOR) {
			echo "<a href='adm_painel.php'>Painel do administrador</a>";
			echo "<br/><br/>";
		}
	}
	echo "<a href='usuario_acao.php?acao=logoff'>Sair</a>";

	?>
</body>
</html>
