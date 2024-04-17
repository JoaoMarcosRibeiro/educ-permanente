<?php
include "../../classes/Controle.php";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos necessários foram enviados
    if (isset($_POST['id']) && isset($_POST['cpf']) && isset($_FILES["arquivos"])) {
        // Recupera os dados do formulário
        $id = $_POST['id'];
        $cpf = $_POST['cpf'];

        // Inicializa a variável $caminhos_arquivos como uma string vazia
        $caminhos_arquivos = "";

        // Define a função para criar um diretório se ele não existir
        function criarDiretorio($caminho)
        {
            if (!is_dir($caminho)) {
                mkdir($caminho, 0777, true); // Cria o diretório com permissões de leitura, escrita e execução
            }
        }

        // Define o diretório de destino para salvar os arquivos
        $diretorio_destino = "arquivos/" . $cpf . "/";
        // Cria o diretório se ele não existir
        criarDiretorio($diretorio_destino);

        // Loop para processar cada arquivo enviado
        foreach ($_FILES["arquivos"]["tmp_name"] as $key => $tmp_name) {
            // Obtém o nome original do arquivo enviado
            $nome_arquivo = $_FILES["arquivos"]["name"][$key];
            // Define o caminho completo do arquivo no servidor
            $caminho_arquivo = $diretorio_destino . $nome_arquivo;

            // Move o arquivo para o destino desejado
            if (move_uploaded_file($tmp_name, $caminho_arquivo)) {
                // Adiciona o caminho do arquivo ao final da string $caminhos_arquivos, separado por vírgula
                $caminhos_arquivos .= $caminho_arquivo . ",";
            } else {
                echo "Erro ao mover o arquivo para o diretório de destino.";
            }
        }

        // Instancia o objeto de controle
        $Controle = new Controle();

        // Chama a função para adicionar os arquivos ao aluno no banco de dados
        if ($Controle->arquivoAluno($id, $caminhos_arquivos)) {
            // Redireciona para a página de dados do aluno em caso de sucesso
            header("Location: ../../aluno-dados?id=".$id);
            exit(); // Encerra o script para evitar a execução de código adicional
        } else {
            // Redireciona para a página de dados do aluno com mensagem de erro em caso de falha
            header("Location: ../../aluno-dados?id=".$id."&error=true");
            exit(); // Encerra o script para evitar a execução de código adicional
        }
    } else {
        // Redireciona para a página de dados do aluno com mensagem de erro em caso de campos ausentes
        header("Location: ../../aluno-dados?id=".$id."&error=true");
        exit(); // Encerra o script para evitar a execução de código adicional
    }
} else {
    // Redireciona para a página de dados do aluno em caso de acesso direto ao script sem submissão do formulário
    header("Location: ../../aluno-dados?id=".$id);
    exit(); // Encerra o script para evitar a execução de código adicional
}
