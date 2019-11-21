<?php

// Função que envia o e-mail
function EnviarEmail($hash, $email) {
	$root = "localhost/almocai";
	$uri = "/entrar/nova_senha/?hash={$hash}&email={$email}";
	
	$to = $GLOBALS['email'];
	
	$subject = "Almocaí - Recuperação de senha";
	
	$link = $root.$uri;
	$message = "Acesse o link para recuperar sua senha: \n {$link}";

	// echo $message."<br>";

	$headers = "From: noreply@almocai.com";

	mail($to, $subject, $message, $headers);
}


$root_path = "../../";

require_once "{$root_path}classes/UsuarioDao.class.php";

$email = isset($_POST['email']) ? $_POST['email'] : "";

if ($email == "")
{
	$msg_email_enviado = "";

} else
{
	// Carrega usuário do BD pelo e-mail e carrega hash
	$usuario = UsuarioDao::SelectPorEmail($email);
	$hash = $usuario->hash();

	EnviarEmail($hash, $email);
	$msg_email_enviado = file_get_contents("msg_email_enviado.html");
}

$main = file_get_contents("main.html");
$main = str_replace("{{msg_email_enviado}}", $msg_email_enviado, $main);

$pagina = file_get_contents("{$root_path}template.html");
$pagina = str_replace("{title}", "Recuperação de senha", $pagina);
$pagina = str_replace("{peso_fonte}", "", $pagina);
$pagina = str_replace("{{nav}}", "", $pagina);
$pagina = str_replace("{{footer}}", "", $pagina);
$pagina = str_replace("{{scripts}}", "", $pagina);
$pagina = str_replace("{{main}}", $main, $pagina);

$pagina = str_replace("{root_path}", $root_path, $pagina);

print $pagina;