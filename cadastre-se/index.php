<?php
$root_path = "../";
//require_once($root_path."");

// Página recebe o template
$cadastre = file_get_contents($root_path."template.html");

// Título da página
$title = "Cadastre-se";
$cadastre = str_replace('{title}', $title, $cadastre);

// Vazios (HEADER, SCRIPTS, peso_fonte)
$cadastre = str_replace("{{nav}}", "", $cadastre);
$cadastre = str_replace('{{scripts}}', '', $cadastre);
$cadastre = str_replace('{peso_fonte}', '', $cadastre);

// <FOOTER>
$footer = file_get_contents($root_path."componentes/footer.html");
$cadastre = str_replace("{{footer}}", $footer, $cadastre);

// <MAIN>
$main = file_get_contents("main.html");
$cadastre = str_replace("{{main}}", $main, $cadastre);


// Caminho à raiz
$cadastre = str_replace('{root_path}', $root_path, $cadastre);

// Renderiza a página ao usuário
print($cadastre);
?>
