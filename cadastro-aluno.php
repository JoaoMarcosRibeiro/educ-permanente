<?php
include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sql = "SELECT * FROM cursos ORDER BY nome ASC";
$cursos = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($cursos);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Aluno</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Cadastro de Aluno</h2>
        <form id="cadastro-form" action="servidor/cadastro/cadastrar-aluno" method="post" enctype="multipart/form-data">
            <div class=" form-group">
                <label for="nome">Nome do Aluno:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cpf">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="rg">RG:</label>
                    <input type="text" class="form-control" id="rg" name="rg" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="data_nascimento">Data de nascimento:</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="curso">Curso:</label>
                    <Select name="id_curso" class="form-control" id="id_curso">
                        <option value=''>Selecione curso</option>
                        <?php
                        // se o número de resultados for maior que zero, mostra os dados
                        if ($total > 0) {
                            while ($linha = mysqli_fetch_assoc($cursos)) {
                                ?>
                                <option value='<?= $linha['id'] ?>'>
                                    <?= $linha['nome'] ?>
                                </option>
                                <?php ;
                            }
                        } ?>
                    </Select>
                </div>
            </div>
            <fieldset>
                <legend class="form-group">Contato:</legend>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="telefone">Telefone:</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
            </fieldset>
            <div class="form-group">
                <label for="arquivos">Arquivos:</label>
                <input type="file" class="form-control-file" id="arquivos" name="arquivos[]" accept=".pdf" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>

</html>