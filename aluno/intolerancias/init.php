<?php

$root_path = '../../';

include $root_path.'valida_secao.php';
valida_secao_tipo($root_path, ['ALUNO', 'ADMINISTRADOR']);

require_once $root_path."classes/Conexao.class.php";
require_once $root_path."classes/StatementBuilder.class.php";
require_once $root_path."classes/AbsCodigoDescricao.class.php";
require_once $root_path."classes/Intolerancia.class.php";
require_once $root_path."classes/IntoleranciaDao.class.php";