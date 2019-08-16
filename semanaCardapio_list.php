<!DOCTYPE html>
<html>

<?php 
	
require_once "autoload.php";

?>

<head>
	<title>Cadastro de cardápio semanal</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="semanaCardapio_acao.php" method="post">
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
			for ($i=0; $i < count($semanas); $i++) { 
				echo "<tr>";
				
				echo "<td>".$semanas[$i]->getCodigo()."</td>";
				$dias = $semanas[$i]->getDias();
				echo "<td>";
				for ($j=0; $j < count($dias); $j++) { 
					echo $dias[$j]->getData()." - ".$dias[$j]->getDiaSemana()->getDescricao();
					echo "<br/>";
				}
				echo "</td>";

				echo "<td><center><a href='diaAlmoco_list.php?semana_codigo=".$semanas[$i]->getCodigo()."'>+</a></center></td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>