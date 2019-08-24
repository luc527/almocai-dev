<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro do Aluno</title>
</head>
<body>
    <form action="aluno_acao.php" method="post">
        <fieldset>
            <label for="matricula">Matrícula: </label> <br/>
            <input type="text" name="matricula" id="matricula"> <br/><br/>

            <label for="senha">Senha: </label> <br/>
            <input type="password" name="senha" id="senha"> <br/><br/>

            <label for="nome">Nome completo </label> <br/>
            <input type="text" name="nome" id="nome"> <br/><br/>
            <center>
                <button type="submit" name="acao" value="inserir">Cadastrar</button>
            </center>
        </fieldset>
        
    </form>
</body>
</html>