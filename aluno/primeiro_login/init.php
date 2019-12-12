<?php

$root_path = "../../";

include $root_path.'valida_secao.php';
valida_secao_tipo($root_path, ['ALUNO', 'ADMINISTRADOR']);

require_once $root_path.'classes/Usuario.class.php';
require_once $root_path.'classes/UsuarioDao.class.php';
require_once $root_path.'classes/FrequenciaDao.class.php';
require_once $root_path.'classes/AlimentacaoDao.class.php';


include $root_path.'aluno/perfil/funcoes.php';
// Usa uma função para gerar os formulários de alimentação e de presença do aluno

/* Carrega informações do usuário */
$usuario = new Usuario;
$usuario->setCodigo($_SESSION['codigo']);
