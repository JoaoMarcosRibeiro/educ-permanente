<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sqlFaculdade = "SELECT * FROM ed_faculdades WHERE email = '$email'";
$faculdade = mysqli_query($conexao, $sqlFaculdade);
$dadosFaculdade = mysqli_fetch_assoc($faculdade);

$sqlUsuario = "SELECT * FROM ed_usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Atualizar Cadastro</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h2>Atualizar Cadastro</h2>
        <?php
        // Verifica se o ID da faculdade foi passado via GET
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $aluno_id = $_GET['id'];

            $sqlcursos = "SELECT * FROM ed_faculdades ORDER BY nome ASC";
            $cursos = mysqli_query($conexao, $sqlcursos);
            $total = mysqli_num_rows($cursos);

            // Consulta SQL para obter os dados do curso com o ID fornecido
            $sql = "SELECT * FROM ed_cursos WHERE id = $aluno_id";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Exibir o formulário de atualização com os dados preenchidos
                echo "<form action='servidor/cadastro/atualizar-curso.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                if ($dadosUsuario) {
                    echo "<input type='hidden' name='email' value='" . $email . "'>";
                }
                echo "<div class='form-group'>";
                echo "<label for='nome'>Nome do Curso:</label>";
                echo "<input type='text' class='form-control' id='nome' name='nome' value='" . $row["nome"] . "' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='nome'>Semestre:</label>";
                echo "<input type='text' class='form-control' id='semestre' name='semestre' value='" . $row["semestre"] . "' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='descricao'>Descrição:</label>";
                echo "<textarea type='text' class='form-control' id='descricao' name='descricao' value='" . $row["descricao"] . "' required>" . $row["descricao"] . "</textarea>";
                echo "</div>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='duracao'>Duração (em meses):</label>";
                echo "<input type='number' class='form-control' id='duracao' name='duracao' value='" . $row["duracao"] . "' required>";
                echo "</div>";
                if ($dadosUsuario) {
                    echo "<input type='hidden' name='id_faculdade' value='" . $dadosFaculdade['id'] . "'>";
                } else {
                    echo "<div class='form-group col-md-6'>";
                    echo "<label for='faculdade'>Faculdade:</label>";
                    echo "<Select name='id_faculdade' class='form-control' id='id_faculdade'>";
                    $id_faculdade = $row["faculdade_id"];
                    $sqlFaculdade = "SELECT * FROM ed_faculdades WHERE id = '$id_faculdade'";
                    $faculdade = mysqli_query($conexao, $sqlFaculdade);
                    $nomeFaculdade = mysqli_fetch_assoc($faculdade);
                    echo "<option value='" . $row["faculdade_id"] . "'>" . $nomeFaculdade['nome'] . "</option>";
                    if ($total > 0) {
                        while ($linha = mysqli_fetch_assoc($cursos)) {
                            echo "<option value='" . $linha["id"] . "'>" . $linha["nome"] . "</option>";
                        }
                    }
                    echo "</Select>";
                    echo "</div>";
                }
                echo "</div>";
                echo "<div class='form-row justify-content-center'>";
                echo "<div class='form-group mr-3'>";
                echo "<button type='submit' class='btn btn-primary'>ATUALIZAR</button>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<a class='btn btn-danger' onclick='history.go(-1);'>VOLTAR</a>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
            } else {
                echo "Nenhum curso encontrado com o ID fornecido.";
            }
            $conexao->close();
        } else {
            echo "ID do curso não fornecido.";
        }
        ?>
    </div>
</body>

</html>