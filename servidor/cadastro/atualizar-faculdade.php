<?php
include "../../classes/Controle.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];



$Controle = new Controle();

if ($Controle->atualizarFaculdade($id, $nome, $cnpj, $cep, $logradouro, $numero, $complemento, $cidade, $estado, $telefone, $email)) {
    echo '<script>alert("Faculdade atualizada com sucesso!");
            window.location="../../faculdade";</script>';
} else {
    echo '<script>alert("Faculdade ja possui cadastrado! ");
            window.location="../../faculdade";</script>';
}