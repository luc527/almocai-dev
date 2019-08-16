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
		$semana = new SemanaCardapio;
		$semana->setCodigo($_POST['semana_codigo']);

		$diaDao = new DiaAlmocoDao;
		$codigo = $diaDao->SelectUltimoCod() + 1;

		$dia = new DiaAlmoco;
		$dia->setData($_POST['data']);
		$dia->setCodigo($codigo);

		$semana->setDia($dia);

		$semanaDao = new SemanaCardapioDao;
		$semanaDao->InserirDias($semana);
	}
}

?>

<head>
	<title>Cadastro de dia</title>
	<meta charset="utf-8">
</head>
<body>
	<form action="" method="post">
		<input type="date" name="data" id="data">
		<input type="hidden" name="semana_codigo" id="semana_codigo" value="<?php echo $_GET['semana_codigo']; ?>">
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
					<th>Cadastrar alimento</th>
				</tr>
			</thead>

			<tbody>

			<?php
			for ($i=0; $i < count($dias); $i++)
			{ 
				echo "<tr>";
				
				echo "<td>".$dias[$i]->getCodigo()."</td>";
				echo "<td>".$dias[$i]->getData()."</td>";

				echo "</tr>";
			}

			?>

			</tbody>
		</table>
	</div>
</body>
</html>