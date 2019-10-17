<?php
    class Upload {
        private $name; //name do input que o usuário colocará a imagem
        private $pasta; //nome da pasta que receberá a imagem
        private $nome_substituto; //nome que irá sobrescrever o nome da imagem atual
        private $permite; //Tipo de imagem permitida, ex:png,jpg,gif,pjpeg,jpeg
        // pinto pintoprivate static $tipos_permitidos = ['']

        /**
         * 
         */
        public function uploadImagem ($name_imagem,$pasta_destino)
        {
            if (!empty($_FILES[$name_imagem]['tmp_name'])) {
                // pega o nome do arquivo enviado
                $this->name = $_FILES[$name_imagem];
                // define o a extensão permitida
                $tipo_permitido = ['pdf'];
                // define a pasta para onde vai o arquivo
                $this->pasta = $pasta_destino;

                $nome = $this->name['name'];
                // extensão do arquivo
                $extensao = end(explode(".",$this->name['name']));
                // nome gerado a cada segundo
                $this->nome_substituto = md5(time());
                // caminho do arquivo
                $upload_arquivo = $this->pasta.$this->nome_substituto.".".$extensao;
                foreach ($tipo_permitido as $key => $tipo) {
                    $this->permite[] = $tipo;
                }

                // verifica se o nome não é vazio e se a entensão é permitida
                if(!empty($nome) and in_array($this->name['type'],$this->permite)) { 
                    // verifica de o arquivo foi movido para pasta
                    if(move_uploaded_file($this->name['tmp_name'], $upload_arquivo)) {
                        return $upload_arquivo;
                    } else {
                        return "erro ao enviar a imagem";
                    }
                } else{
                    //faça algo caso não seja a extensão permitida
                    return "formato de imagem não aceito pelo sistema.";
                }

        }
        }
        
}
?>