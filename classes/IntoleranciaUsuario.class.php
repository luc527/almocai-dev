<?php

    class IntoleranciaUsuario{
        private $intolerancia;
        private $documento;

        public function setIntolerancia(Intolerancia $intolerancia){
            $this->intolerancia = $intolerancia;
        }
        
        public function setDocumento($documento){
            $this->documento = $documento;
        }

        public function getIntolerancia(){
            return $this->intolerancia;
        }

        public function getDocumento(){
            return $this->documento;
        }
        
    }
?>