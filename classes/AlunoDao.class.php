<?php 
    require_once "autoload.php";

    class AlunoDao{
        public static $instance;

        public static function getInstance() {
            if (!isset(self::$instance))
                self::$instance = new DaoUsuario();
     
            return self::$instance;
        }

        public function Inserir(Aluno $aluno){
            try{
                $sql = "INSERT INTO Aluno (
                    matricula,
                    senha,
                    nome)
                    VALUES(
                    :matricula,
                    :senha,
                    :nome)";

                $p_sql = Conexao::conexao()->prepare($sql);

                $p_sql->bindValue(":matricula", $aluno->getCodigo());
                $p_sql->bindValue(":senha", $aluno->getSenha());
                $p_sql->bindValue(":nome", $aluno->getNome());

                return $p_sql->execute();
            } catch (Exception $e){
                print "Ocorreu um erro ao tentar executar esta ação<br> ". $e->
                getCode() . " Mensagem: " . $e->getMessage();
            }
        }
    }
?>