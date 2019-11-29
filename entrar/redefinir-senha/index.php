<?php

// Função que envia o e-mail
function EnviarEmail($hash, $email) {
	$root = "https://fabricadetecnologias.ifc-riodosul.edu.br/almocai";
	$uri = "/entrar/nova-senha/?hash={$hash}&email={$email}";
	
	$to = $GLOBALS['email'];
	$link = $root.$uri;

	$subject = "Almocaí - Redefinir senha";
	
	$content = file_get_contents('modelo-mensagem.html');
	$content = str_replace('{link}', $link, $content);
	$content = str_replace('{year}', date("Y"), $content);

	$from = 'mateus.lucas840@outlook.com';

	$headers = "From: '. $from. ' \r\n";
  $headers .= "Reply-To: '. $from. '\r\n";
  $headers .= "Return-Path: '. $from. '\r\n";
  $headers .= "Organization: Instituto Federal Catarinense - Campus Rio do Sul\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "X-Priority: 3\r\n";
  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

	try {
		return mail($to, $subject, $content, $headers) ? true : false;
	} catch (Exception $e) {
		return false;
	}

}


$root_path = "../../";

require_once "{$root_path}classes/UsuarioDao.class.php";

$email = isset($_POST['email']) ? $_POST['email'] : "";

if ($email == "")
{
	$msg_email_enviado = "";

} else{
	if(UsuarioDao::VerificaEmail($email) > 0){
		// Carrega usuário do BD pelo e-mail e carrega hash
		$usuario = UsuarioDao::SelectPorEmail($email);

		$hash = $usuario->hash();

		if (EnviarEmail($hash, $email)) {
			$msg_email_enviado = file_get_contents("msg_email_enviado.html");
		} else {
			$msg_email_enviado = file_get_contents("msg_email_invalido.html");
			$errorMessage = 'Não foi possível enviar o email, tente novamente mais tarde. Caso o erro persista, contate a Cordenação Geral de Ensino';
			$msg_email_enviado = str_replace('{errorMessage}', $errorMessage, $msg_email_enviado);
		}

	}
	else{
		$msg_email_enviado = file_get_contents("msg_email_invalido.html");
		$errorMessage = 'O email inserido é invalido. Verifique seu email e tente novamente!';
		$msg_email_enviado = str_replace('{errorMessage}', $errorMessage, $msg_email_enviado);
	}
}

$main = file_get_contents("main.html");
$main = str_replace("{{msg_email_enviado}}", $msg_email_enviado, $main);

$pagina = file_get_contents("{$root_path}template.html");
$pagina = str_replace("{title}", "Redefinir senha de senha", $pagina);
$pagina = str_replace("{peso_fonte}", "", $pagina);
$pagina = str_replace("{{nav}}", "", $pagina);
$pagina = str_replace("{{footer}}", "", $pagina);
$pagina = str_replace("{{scripts}}", "", $pagina);
$pagina = str_replace("{{main}}", $main, $pagina);

$pagina = str_replace("{root_path}", $root_path, $pagina);

print $pagina;