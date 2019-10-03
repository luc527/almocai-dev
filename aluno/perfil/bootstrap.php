<?php

include $root_path . 'valida_secao.php';
valida_secao_tipo($root_path, 'ALUNO');

require_once $root_path . "classes/UsuarioDao.class.php";
require_once $root_path . "classes/CarneDao.class.php";
require_once $root_path . "classes/AlimentacaoDao.class.php";
require_once $root_path . "classes/FrequenciaDao.class.php";
include 'funcoes.php';

$usuario = new Usuario;
$usuario->setCodigo($_SESSION['matricula']);
$usuario = UsuarioDao::perfilCompleto($usuario);
