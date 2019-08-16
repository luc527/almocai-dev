<!DOCTYPE html>
<html>

<?php 
	
require_once "autoload.php";

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if(isset($acao))
{
	if ($acao == 'Inserir')
	{
		$semanaDao = new SemanaCardapioDao;
		$codigo = $semanaDao->SelectUltimoCod();

		$semana = new SemanaCardapio;
		$semana->setCodigo($codigo);

		$semanaDao->Inserir($semana);
	}
}

?>

<head>
	<title>Cadastro de cardápio semanal</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="" method="post">
		<button name="acao" value="Inserir">Criar semana</button>
	</form>

	<hr/>

	<div style="width: 70%; margin: auto;">
		<?php
		$semanaDao = new SemanaCardapioDao;
		$semanas = $semanaDao->SelectTodos();
		?>

		<table border="1">
			<thead>
				<tr>
					<th>Código</th>
				</tr>
			</thead>

			<tbody>

			<?php
			for ($i=0; $i < count($semanas); $i++)
			{ 
				echo "<tr>";
				
				echo "<td>".$semanas[$i]->getCodigo()."</td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>