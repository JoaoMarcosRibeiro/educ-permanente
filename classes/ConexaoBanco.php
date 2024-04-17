<?php
class ConexaoBanco
{
    public $servidor = '130.130.1.9';
    public $usuario = 'homologa';
    public $password = 'daskol@150';
    public $database = 'homologa';
    public $port = 3306;

    public function conectar()
    {
        return mysqli_connect(
            $this->servidor,
            $this->usuario,
            $this->password,
            $this->database,
            $this->port
        );
    }
}

?>