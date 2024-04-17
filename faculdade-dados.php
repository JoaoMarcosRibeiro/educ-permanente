<?php
include "classes/ConexaoBanco.php";

// Recupera o ID doa faculdade da URL
$id = $_GET['id'];

// Conecta ao banco de dados
$conexao_banco = new ConexaoBanco();
$conexao = $conexao_banco->conectar();

// Consulta para recuperar os dados da faculdade
$sql = "SELECT * FROM faculdades WHERE id = $id";
$resultado = mysqli_query($conexao, $sql);
$faculdade = mysqli_fetch_assoc($resultado);


if (!empty($faculdade['arquivo'])) {
    // Converte a string de arquivos em um array
    $arquivos = explode(",", $faculdade['arquivo']);
} else {
    // Define um array vazio se não houver arquivos associados
    $arquivos = [];
}



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Aluno</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <!-- Adicionando CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionando Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Adicione seu CSS personalizado aqui */
        body {
            background-color: #f8f9fa;

        }

        h1 {
            text-align: center;
        }

        .container {
            text-align: left;
        }

        .card {
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-left: 0px;
        }

        .list-group-item {
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.125);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="faculdade-index">PAGINA INICIAL</a>
    </nav>
    <div class="container">
        <h1 class="mt-4">Meus dados</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user mr-2"></i><strong>Nome da Faculdade:</strong>
                    <?php echo $faculdade['nome']; ?>
                </h5>
                <p class="card-text"><i class="fas fa-id-card mr-2"></i><strong>CNPJ:</strong>
                    <?php echo $faculdade['cnpj']; ?>
                </p>
                <p class="card-text"><i class="fas fa-envelope mr-2"></i><strong>Email:</strong>
                    <?php echo $faculdade['email']; ?>
                </p>
                <p class="card-text"><i class="fas fa-phone mr-2"></i><strong>Telefone:</strong>
                    <?php echo $faculdade['telefone']; ?>
                </p>
            </div>
        </div>
        <h1 class="mt-4">Arquivos Associados</h1>
        <form id="cadastro-form" enctype="multipart/form-data">
            <div class="input-group">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="cnpj" value="<?= $faculdade['cnpj']; ?>">
                <input style="padding: 0px; padding-top: 3px;" type="file" class="form-control" id="arquivos"
                    name="arquivos[]" accept=".pdf" multiple>
                <button class="btn btn-primary" type="submit">Enviar arquivo</button>
            </div>
        </form>
        <ul class="list-group mt-3" id="lista-arquivos">
            <?php
            // Verifica se o faculdade tem arquivos associados
            if (!empty($arquivos)) {
                // Itera sobre os arquivos associados à faculdade
                foreach ($arquivos as $arquivo) {
                    // Explode o caminho do arquivo para acessar o nome do arquivo (supondo que o nome do arquivo seja o último elemento após a separação por '/')
                    $nome_arquivo = explode("/", $arquivo);
                    echo "<li class='list-group-item'>
                            <i class='far fa-file mr-2'></i>
                            <a href='servidor/cadastro/" . $arquivo . "' target='_blank'>{$nome_arquivo[count($nome_arquivo) - 1]}</a>
                            <span class='remove-file-btn' data-file='{$arquivo}'><i class='fas fa-trash-alt'></i></span>
                          </li>";
                }
            } else {
                echo "<li class='list-group-item'>Nenhum arquivo associado a esta faculdade.</li>";
            }
            ?>
        </ul>
    </div>
    

    <!-- Adicionando jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Adicionando script de atualização de div -->
    <script>
        $(document).ready(function () {
            $('#cadastro-form').submit(function (e) {
                e.preventDefault(); // Impede o envio do formulário padrão

                var formData = new FormData($(this)[0]); // Obter dados do formulário

                $.ajax({
                    url: 'servidor/cadastro/adiciona-arquivo-faculdade.php', // Define o URL de destino
                    type: 'POST', // Define o método de envio como POST
                    data: formData, // Define os dados a serem enviados
                    processData: false, // Impede que o jQuery processe os dados
                    contentType: false, // Impede que o jQuery defina o tipo de conteúdo
                    success: function (response) {
                        // Exibir mensagem de sucesso ou erro
                        alert('Arquivo adicionado com sucesso');
                        // Limpar o campo de seleção de arquivo
                        $('#arquivo').val('');
                        // Recarregar a lista de arquivos após o envio bem-sucedido
                        $('#lista-arquivos').load(location.href + ' #lista-arquivos');
                    },
                    error: function (xhr, status, error) {
                        // Exibir mensagem de erro
                        alert('Erro ao enviar arquivo: ' + error);
                    }
                });
            });
        });

        $(document).ready(function () {
            // Função para remover o arquivo
            $(document).on('click', '.remove-file-btn', function () {
                var arquivo = $(this).data('file'); // Obtém o caminho do arquivo                
                var confirmar = confirm('Tem certeza de que deseja excluir este arquivo?');                
                if (confirmar) {
                    $.ajax({
                        url: 'servidor/cadastro/remove-arquivo-faculdade.php', // Define o URL de destino
                        type: 'POST', // Define o método de envio como POST
                        data: { arquivo: arquivo, id: <?= $id ?> }, // Define os dados a serem enviados                        
                        success: function (response) {
                            // Exibir mensagem de sucesso
                            alert('Arquivo removido com sucesso');
                            // Recarregar a lista de arquivos após a remoção bem-sucedida
                            $('#lista-arquivos').load(location.href + ' #lista-arquivos');
                        },
                        error: function (xhr, status, error) {
                            // Exibir mensagem de erro
                            alert('Erro ao remover arquivo: ' + error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>