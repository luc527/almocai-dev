<!DOCTYPE html>
<html>

<?php 
	
require_once "autoload.php";

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

$semanaDao = new SemanaCardapioDao;

if (isset($acao)) {
	if ($acao == 'SelectPorCriterio') {
		$semanas = $semanaDao->SelectPorCriterio($_POST['pesquisa'], $_POST['criterio']);
		$semanas = $semanaDao->SelectDias($semanas);
	}
} else {
	$semanas = $semanaDao->SelectDias($semanaDao->SelectTodos());
}


?>

<head>
	<title>Cadastro de cardápio semanal</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="semanaCardapio_acao.php" method="post">
		<fieldset>
			<legend>Cadastro</legend>
			<label for="data_inicio">Data do início da semana (segunda): </label> <input type="date" name="data_inicio"> <br/>
			<button name="acao" value="Inserir">Criar semana</button>
		</fieldset>
	</form> <br/>

	<form action="" method="post">
		<fieldset>
			<legend>Listagem</legend>
			<input type="text" name="pesquisa" placeholder="Digite sua pesquisa aqui"> <br/>
			
			<label for="criterio">Critério</label> <br/>
			<label>
				<input type="radio" name="criterio" value="data_inicio">
				Data de início (DD/MM/AAAA)
			</label> <br/>
			<label>
				<input type="radio" name="criterio" value="codigo">
				Código
			</label> <br/>

			<button type="submit" name="acao" value="SelectPorCriterio">Pesquisar</button>
		</fieldset>
	</form> <br/>

	<hr/>

	<div style="width: 90%; margin: auto;">
		<table border="1" style="background-color: lightgrey">
			<thead>
				<tr>
					<th>Código</th>
					<th>Data do início</th>
					<th>Dias</th>
					<th>Remover</th>
				</tr>
			</thead>

			<tbody>

			<?php
			for ($i=0; $i < count($semanas); $i++) { 
				echo "<tr>";
				
				echo "<td>".$semanas[$i]->getCodigo()."</td>";
				$dataSemana = Funcoes::DataBDParaUser($semanas[$i]->getData_inicio());
				echo "<td>".$dataSemana."</td>";

				$dias = $semanas[$i]->getDias();

				echo "<td>";

					echo "<table border='1' style='background-color: white'>";
						echo "<thead>";
							echo "<tr>";
								echo "<th>Código</th>";
								echo "<th>Data</th>";
								echo "<th>Dia da semana</th>";
								echo "<th>Alimentos</th>";
								echo "<th>Cadastrar alimentos</th>";
							echo "</tr>";
						echo "</thead>";

						echo "<tbody>";
							
					$diaDao = new DiaAlmocoDao;
					$dias = $diaDao->SelectAlimentos($dias);

					for ($j=0; $j < count($dias); $j++) { 
						$dataDia = Funcoes::DataBDParaUser($dias[$j]->getData());

						echo "<tr>";
							echo "<td>".$dias[$j]->getCodigo()."</td>";
							echo "<td>".$dataDia."</td>";
							echo "<td>".$dias[$j]->getDiaSemana()->getDescricao()."</td>";
							echo "<td>";

							$alimentos = $dias[$j]->getAlimentos();
							
							echo "<ul>";
								for ($k=0; $k < count($alimentos); $k++) { 
									echo "<li>";
									echo "<b>".$alimentos[$k]->getDescricao()."</b> [#".$alimentos[$k]->getCodigo()."]";
									echo " - <a href='alimento_acao.php?acao=Deletar&codigo=".$alimentos[$k]->getCodigo()."'>Deletar</a>";
									echo "</li>";
								}
							echo "</ul>";

							echo "</td>";

							echo "<td>";

								echo "<form action='alimento_acao.php' method='post'>";
									echo "<fieldset>";
										echo "<input type='text' name='descricao'>";
										echo "<input type='hidden' name='diaAlmoco_codigo' value='".$dias[$j]->getCodigo()."'>";
										echo "<button name='acao' value='Inserir'>Adicionar</button>";
									echo "</fieldset>";
								echo "</form>";

							echo "</td>";

						echo "<tr>";
					}

						echo "</tbody>";
					echo "</table>";
				echo "</td>";

				echo "<td>";
					echo "<a href='semanaCardapio_acao.php?acao=Deletar&codigo=".$semanas[$i]->getCodigo()."'>X</a>";
				echo "</td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>