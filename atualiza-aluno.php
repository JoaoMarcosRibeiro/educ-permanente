<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sqlFaculdade = "SELECT * FROM faculdades WHERE email = '$email'";
$faculdade = mysqli_query($conexao, $sqlFaculdade);
$dadosFaculdade = mysqli_fetch_assoc($faculdade);

$sqlUsuario = "SELECT * FROM usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

if ($dadosFaculdade) {
    $idFaculdade = $dadosFaculdade['id'];
    $sql = "SELECT * FROM cursos WHERE faculdade_id = '$idFaculdade' ORDER BY nome ASC ";
} else {
    $sql = "SELECT * FROM cursos ORDER BY nome ASC";
}

$cursos = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($cursos);

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
        // Verifica se o ID do aluno foi passado via GET
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $aluno_id = $_GET['id'];

            // Consulta SQL para obter os dados do aluno com o ID fornecido
            $sqlAlunos = "SELECT * FROM Alunos WHERE id = $aluno_id";
            $result = $conexao->query($sqlAlunos);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Exibir o formulário de atualização com os dados preenchidos
                echo "<form action='servidor/cadastro/atualizar-aluno.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<div class='form-group'>";
                echo "<label for='nome'>Nome do Aluno:</label>";
                echo "<input type='text' class='form-control' id='nome' name='nome' value='" . $row["nome"] . "' required>";
                echo "</div>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='cpf'>CPF:</label>";
                echo "<input type='text' class='form-control' id='cpf' name='cpf' value='" . $row["cpf"] . "' required>";
                echo "</div>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='rg'>RG:</label>";
                echo "<input type='text' class='form-control' id='rg' name='rg' value='" . $row["rg"] . "' required>";
                echo "</div>";
                echo "</div>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='data_nascimento'>Data de nascimento:</label>";
                echo "<input type='date' class='form-control' id='data_nascimento' name='data_nascimento' value='" . $row["data_nascimento"] . "' required>";
                echo "</div>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='curso'>Curso:</label>";
                echo "<Select name='id_curso' class='form-control' id='id_curso'>";
                $id_curso = $row["curso_id"];
                $sqlCurso = "SELECT * FROM cursos WHERE id = '$id_curso'";
                $curso = mysqli_query($conexao, $sqlCurso);
                $nomeCurso = mysqli_fetch_assoc($curso);
                echo "<option value='" . $row["curso_id"] . "'>" . $nomeCurso['nome'] . "-" . $nomeCurso['semestre'] . "</option>";
                // se o número de resultados for maior que zero, mostra os dados
                if ($total > 0) {
                    while ($linha = mysqli_fetch_assoc($cursos)) {
                        $idFaculdade = $linha['faculdade_id'];
                        $sqlFaculdade = "SELECT * FROM faculdades WHERE id = '$idFaculdade'";
                        $faculdade = mysqli_query($conexao, $sqlFaculdade);
                        $dadosFaculdade = mysqli_fetch_assoc($faculdade);
                        $idCurso = $linha['id'];
                        $nomeCurso = $linha['nome'];
                        $semestre = $linha['semestre'];

                        echo "<option value='$idCurso'>
                                    $nomeCurso-$semestre
                                </option>";
                    }
                }
                echo "</Select>";
                echo "</div>";
                echo "</div>";
                echo "<fieldset>";
                echo "<legend class='form-group'>Contato:</legend>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='telefone'>Telefone:</label>";
                echo "<input type='text' class='form-control' id='telefone' name='telefone' value='" . $row["telefone"] . "' required>";
                echo "</div>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='email'>E-mail:</label>";
                echo "<input type='email' class='form-control' id='email' name='email' value='" . $row["email"] . "' required>";
                echo "</div>";
                echo "</div>";
                echo "</fieldset>";
                echo "<div class='form-row justify-content-center mt-4'>";
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