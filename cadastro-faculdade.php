<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Faculdade</title>
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
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index">EDUCAÇÃO PERMANENTE</a>
    </nav>
    <div class="container">
        <h2>Cadastro de Faculdade</h2>
        <form id="cadastro-form" action="servidor/cadastro/cadastrar-faculdade" method="post">
            <div class="form-group">
                <label for="nome">Nome da Faculdade:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00" required>
            </div>
            <fieldset>
                <legend class="form-group">Endereço:</legend>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cep">CEP:</label>
                        <input type="text" class="form-control" id="cep" name="cep" maxlength="9" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <label for="logradouro">Logradouro:</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro" required readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="numero">Número:</label>
                        <input type="text" class="form-control" id="numero" name="numero">
                    </div>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" class="form-control" id="complemento" name="complemento">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cidade">Cidade:</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="estado">Estado:</label>
                        <input type="text" class="form-control" id="estado" name="estado" required readonly>
                    </div>
                </div>
            </fieldset>
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
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>

</html>