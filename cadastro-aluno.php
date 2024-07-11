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
    <title>Cadastro de Aluno</title>
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
        <h2>Cadastro de Aluno</h2>
        <form id="cadastro-form" action="servidor/cadastro/cadastrar-aluno" method="post" enctype="multipart/form-data">
            <div class=" form-group">
                <label for="nome">Nome do Aluno:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cpf">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                        placeholder="000.000.000-00" oninput="formatarCPF(this)" required>
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
                                $idFaculdade = $linha['faculdade_id'];
                                $sqlFaculdade = "SELECT * FROM faculdades WHERE id = '$idFaculdade'";
                                $faculdade = mysqli_query($conexao, $sqlFaculdade);
                                $dadosFaculdade = mysqli_fetch_assoc($faculdade);
                                ?>
                                <option value='<?= $linha['id'] ?>'>
                                    <?= $linha['nome'], "-" . $linha['semestre'], " (" . $dadosFaculdade['nome'] . ")" ?>
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
    <script>
        function formatarCPF(campo) {
            var valor = campo.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona ponto após o terceiro dígito
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona outro ponto após o sexto dígito
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona traço no final
            campo.value = valor;
        }
    </script>
</body>

</html>