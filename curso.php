<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lista de Cursos</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <!-- Adicionando CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionando Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Lista de Cursos</h2>
        <!-- Botão de cadastrar -->
        <div class="row justify-content-end mb-3">
            <div class="col-auto">
                <a href="cadastro-curso" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Cadastrar Novo Curso
                </a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Faculdade</th>
                    <th scope="col">Ações</th>
                    <!-- Adicione outras colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php
                include "classes/ConexaoBanco.php";

                $conexaobanco = new ConexaoBanco();

                $conexao = $conexaobanco->conectar();

                // Número de registros por página
                $registros_por_pagina = 10;

                // Página atual
                $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

                // Calcula o offset
                $offset = ($pagina_atual - 1) * $registros_por_pagina;

                // Consulta SQL para obter os dados com paginação
                $sql = "SELECT * FROM Cursos LIMIT $registros_por_pagina OFFSET $offset";
                $result = $conexao->query($sql);

                if ($result->num_rows > 0) {
                    // Loop para exibir cada curso em uma linha da tabela
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["nome"] . "</td>";
                        $id_faculdade = $row["faculdade_id"];
                        $sqlFaculdade = "SELECT * FROM faculdades WHERE id = '$id_faculdade'";
                        $faculdade = mysqli_query($conexao, $sqlFaculdade);
                        $nomeFaculdade = mysqli_fetch_assoc($faculdade);
                        echo "<td>" . $nomeFaculdade["nome"] . "</td>";
                        echo "<td><a href='atualiza-curso.php?id=" . $row["id"] . "'><i class='fas fa-edit'></i></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhum curso encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>