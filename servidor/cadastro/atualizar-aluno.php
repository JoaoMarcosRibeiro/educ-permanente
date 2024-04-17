<?php
include "../../classes/Controle.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$dataNascimento = $_POST['data_nascimento'];
$id_curso = $_POST['id_curso'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];


$Controle = new Controle();

if ($Controle->atualizarAluno($id, $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg)) {
    echo '<script>alert("Aluno atualizado com sucesso!");
            window.location="../../aluno";</script>';
} else {
    echo '<script>alert("Aluno ja foi cadastrado! ");
            window.location="../../aluno";</script>';
}