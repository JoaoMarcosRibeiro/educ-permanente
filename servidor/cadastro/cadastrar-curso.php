<?php
include "../../classes/Controle.php";

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$duracao = $_POST['duracao'];
$id_faculdade = $_POST['id_faculdade'];


$Controle = new Controle();

if ($Controle->cadastrarCurso($nome, $descricao, $duracao, $id_faculdade)) {
    echo '<script>alert("Curso cadastrado com sucesso!");
            window.location="../../index";</script>';
} else {
    echo '<script>alert("Erro no cadastro");
            window.location="../../index";</script>';
}