<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sqlUsuario = "SELECT * FROM ed_usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

if (!$dadosUsuario) {
    header("location:login-faculdade");
}

// Número de registros por página
$registros_por_pagina = 10;

// Página atual
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcula o offset
$offset = ($pagina_atual - 1) * $registros_por_pagina;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lista de Alunos</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <!-- Adicionando CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionando Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="faculdade-index">PAGINA INICIAL</a>
    </nav>
    <div class="container">
        <h2>Lista de Alunos</h2>
        <!-- Botão de cadastrar -->
        <div class="row justify-content-end mb-3">
            <div class="col-auto">
                <a href="cadastro-aluno" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Cadastrar Novo Aluno
                </a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Curso</th>
                    <th scope="col">Ações</th>
                    <!-- Adicione outras colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlIdFaculdade = "SELECT * FROM ed_faculdades WHERE email= '$email'";
                $resposta = $conexao->query($sqlIdFaculdade);
                $faculdadeDados = $resposta->fetch_assoc();
                $FaculdadeId = $faculdadeDados['id'];

                $sqlCursos = "SELECT * FROM ed_cursos WHERE faculdade_id = '$FaculdadeId'";
                $resultado = $conexao->query($sqlCursos);

                while ($cursosDados = $resultado->fetch_assoc()) {
                    $cursosId = $cursosDados['id'];


                    // Consulta SQL para obter os dados com paginação
                    $sql = "SELECT * FROM ed_alunos WHERE curso_id = '$cursosId' LIMIT $registros_por_pagina OFFSET $offset";
                    $result = $conexao->query($sql);

                    if ($result->num_rows > 0) {
                        // Loop para exibir cada curso em uma linha da tabela
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["nome"] . "</td>";
                            $id_curso = $row["curso_id"];
                            $sqlCurso = "SELECT * FROM ed_cursos WHERE id = '$id_curso'";
                            $curso = mysqli_query($conexao, $sqlCurso);
                            $nomeCurso = mysqli_fetch_assoc($curso);
                            echo "<td>" . $nomeCurso["nome"] . "</td>";
                            echo "<td><a href='atualiza-aluno.php?id=" . $row["id"] . "'><i class='fas fa-edit'></i></a>";
                            $caminho_arquivo = $row["arquivo"];
                            echo "<a style='margin-left: 10px;' href='aluno-dados?id=" . $row["id"] . "'><i class='far fa-eye'></i></a></td>";
                            echo "</tr>";
                        }
                    }
                }
                $sql = "SELECT * FROM ed_alunos";
                $total = $conexao->query($sql);

                if ($total->num_rows === 0) {
                    echo "<tr><td colspan='8'>Nenhum aluno encontrado</td></tr>";
                }

                ?>
            </tbody>
        </table>
        <!-- Paginação -->
        <?php
        // Consulta SQL para contar o total de registros
        $sql_total = "SELECT COUNT(*) AS total FROM ed_alunos";
        $resultado = $conexao->query($sql_total);
        $total_registros = $resultado->fetch_assoc()['total'];

        // Calcula o número total de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Exibe a paginação apenas se houver mais de uma página
        if ($total_paginas > 1) {
            ?>
            <nav aria-label="Navegação de página">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($pagina_atual <= 1)
                        echo 'disabled'; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_atual - 1; ?>" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php
                    // Exibe os links para as páginas
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='?pagina=" . $i . "'>" . $i . "</a></li>";
                    }
                    ?>
                    <li class="page-item <?php if ($pagina_atual >= $total_paginas)
                        echo 'disabled'; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_atual + 1; ?>" aria-label="Próximo">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>
    </div>
</body>

</html>