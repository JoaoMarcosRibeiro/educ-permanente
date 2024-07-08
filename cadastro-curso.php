<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sql = "SELECT * FROM faculdades ORDER BY nome ASC";
$faculdades = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($faculdades);

$sqlFaculdade = "SELECT * FROM faculdades WHERE email = '$email'";
$faculdade = mysqli_query($conexao, $sqlFaculdade);
$dadosFaculdade = mysqli_fetch_assoc($faculdade);

$sqlUsuario = "SELECT * FROM usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Curso</title>
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
        <h2>Cadastro de Curso</h2>
        <form id="cadastro-form" action="servidor/cadastro/cadastrar-curso" method="post">
            <div class="form-group">
                <label for="nome">Nome do Curso:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="nome">Semestre:</label>
                <input type="text" class="form-control" id="semestre" name="semestre" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="duracao">Duração (em meses):</label>
                    <input type="number" class="form-control" id="duracao" name="duracao" required>
                </div>
                <?php if ($dadosUsuario) {
                    echo "<input type='hidden' name='id_faculdade' value='" . $dadosFaculdade['id'] . "'>";
                } else { ?>
                    <div class="form-group col-md-6">
                        <label for="faculdade">Faculdade:</label>
                        <Select name="id_faculdade" class="form-control" id="id_faculdade">
                            <option value=''>Selecione faculdade</option>
                            <?php
                            // se o número de resultados for maior que zero, mostra os dados
                            if ($total > 0) {
                                while ($linha = mysqli_fetch_assoc($faculdades)) {
                                    ?>
                                    <option value='<?= $linha['id'] ?>'><?= $linha['nome'] ?></option>
                                <?php }
                            } ?>
                        </Select>
                    </div>
                <?php } ?>
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group mr-3">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" onclick="history.go(-1);">VOLTAR</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>