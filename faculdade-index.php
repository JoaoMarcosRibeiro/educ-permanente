<?php session_start();
$email = $_SESSION['email'];

include "classes/ConexaoBanco.php";

$conexaobanco = new ConexaoBanco();

$conexao = $conexaobanco->conectar();

$sqlFaculdade = "SELECT * FROM ed_faculdades WHERE email = '$email'";
$faculdade = mysqli_query($conexao, $sqlFaculdade);
$dadosFaculdade = mysqli_fetch_assoc($faculdade);

$sqlUsuario = "SELECT * FROM ed_usuarios_faculdade WHERE email = '$email'";
$usuario = mysqli_query($conexao, $sqlUsuario);
$dadosUsuario = mysqli_fetch_assoc($usuario);

if (!$dadosUsuario) {
    header("location:login-faculdade");
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Educação permanente</title>
  <link rel="shortcut icon" href="img/faculdade.png">
  <!-- Adicionando CSS do Bootstrap -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    .menu {
      text-align: center;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    h2 {
      margin-bottom: 30px;
      color: #333333;
    }

    .list-group {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .list-group-item {
      font-size: 18px;
      border-radius: 8px;
      margin-bottom: 15px;
      padding: 15px 20px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .list-group-item:nth-child(1) {
      background-color: #007bff;
      /* Azul */
      color: #ffffff;
    }

    .list-group-item:nth-child(2) {
      background-color: #28a745;
      /* Verde */
      color: #ffffff;
    }

    .list-group-item:nth-child(3) {
      background-color: #f59042; 
      color: #ffffff;
    }
    .list-group-item:nth-child(4) {
      background-color: #7004fd; 
      color: #ffffff;
    }

    .list-group-item:nth-child(5) {
      background-color: #dc3545; /* Vermelho */
      color: #ffffff;
    }

    .list-group-item:nth-child(1):hover {
      background-color: #0056b3;
      /* Azul mais escuro */
    }

    .list-group-item:nth-child(2):hover {
      background-color: #3cc23f;
      /* Verde mais escuro */
    }

    .list-group-item:nth-child(3):hover {
      background-color: #f27b1f;
    }
    .list-group-item:nth-child(4):hover {
      background-color: #3b0383; 
    }

    .list-group-item:nth-child(5):hover {
      background-color: #b93134; /* Vermelho mais escuro */
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="menu">
      <h2>EDUCAÇÃO PERMANENTE</h2>
      <div class="list-group">
        <a href="faculdade-dados?id=<?= $dadosFaculdade['id'] ?>" class="list-group-item list-group-item-action">MEUS
          DADOS</a>
        <a href="cursos-faculdade" class="list-group-item list-group-item-action">MEUS CURSOS</a>
        <a href="alunos-faculdade" class="list-group-item list-group-item-action">MEUS ALUNOS</a>
        <a href="professores-faculdade" class="list-group-item list-group-item-action">MEUS PROFESSORES</a>
        <a href="servidor/login/logout-faculdade.php" class="list-group-item list-group-item-action">SAIR</a>
      </div>
    </div>
  </div>

  <!-- Adicionando JS do Bootstrap (opcional) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>