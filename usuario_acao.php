<?php
    require_once "autoload.php";

    if (isset($_POST['acao'])) $acao = $_POST['acao'];
    else if (isset($_GET['acao'])) $acao = $_GET['acao'];
    else $acao = '';

    if ($acao == "inserir") {
        // Adquirindo campos do formulário HTML
        $matricula = isset($_POST['matricula'])?$_POST['matricula']:"";
        $senha = isset($_POST['senha'])?sha1($_POST['senha']):"";
        $nome = isset($_POST['nome'])?$_POST['nome']:"";
        $tipo_cod = isset($_POST['tipo']) ? $_POST['tipo'] : 3; //3: tipo do aluno

        // Populando Objeto Tipo do usuário
        $tipo = new TipoUsuario;
        $tipo->setCodigo($tipo_cod);

        // Populando Objeto Usuario
        $usuario = new Usuario;
        $usuario->setCodigo($matricula);
        $usuario->setSenha($senha);
        $usuario->setNome($nome);
        $usuario->setTipo($tipo);

        // Inserindo Usuario no BD
        UsuarioDao::Inserir($usuario);
        #header("aluno_cadastro.php");
    } else if ($acao == 'Login') {
        // Populando Objeto Usuario
        $usuario = new Usuario;
        $usuario->setCodigo( $_POST['matricula'] );
        $usuario->setSenha( sha1($_POST['senha']) );

        // Recebe o vetor "login", com informações sobre como proceder
        // Ver classes/UsuarioDao.php:82
        $login = UsuarioDao::Login($usuario);

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
