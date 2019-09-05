<!DOCTYPE html>

<?php
require_once('autoload.php');
include 'valida_secao.php';

if (isset($_POST['codigo'])) $codigo = $_POST['codigo'];
else if (isset($_GET['codigo'])) $codigo = $_GET['codigo'];

if (isset($codigo)) {
	$semana = SemanaCardapioDao::SelectPorCodigo($codigo);
}


?>

<html>
<head>
	<title>Visualização de cardápio</title>
	<meta charset="utf-8">
</head>
<body>
	<div class="container" style="width:70%; margin: auto;">
		<table border="1" style="background-color: lightgrey;">
			<thead>
				<tr>
					<th>Código</th>
					<th>Data do início</th>
					<th>Dias</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td> <?php echo $semana->getCodigo(); ?> </td>
					<td> <?php echo $semana->getData_inicio(); ?> </td>
					<td>
					<?php
						$semana = SemanaCardapioDao::SelectDias($semana);
						$dias = $semana->getDias();
					?>
						<table border="1" style="background-color: white;">
							<thead>
								<tr>
									<th>Código</th>
									<th>Data</th>
									<th>Alimentos</th>
									<th>Marcar presença</th>
									<th>Presença marcada</th>
								</tr>
							</thead>

							<tbody>
							<?php
								for ($i=0; $i < count($dias); $i++) {
								?>
									<tr>
										<td> <?php echo $dias[$i]->getCodigo(); ?> </td>
										<td> <?php echo Funcoes::DataBDParaUser( $dias[$i]->getData() ); ?> </td>

										<td>
											<ul>
											<?php
												$dias[$i] = DiaAlmocoDao::SelectAlimentos($dias[$i]);
												$alimentos = $dias[$i]->getAlimentos();
												for ($j=0; $j < count($alimentos); $j++) {
													echo "<li>".$alimentos[$j]->getDescricao()." (".$alimentos[$j]->getTipo().")</li>";
												}
											?>
											</ul>
										</td>

										<td>
											<form action="presenca_acao.php" method="post"> <fieldset>
												<input type="hidden" name="cod_semana" value="<?php echo $codigo; ?>">
												<input type="hidden" name="matricula" value="<?php echo $_SESSION['matricula']; ?>">
												<input type="hidden" name="dia" value="<?php echo $dias[$i]->getCodigo(); ?>">
												<center>
													<button type="submit" name="presenca" value="1">Estarei presente</button> <br/>
													<button type="submit" name="presenca" value="0">Não virei</button>
												</center>
											</fieldset> </form>
										</td>

										<td>

										</td>
									</tr>
								<?php
								}
							?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
