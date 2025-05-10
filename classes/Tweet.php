<?php

    class Tweet {
        public $nombreUsuario;
        public $fotoPerfilUsuario;
        public $contenido;
        public $fechaHora;
    
        public function __construct($nombreUsuario, $fotoPerfilUsuario, $contenido, $fechaHora) {
            $this->nombreUsuario = $nombreUsuario;
            $this->fotoPerfilUsuario = $fotoPerfilUsuario;
            $this->contenido = $contenido;
            $this->fechaHora = $this->formatearFechaHora($fechaHora);
        }

        private function formatearFechaHora($fechaHora) {
            $fechaTweetFormateada = new DateTime($fechaHora);
            return $fechaTweetFormateada->format('H:i - d/m/Y');
        }
    
        public function __toString() {
            return 
                '<div class="tweet">
                    <div class="col1">
                        <div class="tweet-usuario-foto">
                            <img src="' . $this->fotoPerfilUsuario . '" alt="Foto de perfil de @' . $this->nombreUsuario . '">
                        </div>
                    </div>
                    <div class="col2">
                        <span> @' . $this->nombreUsuario . '</span>
                        <p>' . $this->contenido . '</p>
                        <span>' . $this->fechaHora . '</span>
                    </div>
                </div>';
        }
    }

?>