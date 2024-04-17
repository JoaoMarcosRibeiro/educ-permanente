<?php
include "ConexaoBanco.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se foi recebido o caminho do arquivo a ser removido
    if (isset($_POST['arquivo']) && isset($_POST['id'])) {
        $arquivo = $_POST['arquivo'];
        $id = $_POST['id'];

        // Verifica se o arquivo existe no servidor
        if (file_exists($arquivo)) {
            // Remove o arquivo do servidor
            if (unlink($arquivo)) {
                // Instancia o objeto de controle
                $Controle = new Controle();

                if ($Controle->removeArquivoFaculdade($id, $arquivo)) {
                    header("Location: ../../faculdade-dados?id=" . $id);
                    exit(); // Encerra o script para evitar a execução de código adicional
                }
                
            }
        }
    }
}
