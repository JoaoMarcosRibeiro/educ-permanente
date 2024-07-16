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

$emailFaculdade = isset($_POST['email-faculdade']);


$Controle = new Controle();

if ($Controle->atualizarProfessor($id, $nome, $email, $telefone, $dataNascimento, $id_curso, $cpf, $rg)) {
    if($emailFaculdade){
        echo '<script>alert("Professor atualizado com sucesso!");
        window.location="../../professores-faculdade";</script>';
    }else {
        echo '<script>alert("Professor atualizado com sucesso!");
            window.location="../../professores";</script>';
    }
} else {
    echo '<script>alert("Professor ja foi cadastrado! ");
            window.location="../../professores";</script>';
}