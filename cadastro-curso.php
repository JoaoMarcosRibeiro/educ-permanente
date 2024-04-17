<?php
include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sql = "SELECT * FROM faculdades ORDER BY nome ASC";
$cursos = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($cursos);

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
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Cadastro de Curso</h2>
        <form id="cadastro-form" action="servidor/cadastro/cadastrar-curso" method="post">
            <div class="form-group">
                <label for="nome">Nome do Curso:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
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
                <div class="form-group col-md-6">
                    <label for="faculdade">Faculdade:</label>
                    <Select name="id_faculdade" class="form-control" id="id_faculdade">
                    <option value=''>Selecione faculdade</option>
                    <?php
                    // se o número de resultados for maior que zero, mostra os dados
                    if ($total > 0) {
                        while ($linha = mysqli_fetch_assoc($cursos)) {
                    ?>                     
                    <option value='<?= $linha['id'] ?>'><?= $linha['nome'] ?></option>
                    <?php } 
                    } ?>                        
                    </Select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>

</html>