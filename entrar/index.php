<?php
// Caminho à raiz do projeto
$root_path = "../";

$entrar = file_get_contents($root_path.'template.html');

// Título da página
$title = "Entrar";
$entrar = str_replace('{title}', $title, $entrar);

// Vazios (nav, footer, scripts, peso_fonte)
$entrar = str_replace('{{nav}}', "", $entrar);
$entrar = str_replace('{{footer}}', "", $entrar);
$entrar = str_replace('{peso_fonte}', "", $entrar);

// Scripts + erro ao logar
$scripts = file_get_contents("scripts.js");
$erro_trigger = "";
if (isset($_GET['erro'])) {
  $erro_trigger = "erroLogin()";
}
$scripts = str_replace("{erro_trigger}", $erro_trigger, $scripts);
$entrar = str_replace("{{scripts}}", $scripts, $entrar);

// Componente <main> (formulário de login)
$main = file_get_contents('main.html');
$entrar = str_replace('{{main}}', $main, $entrar);

$entrar = str_replace('{root_path}', $root_path, $entrar);
print($entrar);
?>
