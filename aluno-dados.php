<?php
include "classes/ConexaoBanco.php";

// Recupera o ID do aluno da URL
$id_aluno = $_GET['id'];

// Conecta ao banco de dados
$conexao_banco = new ConexaoBanco();
$conexao = $conexao_banco->conectar();

// Consulta para recuperar os dados do aluno
$sql_aluno = "SELECT * FROM alunos WHERE id = $id_aluno";
$resultado_aluno = mysqli_query($conexao, $sql_aluno);
$aluno = mysqli_fetch_assoc($resultado_aluno);

// Verifica se há arquivos associados ao aluno
if (!empty($aluno['arquivo'])) {
    // Converte a string de arquivos em um array
    $arquivos = explode(",", $aluno['arquivo']);
} else {
    // Define um array vazio se não houver arquivos associados
    $arquivos = [];
}

$id_curso = $aluno["curso_id"];
$sqlCurso = "SELECT * FROM cursos WHERE id = '$id_curso'";
$curso = mysqli_query($conexao, $sqlCurso);
$nomeCurso = mysqli_fetch_assoc($curso);

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
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h1 class="mt-4">Dados do Aluno</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user mr-2"></i><strong>Nome:</strong>
                    <?php echo $aluno['nome']; ?>
                </h5>
                <p class="card-text"><i class="fas fa-id-card mr-2"></i><strong>CPF:</strong>
                    <?php echo $aluno['cpf']; ?>
                </p>
                <p class="card-text"><i class="fas fa-address-card mr-2"></i><strong>RG:</strong>
                    <?php echo $aluno['rg']; ?>
                <p class="card-text"><i class="fas fa-envelope mr-2"></i><strong>Email:</strong>
                    <?php echo $aluno['email']; ?>
                </p>
                <p class="card-text"><i class="fas fa-phone mr-2"></i><strong>Telefone:</strong>
                    <?php echo $aluno['telefone']; ?>
                </p>
                </p>
                <p class="card-text"><i class="fas fa-birthday-cake mr-2"></i><strong>Data de Nascimento:</strong>
                    <?php echo $aluno['data_nascimento']; ?>
                </p>
                <p class="card-text"><i class="fas fa-graduation-cap mr-2"></i><strong>Curso:</strong>
                    <?php echo $nomeCurso['nome']; ?>
                </p>
            </div>
        </div>

        <h1 class="mt-4">Arquivos Associados</h1>
        <form id="cadastro-form" enctype="multipart/form-data">
            <div class="input-group">
                <input type="hidden" name="id" value="<?= $id_aluno ?>">
                <input type="hidden" name="cpf" value="<?= $aluno['cpf']; ?>">
                <input style="padding: 0px; padding-top: 3px;" type="file" class="form-control" id="arquivos"
                    name="arquivos[]" accept=".pdf" multiple>
                <button class="btn btn-primary" type="submit">Enviar arquivo</button>
            </div>
        </form>
        <ul class="list-group mt-3" id="lista-arquivos">
            <?php
            // Verifica se o aluno tem arquivos associados
            if (!empty($arquivos)) {
                // Itera sobre os arquivos associados ao aluno
                foreach ($arquivos as $arquivo) {
                    // Explode o caminho do arquivo para acessar o nome do arquivo (supondo que o nome do arquivo seja o último elemento após a separação por '/')
                    $nome_arquivo = explode("/", $arquivo);
                    echo "<li class='list-group-item'><i class='far fa-file mr-2'></i><a href='servidor/cadastro/" . $arquivo . "' target='_blank'>{$nome_arquivo[count($nome_arquivo) - 1]}</a></li>";
                }
            } else {
                echo "<li class='list-group-item'>Nenhum arquivo associado a este aluno.</li>";
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
                    url: 'servidor/cadastro/adiciona-arquivo-aluno.php', // Define o URL de destino
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
    </script>
</body>

</html>