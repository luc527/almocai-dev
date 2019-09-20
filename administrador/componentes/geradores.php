<?php
$root_path = "../../";

function gerarMain ($tipo, $pesquisa, $root_path) {
  $main = file_get_contents($root_path . "administrador/componentes/main.html");
  $main = str_replace("{tipo}", $tipo, $main);
  
  $add_users = gerarAddUsers ($tipo, $root_path);
  $main = str_replace("{{adicionar_usuario}}", $add_users, $main);

  $list_users = gerarListUsers ($tipo, $pesquisa, $root_path);
  $main = str_replace("{{listagem_usuarios}}", $list_users, $main);

  if ($tipo == 'aluno') {
    $cor_texto = "";
    $cor_btn = ""; // vazio -> verde por padrão
  } else if ($tipo == 'funcionario') { 
    $cor_texto = "text-azul";
    $cor_btn = "azul";
  }
  $main = str_replace("{cor_texto}", $cor_texto, $main);
  $main = str_replace("{cor_btn}", $cor_btn, $main);

  return $main;
}


function gerarAddUsers($tipo, $root_path) {
  $tipoBD = strtoupper($tipo);

  $add_users = file_get_contents($root_path . "administrador/componentes/adicionar_usuario.html");
  $add_users = str_replace("{tipo}", $tipo, $add_users);
  $add_users = str_replace("{tipoBD}", $tipoBD, $add_users);

  return $add_users;
}


function gerarListUsers ($tipo, $pesquisa, $root_path) {
  $tipoBD = strtoupper($tipo);

  $usersBD = UsuarioDao::Select2($tipoBD, $pesquisa);
  $users = gerarLinhas($usersBD);

  $list_users = file_get_contents($root_path . "administrador/componentes/listagem_usuarios.html");
  $list_users = str_replace("{tipo}", $tipo, $list_users);
  $list_users = str_replace("{{linhas_usuario}}", $users, $list_users);

  return $list_users;
}



function gerarLinhas ($registros) {
  $linhas = "";

  if ($registros !== null) { // só para não retornar erro caso não existam usuários cadastrados
    foreach ($registros as $reg) {
      $linha = file_get_contents($GLOBALS['root_path']."administrador/componentes/linha_usuario.html");
      
      $matricula = $reg->getCodigo();
      $nome = $reg->getNome();

      $linha = str_replace("{matricula}", $matricula, $linha);
      $linha = str_replace("{nome}", $nome, $linha); 
      
      $linhas .= $linha;  
    }
  }

  return $linhas;
}
?>