<?php
include "../../classes/Controle.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$duracao = $_POST['duracao'];
$id_faculdade = $_POST['id_faculdade'];



$Controle = new Controle();

if ($Controle->atualizarCurso($id, $nome, $descricao, $duracao, $id_faculdade)) {
    echo '<script>alert("Curso atualizado com sucesso!");
            window.location="../../curso";</script>';
} else {
    echo '<script>alert("Curso ja foi cadastrado para essa faculdade! ");
            window.location="../../curso";</script>';
}

