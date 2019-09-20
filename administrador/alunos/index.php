<?php
$root_path = '../../';

// Validar seção adm
include($root_path."valida_secao.php");
valida_secao_adm($root_path);

require_once($root_path."classes/UsuarioDao.class.php");
require_once($root_path."administrador/componentes/geradores.php");

$ger_alunos = file_get_contents($root_path."template.html");
// Valores e componentes do template
$title = "Gerenciamento de alunos";
$peso_fonte = ",200,700";
$nav = file_get_contents($root_path."componentes/nav-administrador.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents($root_path."administrador/componentes/scripts.js");
// Carregando valores e componentes no template
$ger_alunos = str_replace("{title}", $title, $ger_alunos);
$ger_alunos = str_replace("{peso_fonte}", $peso_fonte, $ger_alunos);
$ger_alunos = str_replace("{{nav}}", $nav, $ger_alunos);
$ger_alunos = str_replace("{{footer}}", $footer, $ger_alunos);
$ger_alunos = str_replace("{{scripts}}", $scripts, $ger_alunos);

// Main (função gerarMain em administrador/componentes/geradores.php)
$pesquisa = isset($_POST['pesquisa']) ? $_POST['pesquisa'] : 'TODOS';
$main = gerarMain('aluno', $pesquisa, $root_path);

// Carrega MAIN na página de gerenciamento
$ger_alunos = str_replace("{{main}}", $main, $ger_alunos);

// Carregando root_path na página
$ger_alunos = str_replace("{root_path}", $root_path, $ger_alunos);
// Renderização da página
print($ger_alunos);
?>