<?php
include "../../classes/Controle.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$semestre = $_POST['semestre'];
$descricao = $_POST['descricao'];
$duracao = $_POST['duracao'];
$id_faculdade = $_POST['id_faculdade'];

$email = isset($_POST['email']);



$Controle = new Controle();

if ($Controle->atualizarCurso($id, $nome, $semestre,$descricao, $duracao, $id_faculdade)) {
    if($email){
        echo '<script>alert("Curso atualizado com sucesso!");
        window.location="../../cursos-faculdade";</script>';
    }else {
        echo '<script>alert("Curso atualizado com sucesso!");
        window.location="../../cursos";</script>';
    }
} else {
    echo '<script>alert("Curso ja foi cadastrado para essa faculdade! ");
            window.location="../../cursos";</script>';
}

