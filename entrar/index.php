<?php
$entrar = file_get_contents('../template.html');

// Título da página
$title = "Entrar";
$entrar = str_replace('{title}', $title, $entrar);

// Vazios (nav, footer, scripts, peso_fonte)
$entrar = str_replace('{{nav}}', "", $entrar);
$entrar = str_replace('{{footer}}', "", $entrar);
$entrar = str_replace('{{scripts}}', "", $entrar);
$entrar = str_replace('{peso_fonte}', "", $entrar);

// Componente <main>
$main = file_get_contents('main.html');
$entrar = str_replace('{{main}}', $main, $entrar);

// Caminho à raiz do sistema
$root_path = "../";
$entrar = str_replace('{root_path}', $root_path, $entrar);

print($entrar);
?>
