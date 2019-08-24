<?php 
    require_once "autoload.php";

    class AlunoDao {
        public static function Inserir(Aluno $aluno){
            try {
                $sql = "INSERT INTO Aluno (
                    matricula,
                    senha,
                    nome)
                    VALUES(
                    :matricula,
                    :senha,
                    :nome)";

                $stmt = Conexao::conexao()->prepare($sql);

                $stmt->bindValue(":matricula", $aluno->getCodigo());
                $stmt->bindValue(":senha", $aluno->getSenha());
                $stmt->bindValue(":nome", $aluno->getNome());

                return $stmt->execute();
            } catch (Exception $e){
                print "Ocorreu um erro ao tentar executar esta ação<br> ". $e->
                getCode() . " Mensagem: " . $e->getMessage();
            }
        }
    }
?>