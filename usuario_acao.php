<?php
    include 'config.php';

    require_once "autoload.php";

    if (isset($_POST['acao'])) $acao = $_POST['acao'];
    else if (isset($_GET['acao'])) $acao = $_GET['acao'];
    else $acao = '';
    
    if ($acao == "inserir") {
        // Adquirindo campos do formulário HTML
        $matricula = isset($_POST['matricula'])?$_POST['matricula']:"";
        $senha = isset($_POST['senha'])?sha1($_POST['senha']):"";
        $nome = isset($_POST['nome'])?$_POST['nome']:"";
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : ALUNO;

        // Populando Objeto Usuario
        $usuario = new Usuario;
        $usuario->setCodigo($matricula);
        $usuario->setSenha($senha);
        $usuario->setNome($nome);
        $usuario->setTipo($tipo);

        // Inserindo Usuario no BD
        UsuarioDao::Inserir($usuario);

        header("location:login.php");
    } else if ($acao == 'Login') {
        // Populando Objeto Usuario
        $usuario = new Usuario;
        $usuario->setCodigo( $_POST['matricula'] );
        $usuario->setSenha( sha1($_POST['senha']) );

        // Recebe o vetor "login", com informações sobre como proceder
        // Ver classes/UsuarioDao.php:82
        $login_info = UsuarioDao::Login($usuario);

        var_dump($login_info);

        if ($login_info[0] != 'fazer_login') {
            header("location:login.php?erro=".$login_info[0]);
        } else {
            session_start();
            $_SESSION['matricula'] = $login_info[1];
            $_SESSION['nome'] = $login_info[2];
            $_SESSION['tipo'] = $login_info[3];
            header("location:index.php");
        }
    } else if ($acao == 'logoff') {
      session_start();
      session_destroy();
      header("location:login.php");
    }elseif($acao == 'verificaMatricula'){
        $matricula = isset($_GET['matricula'])?$_GET['matricula']:0;
        $numUsuario = count(UsuarioDao::Select('matricula', $matricula));
        echo $numUsuario;
    }
?>
