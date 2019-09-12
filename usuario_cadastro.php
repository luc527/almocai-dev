<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro do Aluno</title>
    <script src="js/ajax.js"></script>
</head>
<body>
    <h1 id='erro'></h1>
    <form action="usuario_acao.php" method="post" id="form-cadastro-usr">
            <input type="hidden" id="acao" value="verificaMatricula">
            <label for="matricula">Matrícula: </label> <br/>
            <input type="text" name="matricula" id="matricula"> <br/><br/>
            <label for="senha">Senha: </label> <br/>
            <input type="password" name="senha" id="senha"> <br/><br/>

            <label for="nome">Nome completo </label> <br/>
            <input type="text" name="nome" id="nome"> <br/><br/>

            <button type="submit" name="acao" value="inserir">Cadastrar</button>

    </form>
</body>
</html>
