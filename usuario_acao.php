<?php
    require_once "autoload.php";

    if (isset($_POST['acao'])) $acao = $_POST['acao'];
    else if (isset($_GET['acao'])) $acao = $_GET['acao'];
    else $acao = '';

    if ($acao == "inserir") {
        // adquirindo campos
        $matricula = isset($_POST['matricula'])?$_POST['matricula']:"";
        $senha = isset($_POST['senha'])?sha1($_POST['senha']):"";
        $nome = isset($_POST['nome'])?$_POST['nome']:"";

        // Populando Usuario
        $usuario = new Usuario;
        $usuario->setCodigo($matricula);
        $usuario->setSenha($senha);
        $usuario->setNome($nome);

        // Inserindo Usuario
        UsuarioDao::Inserir($usuario);
        #header("aluno_cadastro.php");
    } else if ($acao == 'Login') {
        $usuario = new Usuario;
        $usuario->setCodigo( $_POST['matricula'] );
        $usuario->setSenha( sha1($_POST['senha']) );

        $login = UsuarioDao::Login($aluno);

        var_dump($login);

        if ($login[0] != 'fazer_login') {
            header("location:login.php?erro=".$login[0]);
        } else {
            session_start();
            $_SESSION['matricula'] = $login[1];
            $_SESSION['nome'] = $login[2];
            $_SESSION['tipo'] = $login[3];
            header("location:index.php");
        }
    }
?>
