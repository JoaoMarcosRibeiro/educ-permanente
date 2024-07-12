<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

// Recupera o ID do aluno da URL
$id_aluno = $_GET['id'];

// Conecta ao banco de dados
$conexao_banco = new ConexaoBanco();
$conexao = $conexao_banco->conectar();

$sqlUsuario = "SELECT * FROM usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

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
$idCurso = $nomeCurso['id'];

// Consulta para recuperar os dados da faculdade
$sql = "SELECT * FROM faculdades WHERE id = '$idCurso'";
$resultado = mysqli_query($conexao, $sql);
$faculdade = mysqli_fetch_assoc($resultado);

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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <?php if ($dadosUsuario) { ?>
            <a class="navbar-brand" href="faculdade-index">PAGINA INICIAL</a>
        <?php } else { ?>
            <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
        <?php } ?>
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
                    <?php echo date('d/m/Y', strtotime($aluno['data_nascimento'])); ?>
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
            if (!empty($arquivos)) {
                // Obtém o número total de arquivos
                $total_arquivos = count($arquivos);
                // Itera sobre os arquivos associados à faculdade
                foreach ($arquivos as $index => $arquivo) {
                    // Ignora a exibição do último elemento na lista
                    if ($index === $total_arquivos - 1) {
                        continue;
                    }
                    // Explode o caminho do arquivo para acessar o nome do arquivo (supondo que o nome do arquivo seja o último elemento após a separação por '/')
                    $nome_arquivo = explode("/", $arquivo);
                    echo "<li class='list-group-item'>
                    <i class='far fa-file mr-2'></i>
                    <a href='servidor/cadastro/" . $arquivo . "' target='_blank'>{$nome_arquivo[count($nome_arquivo) - 1]}</a>";
                    if (!$dadosUsuario) {
                        echo " <span class='remove-file-btn' data-file='{$arquivo}'><i class='fas fa-trash-alt'></i></span>";
                    }
                    echo "</li>";
                }
            } else {
                echo "<li class='list-group-item'>Nenhum arquivo associado a este aluno.</li>";
            }
            ?>
        </ul>
        <div class="editor-wrapper" <?php if ($dadosUsuario) { ?> style="display:none" <?php } ?>>
            <h1 class="mb-4  text-center">Resposta</h1>
            <form action="servidor/cadastro/resposta" method="POST" onsubmit="return updateEditorContent();"
                style="max-width: 100%;">
                <div id="editor-container" style=" height: 200px; border: 1px solid #ced4da; border-radius: .25rem;">
                    <!-- O editor de texto será renderizado aqui -->
                </div>
                <input type='hidden' name='id' value='<?php $faculdade['id'] ?>'>
                <input type="hidden" name="editor-content" id="editor-content">
                <input type='hidden' name='email' value='<?php $faculdade['email'] ?>'>

                <div class="form-row justify-content-center mt-4">
                    <div class="form-group mr-3">
                        <button type="submit" class="btn btn-primary">ENVIAR</button>
                    </div>
                    <div class="form-group">
                        <a class="btn btn-danger" onclick="history.go(-1);">VOLTAR</a>
                    </div>
                </div>
            </form>
        </div>
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

            $(document).on('click', '.remove-file-btn', function () {
                var arquivo = $(this).data('file'); // Obtém o nome do arquivo
                var id_aluno = <?php echo $id_aluno; ?>; // Obtém o ID do aluno
                var confirmar = confirm('Tem certeza de que deseja excluir este arquivo?');
                if (confirmar) {
                    $.ajax({
                        url: 'servidor/cadastro/remove-arquivo-aluno.php',
                        type: 'POST',
                        data: { arquivo: arquivo, id: id_aluno }, // Envia o nome do arquivo e o ID do aluno
                        success: function (response) {
                            if (response === true) {
                                console.log("Arquivo removido com sucesso.");
                            } else {
                                console.error("Erro ao remover arquivo:", response);
                            }
                            // Recarrega a lista de arquivos após a remoção bem-sucedida
                            $('#lista-arquivos').load(location.href + ' #lista-arquivos');
                        },
                        error: function (xhr, status, error) {
                            alert('Erro ao remover arquivo: ' + error);
                        }
                    });
                }
            });
        });
    </script>

    <!-- Inclua o JavaScript do Bootstrap e do Quill -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Inicialize o Quill no elemento com id 'editor-container'
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Escreva seu texto aqui...'
        });

        // Função para atualizar o conteúdo do editor no campo oculto
        function updateEditorContent() {
            var html = quill.root.innerHTML;
            document.getElementById('editor-content').value = html;
            return true;
        }
    </script>
</body>

</html>