<?php

require 'init.php';

$usuario = UsuarioDao::SelectPorCodigo($_SESSION['codigo']);

echo "<pre>";
 var_dump($_FILES);
echo "</pre>";
$pasta_destino = $root_path."/arquivos/intolerancia/";
$acao = isset($_POST['acao']) ? $_POST['acao'] : "";

die(var_dump(Funcoes::ProximoCod('Intolerancia')));

if ($acao == "solicitarIntolerancia")
{
	
	if ($_POST['intolerancia_cod'] == -1) {
		$intol = new Intolerancia;
		$intol->setCodigo(Funcoes::ProximoCod('Intolerancia'));
		$intol->setDescricao($_POST["nova_intolerancia"]);
		IntoleranciaDao::Insert($intol);
	} else {
		$intol = IntoleranciaDao::SelectPorCodigo($_POST['intolerancia_cod']);
	}

	$intolUsuario = new IntoleranciaUsuario;
	$intolUsuario->setIntolerancia($intol);
	if(IntoleranciaUsuarioDao::Inserir($intolUsuario, 'documento', $_SESSION['codigo'], $pasta_destino)){
		$usuario->setIntolerancia($intolUsuario);
	}
	

}