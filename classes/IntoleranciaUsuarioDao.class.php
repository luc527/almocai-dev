<?php
    require_once("Upload.class.php");
    require_once("IntoleranciaUsuario.class.php");
    require_once("Conexao.class.php");
    require_once("StatementBuilder.class.php");
    class IntoleranciaUsuarioDao{

        /**
         * Recebe um objeto IntoleranciaUsuario e insere seus dados no banco de dados na tabela correspondente
         * @param IntoleranciaUsuario $intoleranciaUsuario
         * @param string $nome 
         * 
         * @return 
         */
        public static function Inserir(IntoleranciaUsuario $intoleranciaUsuario, $nome_arquivo, $matricula)
        {
            // pasta de destino do arquivo
            $pasta_destino = "../arquivos/Intolerancia/";
            $Upload = new Upload;
            // faz o upload do arquivo e coloca o caminho no atributo documento
            $intoleranciaUsuario->setDocumento($Upload->uploadImagem($nome_arquivo, $pasta_destino));
            if(!is_array($intoleranciaUsuario->getDocumento()))
            return StatementBuilder::insert("INSERT INTO Usuario_Intolerancia value (:usuario_matricula, :intolerancia_id, :documento)", 
            [
                'usuario_matricula' => $matricula,
                'intolerancia_id' => $intoleranciaUsuario->getIntolerancia()->getCodigo(),
                'documento' => $intoleranciaUsuario->getDocumento()
            ]);
        }         
    }
?>