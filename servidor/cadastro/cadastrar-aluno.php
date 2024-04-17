<?php
include "../../classes/Controle.php";

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$dataNascimento = $_POST['data_nascimento'];
$id_faculdade = $_POST['id_curso'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];

// Inicializa a variável $caminhos_arquivos como um array
$caminhos_arquivos = "";

if ($_FILES["arquivos"]) { // Verifica se algum arquivo foi enviado
    // Função para criar o diretório se ele não existir
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
}

$Controle = new Controle();

if ($Controle->cadastrarAluno($nome, $email, $telefone, $dataNascimento, $id_faculdade, $cpf, $rg, $caminhos_arquivos)) {
    echo '<script>alert("Aluno cadastrado com sucesso!"); window.location="../../aluno"; </script>';
} else {
    echo '<script>alert("Erro no cadastro"); window.location="../../cadastro-aluno";</script>';
}