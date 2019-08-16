<!DOCTYPE html>
<html>

<?php 
	
require_once "autoload.php";

?>

<head>
	<title>Cadastro de dia</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="diaAlmoco_acao.php" method="post">
		<input type="date" name="data" id="data"> <br/>
		<?php echo DiaSemanaDao::GerarSelectHTML(); ?> <br/>
		<label for="semanaCardapio_codigo">Semana</label> <?php echo SemanaCardapioDao::GerarSelectHTML(); ?> <br/>
		<button name="acao" value="Inserir">Criar dia</button>
	</form>

	<hr/>

	<div style="width: 70%; margin: auto;">

		<a href="semanaCardapio_list.php">Listagem de semanas</a>

		<?php
		$diaDao = new DiaAlmocoDao;
		$dias = $diaDao->SelectTodos();
		?>

		<table border="1">
			<thead>
				<tr>
					<th>Código</th>
					<th>Data</th>
					<th>Dia da semana</th>
					<th>Cadastrar alimento</th>
				</tr>
			</thead>

			<tbody>

			<?php
			for ($i=0; $i < count($dias); $i++) { 
				echo "<tr>";
				
				echo "<td>".$dias[$i]->getCodigo()."</td>";
				echo "<td>".$dias[$i]->getData()."</td>";
				echo "<td>".$dias[$i]->getDiaSemana()->getDescricao()."</td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>