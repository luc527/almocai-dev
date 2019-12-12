<?php

$root_path = '../../';

include $root_path.'valida_secao.php';
valida_secao_tipo($root_path, ['ALUNO', 'ADMINISTRADOR']);

$acao = isset($_POST['acao']) ? $_POST['acao'] : '';
if ($acao != 'ConfigurarPrimeiroLogin')
	header('location:'.$root_path);

require_once $root_path.'classes/Frequencia.class.php';
require_once $root_path.'classes/Alimentacao.class.php';
require_once $root_path.'classes/Usuario.class.php';
require_once $root_path.'classes/UsuarioDao.class.php';


$frequencia = new Frequencia;
$frequencia->setCodigo($_POST['frequencia']);

$alimentacao = new Alimentacao;
$alimentacao->setCodigo($_POST['alimentacao']);

$usuario = UsuarioDao::SelectPorCodigo($_SESSION['codigo']);
$usuario->setFrequencia($frequencia);
$usuario->setAlimentacao($alimentacao);

UsuarioDao::UpdateFrequencia($usuario);
UsuarioDao::UpdateAlimentacao($usuario);
UsuarioDao::MarcarPrimeiroLogin($usuario);

header('location:'.$root_path);