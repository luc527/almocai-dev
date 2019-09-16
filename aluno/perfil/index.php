<?php
$root_path = "../../";

/** PROVISÓRIO (p/ testar) */
//include($root_path.'valida_secao.php');
//valida_secao($root_path);
$_SESSION['matricula'] = 2019;
/** */

require_once($root_path."classes/UsuarioDao.class.php");

// Carrega o template geral
$perfil = file_get_contents($root_path."template.html");

// Título da página
$title = "Perfil";
$perfil = str_replace("{title}", $title, $perfil);

// Vazios (peso_fonte)
$perfil = str_replace("{peso_fonte}", "", $perfil);

// <NAV>
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$perfil = str_replace("{{nav}}", $nav, $perfil);

// <FOOTER>
$footer = file_get_contents($root_path."componentes/footer.html");
$perfil = str_replace("{{footer}}", $footer, $perfil);

// Scripts
$scripts = file_get_contents("scripts.js");
$perfil = str_replace("{{scripts}}", $scripts, $perfil);

// <MAIN>
$main = file_get_contents("main.html");
$perfil = str_replace("{{main}}", $main, $perfil);


/**
 * CARTÕES
 */
// Cartão almoço
$almoco = file_get_contents("cartao_almoco.html");
$perfil = str_replace("{{cartao_almoco}}", $almoco, $perfil);
// Cartão intolerância
$intolerancia = file_get_contents("cartao_intolerancia.html");
$perfil = str_replace("{{cartao_intolerancia}}", $intolerancia, $perfil);
// Cartão carne
$carne = file_get_contents("cartao_carne.html");
$perfil = str_replace("{{cartao_carne}}", $carne, $perfil);
// Cartão vegetariano
$vegetariano = file_get_contents("cartao_vegetariano.html");
$perfil = str_replace("{{cartao_vegetariano}}", $vegetariano, $perfil);
// Cartão vegano
$vegano = file_get_contents("cartao_vegano.html");
$perfil = str_replace("{{cartao_vegano}}", $vegano, $perfil);
// Cartão alt_senha
$alt_senha = file_get_contents("cartao_alt_senha.html");
$perfil = str_replace("{{cartao_alt_senha}}", $alt_senha, $perfil);


/**
 * A partir da matrícula na seção, consulta as informações do BD
 */
$usuario = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);
$perfil = str_replace("{nome}", $usuario->getNome(), $perfil); // Nome do usuário na página
$perfil = str_replace("{matricula}", $usuario->getCodigo(), $perfil); // Matricula do usuário


// Caminho à raiz do projeto -- sempre por último, antes do print
$perfil = str_replace("{root_path}", $root_path, $perfil);
// Renderiza página ao usuário
print ($perfil);
?>