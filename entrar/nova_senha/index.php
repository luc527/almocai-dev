<?php

$root_path = "../../";

// Função de redirecionamento, usada nos vários casos em que o usuário não pode acessar essa página
function Redir()
{
	header("location:{$GLOBALS['root_path']}entrar/");
}


// Pega usuário do banco
if (isset($_GET['email'])) {
	$email = $_GET['email'];

	$usuario = UsuarioDao::SelectPorEmail($email);
} else Redir();

// Confirma hash
if (isset($_GET['hash'])) {
	$hash = $_GET['hash'];

	if ($usuario->hash() != $hash) Redir();

} else Redir();

// Gera a página de alteração de senha

$novas = file_get_contents("{$root_path}template.html");

$novas = str_replace("{title}", "Recuperação de senha", $novas);
$novas = str_replace("{peso_fonte}", "", $novas);
$novas = str_replace("{{nav}}", "", $novas);
$novas = str_replace("{{footer}}", "", $novas);
$novas = str_replace("{{scripts}}", "", $novas);
$novas = str_replace("{{main}}", file_get_contents("main.html"), $novas);

print $novas;