<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Atualizar Cadastro</title>
    <link rel="shortcut icon" href="img/faculdade.png">
    <link href="styles/style.css" rel="stylesheet">
    <!-- Adicionando CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionando jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Adicionando jquery-mask-plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- Script para preenchimento automático do endereço -->
    <script src="form-faculdade.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Atualizar Cadastro</h2>
        <?php
        // Verifica se o ID da faculdade foi passado via GET
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $faculdade_id = $_GET['id'];

            include "classes/ConexaoBanco.php";

            $conexaobanco = new ConexaoBanco();

            $conexao = $conexaobanco->conectar();

            // Consulta SQL para obter os dados da faculdade com o ID fornecido
            $sql = "SELECT * FROM ed_faculdades WHERE id = $faculdade_id";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Exibir o formulário de atualização com os dados preenchidos
                echo "<form action='servidor/cadastro/atualizar-faculdade.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<div class='form-group'>";
                echo "<label for='nome'>Nome da Faculdade:</label>";
                echo "<input type='text' class='form-control' id='nome' name='nome' value='" . $row["nome"] . "' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='cnpj'>CNPJ:</label>";
                echo "<input type='text' class='form-control' id='cnpj' name='cnpj' value='" . $row["cnpj"] . "' required>";
                echo "</div>";
                echo "<fieldset>";
                echo "<legend class='form-group'>Endereço:</legend>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='cep'>CEP:</label>";
                echo "<input type='text' class='form-control' id='cep' name='cep' value='" . $row["cep"] . "' required>";
                echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='logradouro'>Logradouro:</label>";
                echo "<input type='text' class='form-control' id='logradouro' name='logradouro' value='" . $row["logradouro"] . "' required readonly>";
                echo "</div>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='numero'>Número:</label>";
                echo "<input type='text' class='form-control' id='numero' name='numero' value='" . $row["numero"] . "'>";
                echo "</div>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='complemento'>Complemento:</label>";
                echo "<input type='text' class='form-control' id='complemento' name='complemento' value='" . $row["complemento"] . "'>";
                echo "</div>";
                echo "</div>";
                echo "<div class='form-row'>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='cidade'>Cidade:</label>";
                echo "<input type='text' class='form-control' id='cidade' name='cidade' value='" . $row["cidade"] . "' required readonly>";
                echo "</div>";
                echo "<div class='form-group col-md-6'>";
                echo "<label for='estado'>Estado:</label>";
                echo "<input type='text' class='form-control' id='estado' name='estado' value='" . $row["estado"] . "' required readonly>";
                echo "</div>";
                echo "</div>";
                echo "</fieldset>";
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
                echo "Nenhuma faculdade encontrada com o ID fornecido.";
            }
            $conexao->close();
        } else {
            echo "ID da faculdade não fornecido.";
        }
        ?>
    </div>
</body>

</html>