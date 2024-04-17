<?php
include "../../classes/Controle.php";

$nome = $_POST['nome'];
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];


$Controle = new Controle();

if ($Controle->cadastrarFaculdade($nome, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email)) {
    echo '<script>alert("Faculdade cadastrada com sucesso!");
            window.location="../../faculdade";</script>';
} else {
    echo '<script>alert("Erro no cadastro");
            window.location="../../cadastro-faculdade";</script>';
}