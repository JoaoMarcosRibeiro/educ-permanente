<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elementos Centralizados</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .senha {
            border: 2px solid #000;
            padding: 20px;
            margin: 10px 0;
            text-align: center;
        }
        .listas {
            padding: 0;
        }
        ul {
            list-style-type: disc;
            padding-left: 20px;
            text-align: left;
            padding: 0;
        }
    </style>
</head>
<body>
    
    <h2>Sua senha de acesso ao portal FHFS</h2>
    <div class="senha">
        Senha
    </div>
    
    <div class="listas">
    <h2>Documentos necessários:</h2>
    <ul>
        <li>Apólice de seguros</li>
        <li>Termo de compromisso de estagio de cada estudante</li>
        <li> Escala com os alunos e dias que os mesmos estarão na unidade</li>
    </ul>

    <h2>Documentos para ser anexados no cadastro dos alunos:</h2>
    <ul>
        <li>RG</li>
        <li>CPF</li>
        <li>Cartão de vacina atualizado(3 dozes de hepatite B, COVID, Reforço de DT)</li>
    </ul>

    <h2>Documentos para ser anexados no cadastro dos professores:</h2>
    <ul>
        <li>Carteira do conselho do professor</li>
        <li>Cartão de vacina atualizado(3 dozes de hepatite B, COVID, Reforço de DT)</li>
    </ul>
    </div>
</body>
</html>
