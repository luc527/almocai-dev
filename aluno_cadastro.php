<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro do Aluno</title>
</head>
<body>
    <form action="aluno_acao.php" method="post">
        <fieldset>
            <label for="matricula">Matricula</label>
            <input type="text" name="matricula" id="matricula"><br>
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha"><br>
            <label for="nome">Nome(completo)</label>
            <input type="text" name="nome" id="nome"><br>
            <center>
                <button type="submit" name="acao" value="inserir">Cadastrar</button>
            </center>
        </fieldset>
        
    </form>
</body>
</html>