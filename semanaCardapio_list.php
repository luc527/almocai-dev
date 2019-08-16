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
		$codigo = $semanaDao->SelectUltimoCod() + 1;

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
		$semanas = $semanaDao->SelectTodosComDias();
		?>

		<table border="1">
			<thead>
				<tr>
					<th>Código</th>
					<th>Dias</th>
					<th>Cadastrar dia</th>
				</tr>
			</thead>

			<tbody>

			<?php
			for ($i=0; $i < count($semanas); $i++)
			{ 
				echo "<tr>";
				
				echo "<td>".$semanas[$i]->getCodigo()."</td>";
				$dias = $semanas[$i]->getDias();
				echo "<td>";
				for ($j=0; $j < count($dias); $j++)
				{ 
					echo $dias[$i]->getData()."<br/>";
				}
				echo "</td>";

				echo "<td><center><a href='diaAlmoco_cad+list.php?semana_codigo=".$semanas[$i]->getCodigo()."'>+</a></center></td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>