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
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Atualizar Cadastro</h2>
        <?php
        // Verifica se o ID da faculdade foi passado via GET
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $aluno_id = $_GET['id'];

            include "classes/ConexaoBanco.php";

            $conexaobanco = new ConexaoBanco();

            $conexao = $conexaobanco->conectar();

            $sqlcursos = "SELECT * FROM faculdades ORDER BY nome ASC";
            $cursos = mysqli_query($conexao, $sqlcursos);
            $total = mysqli_num_rows($cursos);

            // Consulta SQL para obter os dados do curso com o ID fornecido
            $sql = "SELECT * FROM Cursos WHERE id = $aluno_id";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Exibir o formulário de atualização com os dados preenchidos
                echo "<form action='servidor/cadastro/atualizar-curso.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<div class='form-group'>";
                echo "<label for='nome'>Nome do Curso:</label>";
                echo "<input type='text' class='form-control' id='nome' name='nome' value='" . $row["nome"] . "' required>";
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
                echo "<div class='form-group col-md-6'>";
                echo "<label for='faculdade'>Faculdade:</label>";
                echo "<Select name='id_faculdade' class='form-control' id='id_faculdade'>";
                $id_faculdade = $row["faculdade_id"];
                $sqlFaculdade = "SELECT * FROM faculdades WHERE id = '$id_faculdade'";
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
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Atualizar</button>";
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