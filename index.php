<!DOCTYPE html>

<?php

include 'valida_secao_aluno.php';

?>

<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
	<?php
	if(isset($_SESSION['aluno_matricula'])) {
		echo "<p>Olá, <b>".$_SESSION['aluno_nome']."</b>. Sua matrícula é <b>".$_SESSION['aluno_matricula']."</b></p>";
	}
	echo "<a href='aluno_acao.php?acao=loginoff'>Sair</a>"

	?>
</body>
</html>