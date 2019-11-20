<?php 

$root_path = "../../";

require_once "{$root_path}classes/Usuario.class.php";
require_once "{$root_path}classes/UsuarioDao.class.php";

if (!isset($_POST['nova_senha'])) {
	header("{$root_path}entrar/");
}

$usuario = new Usuario;
$usuario->setCodigo($_POST['usuario_codigo']);
$usuario->setEmail($_POST['usuario_email']);
$usuario->setSenha(sha1($_POST['nova_senha']));
UsuarioDao::UpdateSenha($usuario);

header("location:{$root_path}entrar/?sucesso=senha_alterada");
