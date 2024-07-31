<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sqlUsuario = "SELECT * FROM ed_usuarios WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

if (!$dadosUsuario) {
    header("location:login");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lista de Faculdades</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <!-- Adicionando CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionando Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Lista de Faculdades</h2>
        <!-- Botão de cadastrar -->
        <div class="row justify-content-end mb-3">
            <div class="col-auto">
                <a href="cadastro-faculdade" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Cadastrar Nova Faculdade
                </a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome da Faculdade</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Número de registros por página
                $registros_por_pagina = 10;

                // Página atual
                $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

                // Calcula o offset
                $offset = ($pagina_atual - 1) * $registros_por_pagina;

                // Consulta SQL para obter os dados com paginação
                $sql = "SELECT * FROM ed_faculdades LIMIT $registros_por_pagina OFFSET $offset";
                $result = $conexao->query($sql);

                if ($result->num_rows > 0) {
                    // Loop para exibir cada faculdade em uma linha da tabela
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td><a href='atualiza-faculdade.php?id=" . $row["id"] . "'><i class='fas fa-edit'></i></a> 
                        <a href='faculdade-dados.php?id=" . $row["id"] . "'><i class='far fa-eye'></i></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhuma faculdade encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <?php
        // Consulta SQL para contar o total de registros
        $sql_total = "SELECT COUNT(*) AS total FROM ed_faculdades";
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