<?php
    $acao = isset($_POST['acao'])?$_POST['acao']:$_GET['acao'];
    require_once "autoload.php";
    if($acao == "inserir"){
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
    }
?>