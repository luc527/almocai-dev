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

        // Populando Aluno
        $aluno = new Aluno;
        $aluno->setCodigo($matricula);
        $aluno->setSenha($senha);
        $aluno->setNome($nome);
        
        // Inserindo Aluno
        $alunoDao = new AlunoDao;
        $alunoDao->Inserir($aluno);
        #header("aluno_cadastro.php");
    } else if ($acao == 'Login') {
        $aluno = new Usuario;
        $aluno->setCodigo( $_POST['matricula'] );
        $aluno->setSenha( sha1($_POST['senha']) );

        $login = UsuarioDao::Login($aluno);

        var_dump($login);

        if ($login[0] != 'fazer_login') {
            header("location:login.php?erro=".$login[0]);
        } else {
            session_start();
            $_SESSION['aluno_matricula'] = $login[1];
            $_SESSION['aluno_nome'] = $login[2];
            header("location:index.php");
        }
    }

    elseif($acao == 'loginoff'){
        session_start();
        session_destroy();
        header("location:login.php");
    }
?>