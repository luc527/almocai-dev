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

// Componente <main> (formulário de login)
$main = file_get_contents('main.html');
$entrar = str_replace('{{main}}', $main, $entrar);

// Erro (se houver)
$erro = "";
if(isset($_GET['erro'])) {
  $erro .= $_GET['erro'];
  if($erro = 'infos_incorretas') $erro_msg = 'As informações digitadas estão incorretas.';
  $erroHTML = file_get_contents('erro.html');
  $erroHTML = str_replace('{erro_msg}', $erro_msg, $erroHTML);
}
$entrar = str_replace('{{erro}}', $erroHTML, $entrar);

// Caminho à raiz do sistema
$root_path = "../";
$entrar = str_replace('{root_path}', $root_path, $entrar);

print($entrar);
?>
