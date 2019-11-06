<?php

    class IntoleranciaUsuario extends AbsCodigo {
        private $intolerancia;
        private $estado;
        private $documento;

        public function setIntolerancia(Intolerancia $intolerancia){
            $this->intolerancia = $intolerancia;
        }
        
        public function setDocumento($documento){
            $this->documento = $documento;
        }

        public function setEstado(IntoleranciaEstado $e) {
            $this->estado = $e;
        }
        public function getEstado() { return $this->estado; }

        public function getIntolerancia(){
            return $this->intolerancia;
        }

        public function getDocumento(){
            return $this->documento;
        }
        
    }
?>