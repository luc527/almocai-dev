<?php

require 'init.php';

$acao = isset($_POST['acao']) ? $_POST['acao'] : "";

if ($acao == "solicitarIntolerancia")
{
	$intol = new Intolerancia;
	$intol->setCodigo($_POST['intolerancia_cod']);
	$intol->setDocumento($_POST['documento']);
	// ...
}