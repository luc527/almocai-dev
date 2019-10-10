<?php
$root_path = "../";

require "{$root_path}valida_secao.php";
valida_secao($root_path);

require_once "{$root_path}classes/Conexao.class.php";
require_once "{$root_path}classes/Usuario.class.php";
require_once "{$root_path}classes/UsuarioDao.class.php";

$usuario = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);