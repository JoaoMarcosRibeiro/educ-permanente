<?php
include "../../classes/Controle.php";

$nome = $_POST['nome'];
$semestre = $_POST['semestre'];
$descricao = $_POST['descricao'];
$duracao = $_POST['duracao'];
$id_faculdade = $_POST['id_faculdade'];

$email = isset($_POST['email']);


$Controle = new Controle();

if ($Controle->cadastrarCurso($nome, $semestre,$descricao, $duracao, $id_faculdade)) {
    if($email){
        echo '<script>alert("Curso cadastrado com sucesso!");
            window.location="../../cursos-faculdade";</script>';
    }else {
        echo '<script>alert("Curso atualizado com sucesso!");
        window.location="../../cursos";</script>';
    }
    
} else {
    echo '<script>alert("Erro no cadastro");
            window.location="../../cursos";</script>';
}